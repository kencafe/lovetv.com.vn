<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/21/18
 * Time: 15:32
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Vietnamobile
 *
 * @property object $config
 * @property object $msisdn
 * @property object $input
 * @property object $db_config
 * @property object $phone_number
 */
class Vietnamobile extends MX_Controller
{
    const VIETNAMOBILE                                = 'Vietnamobile';
    const INPUT_NAME_MSISDN_FROM_REGISTER             = 'user_msisdn';
    const INPUT_NAME_OTP_CODE_FROM_REGISTER           = 'user_otp_code';
    const SESSION_ID_VNM_INVITE_REGISTER_NOTIFY_IS_OK = 'VNM_INVITE_REGISTER_NOTIFY_IS_OK';
    const SESSION_ID_VNM_INVITE_REGISTER_NOTIFY       = 'VNM_INVITE_REGISTER_NOTIFY';
    const TPL_MASTER                                  = 'index';
    /** @var mixed Provider Services */
    protected $provider_services;
    /** @var null|array Package Settings */
    protected $package_settings;
    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var string Folder Views */
    public $folder_views = 'vasgate/vietnamobile';
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Vietnamobile constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'form', 'assets', 'text']);
        $this->load->library(['seo', 'session', 'msisdn', 'phone_number', 'Site/db_config']);
        $this->config->load('config_vas_telcos');
        $this->provider_services = config_item('provider_services');
        $this->package_settings  = $this->provider_services[self::VIETNAMOBILE]['package'];
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Hàm tiếp nhận thông tin đăng ký và send mã OTP dịch vụ
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 14:27
     *
     * @param string $package
     *
     * @link  : /dich-vu/vnm/dang-ky-su-dung-dich-vu/(:any).html
     */
    public function register($package = '')
    {
        $data = [];
        if (empty($package)) {
            redirect();
        }
        $packageId = strtoupper($package);
        if (!array_key_exists($packageId, $this->package_settings)) {
            redirect();
        }
        $packageInfo = $this->package_settings[$packageId];
        // $hasDetectMsisdn = TRUE;
        $hasDetectMsisdn = $this->msisdn->msisdnHasDetect();
        $getTelcos       = $this->msisdn->getTelcos();
        if ($hasDetectMsisdn && $getTelcos['telco_name'] == self::VIETNAMOBILE) {
            // Nếu đã nhận diện thuê bao đúng của nhà mạng VNM
            $sessionMsisdn = $this->msisdn->getMsisdnInSession();
            $detectTelco   = $this->phone_number->detect_carrier($sessionMsisdn);
            if ($detectTelco == self::VIETNAMOBILE) {
                $msisdn                    = $sessionMsisdn;
                $data['hasDetectMsisdn']   = $hasDetectMsisdn;
                $data['hasDetectIsMsisdn'] = $sessionMsisdn;
            } else {
                $msisdn                    = NULL;
                $data['hasDetectMsisdn']   = NULL;
                $data['hasDetectIsMsisdn'] = NULL;
            }
        } else {
            $data['hasDetectMsisdn']   = NULL;
            $data['hasDetectIsMsisdn'] = $this->input->post(self::INPUT_NAME_MSISDN_FROM_REGISTER, TRUE);
            $msisdn                    = $this->input->post(self::INPUT_NAME_MSISDN_FROM_REGISTER, TRUE);
        }
        $isPostUserData = count($_POST) > 0 ? TRUE : FALSE;
        $inputData      = [
            'msisdn'          => $msisdn,
            'hasDetectMsisdn' => $hasDetectMsisdn,
            'packageId'       => $packageId,
            'packageInfo'     => isset($packageInfo) ? $packageInfo : NULL,
            'isPostUserData'  => $isPostUserData
        ];
        $module         = new \nguyenanhung\WebBuilderSDK\ModuleVasGate\VasGateVietnamobile($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)
               ->setProviderServices($this->provider_services)
               ->setPackageSettings($this->package_settings)
               ->setBaseMsisdn($this->msisdn)
               ->setInputData($inputData)
               ->parseRegisterAndSendOTP();
        $data = $module->getResponse();
        $this->load->view(self::TPL_MASTER, [
            'sub'  => $this->folder_views . '/register',
            'data' => $data
        ]);
    }

    /**
     * Hàm hướng dẫn kích hoạt dịch vụ đối với người dùng 3G
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-15 16:18
     *
     * @param string $package
     *
     * @link  : /dich-vu/vnm/huong-dan-kich-hoat-dich-vu/(:any).html
     */
    public function register_activation_instructions($package = '')
    {
        $data = [];
        if (empty($package)) {
            redirect();
        }
        $packageId = strtoupper($package);
        if (!array_key_exists($packageId, $this->package_settings)) {
            redirect();
        }
        $packageInfo = $this->package_settings[$packageId];
        // $hasDetectMsisdn = TRUE;
        $hasDetectMsisdn = $this->msisdn->msisdnHasDetect();
        $getTelcos       = $this->msisdn->getTelcos();
        if ($hasDetectMsisdn && $getTelcos['telco_name'] == self::VIETNAMOBILE) {
            // Nếu đã nhận diện thuê bao đúng của nhà mạng VNM
            $sessionMsisdn = $this->msisdn->getMsisdnInSession();
            $detectTelco   = $this->phone_number->detect_carrier($sessionMsisdn);
            if ($detectTelco == self::VIETNAMOBILE) {
                $msisdn                    = $sessionMsisdn;
                $data['hasDetectMsisdn']   = $hasDetectMsisdn;
                $data['hasDetectIsMsisdn'] = $sessionMsisdn;
            } else {
                $msisdn                    = NULL;
                $data['hasDetectMsisdn']   = NULL;
                $data['hasDetectIsMsisdn'] = NULL;
            }
        } else {
            $data['hasDetectMsisdn']   = NULL;
            $data['hasDetectIsMsisdn'] = $this->input->post(self::INPUT_NAME_MSISDN_FROM_REGISTER, TRUE);
            $msisdn                    = $this->input->post(self::INPUT_NAME_MSISDN_FROM_REGISTER, TRUE);
        }
        $inputData = [
            'msisdn'          => $msisdn,
            'hasDetectMsisdn' => $hasDetectMsisdn,
            'packageId'       => $packageId,
            'packageInfo'     => isset($packageInfo) ? $packageInfo : NULL,
        ];
        $module    = new \nguyenanhung\WebBuilderSDK\ModuleVasGate\VasGateVietnamobile($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->setProviderServices($this->provider_services)->setPackageSettings($this->package_settings)->setBaseMsisdn($this->msisdn)->setInputData($inputData)->parseRegisterAndNotifySendInviteRegister();
        $data          = $module->getResponse();
        $notifyMessage = $this->msisdn->getSessionData(self::SESSION_ID_VNM_INVITE_REGISTER_NOTIFY);
        if (!empty($notifyMessage)) {
            $notifyMessage = str_replace('[TONG_DAI_CHAM_SOC_KHACH_HANG]', config_item('cms_site_hotline'), $notifyMessage);
        }
        $data['notifyMessage'] = $notifyMessage;
        $this->load->view(self::TPL_MASTER, [
            'sub'  => $this->folder_views . '/register_activation_instructions',
            'data' => $data
        ]);
    }

    /**
     * Hàm tiếp nhận thông tin mã OTP Code và xử lý verify OTP Code
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-22 01:50
     *
     * @param string $package
     *
     * @link  : /dich-vu/vnm/xac-nhan-dang-ky-su-dung-dich-vu/(:any).html
     */
    public function verify_register($package = '')
    {
        if (empty($package)) {
            redirect();
        }
        // Xử lý logic dịch vụ
        $packageId = strtoupper($package);
        if (!array_key_exists($packageId, $this->package_settings)) {
            redirect();
        }
        $packageInfo    = $this->package_settings[$packageId];
        $otpCode        = $this->input->post(self::INPUT_NAME_OTP_CODE_FROM_REGISTER, TRUE);
        $isPostUserData = count($_POST) > 0 ? TRUE : FALSE;
        $inputData      = [
            'packageId'      => $packageId,
            'packageInfo'    => isset($packageInfo) ? $packageInfo : NULL,
            'isPostUserData' => $isPostUserData,
            'inputOtpCode'   => $otpCode
        ];
        $module         = new \nguyenanhung\WebBuilderSDK\ModuleVasGate\VasGateVietnamobile($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)
               ->setProviderServices($this->provider_services)
               ->setPackageSettings($this->package_settings)
               ->setBaseMsisdn($this->msisdn)
               ->setInputData($inputData)
               ->parseRegisterAndVerifyOTP();
        $data = $module->getResponse();
        $this->load->view(self::TPL_MASTER, [
            'sub'  => $this->folder_views . '/register_verify_otp',
            'data' => $data
        ]);
    }
}
