<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 11:27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VNM\VietnamobileLandingPage;

/**
 * Class Vietnamobile
 *
 * @property object $config
 * @property object $msisdn
 * @property object $input
 */
class Vietnamobile extends MX_Controller
{
    const VALID_SIGNATURE                    = FALSE; // TRUE sẽ xác thực chữ ký nhà mạng trả vê
    const INPUT_NAME_MSISDN_FROM_REGISTER    = 'user_msisdn';
    const INPUT_NAME_OTP_CODE_FROM_REGISTER  = 'user_otp_code';
    const CURRENT_CHANNEL                    = 'WAP';
    const SESSION_ID_VNM_CURRENT_REGISTER_ID = 'VNM_CURRENT_REGISTER_ID';
    const SESSION_ID_VNM_CURRENT_OTP_MSISDN  = 'VNM_CURRENT_OTP_MSISDN';
    const SESSION_ID_VNM_CURRENT_OTP_ID      = 'VNM_CURRENT_OTP_ID';
    const SESSION_ID_VNM_CURRENT_OTP_CODE    = 'VNM_CURRENT_OTP_CODE';

    /** @var null|array */
    private $sdkConfig;

    /**
     * Vietnamobile constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'form', 'form', 'assets']);
        $this->load->library(['seo', 'session', 'msisdn', 'phone_number', 'Site/db_config']);
        $this->config->load('config_vas_telcos');
        $this->config->load('config_vnm_sdk');
        $this->sdkConfig = config_item('vnm_sdk_config');
    }

    /**
     * Function callback - Hàm MSISDN Callback nhà mạng Vietnamobile
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/20/18 11:28
     *
     * @link  : /vnm/msisdn/callback.html
     *
     */
    public function callback()
    {
        $configProvider = config_item('provider_services');
        $vnmConfig      = $configProvider['Vietnamobile'];
        if (isset($vnmConfig['detectionIsSDP']) && strtoupper($vnmConfig['detectionIsSDP']) === TRUE) {
            $response_params = [
                'request_id'  => $this->input->get('transid', TRUE),
                'transid'     => $this->input->get('transid', TRUE),
                'isdn'        => $this->input->get('isdn', TRUE),
                'status'      => $this->input->get('status', TRUE),
                'description' => $this->input->get('description', TRUE),
                'hash'        => $this->input->get('hash', TRUE)
            ];
        } else {
            $response_params = [
                'cp_id'          => $this->input->get('cp_id', TRUE),
                'message'        => $this->input->get('message', TRUE),
                'request_id'     => $this->input->get('request_id', TRUE),
                'request_time'   => $this->input->get('request_time', TRUE),
                'response_code'  => $this->input->get('response_code', TRUE),
                'response_time'  => $this->input->get('response_time', TRUE),
                'transaction_id' => $this->input->get('transaction_id', TRUE),
                'signature'      => $this->input->get('signature', TRUE),
                'addition_data'  => $this->input->get('addition_data', TRUE)
            ];
        }
        $this->msisdn->vnmCallbackResponse($response_params);
        redirect();
    }

    /**
     * Function callback detection - Hàm MSISDN Callback nhận diện thuê bao từ SDP Detection
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/20/18 11:28
     *
     * @link  : /sdp/vnm/msisdn/callback.html
     *
     */
    public function detection_callback()
    {
        $responseData = [
            'request_id'  => $this->input->get('transid', TRUE),
            'transid'     => $this->input->get('transid', TRUE),
            'isdn'        => $this->input->get('isdn', TRUE),
            'status'      => $this->input->get('status', TRUE),
            'description' => $this->input->get('description', TRUE),
            'hash'        => $this->input->get('hash', TRUE)
        ];
        $this->msisdn->vnmCallbackResponse($responseData);
        redirect();
    }

    /**
     * Function register_via_landing_page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/20/18 17:43
     *
     * @param string $packageId
     *
     * @link  /dich-vu/vnm/dang-ky-su-dung-dich-vu/(:any).html
     */
    public function register_via_landing_page($packageId = '')
    {
        $landingPage = new VietnamobileLandingPage($this->sdkConfig['SDP_OPTIONS']);
        $landingPage->setResponseIsObject()->setSdkConfig($this->sdkConfig)->startTransaction()->setPackageId(strtoupper($packageId))->generateLandingPageUrl()->redirectLandingPageUrl();
    }

    /**
     * Function register_callback
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/20/18 17:49
     *
     * @link  /vnm/register/callback
     */
    public function register_callback()
    {
        $landingPage = new VietnamobileLandingPage($this->sdkConfig['SDP_OPTIONS']);
        $landingPage->setResponseIsObject()->setSdkConfig($this->sdkConfig)->setDataResponseFromSdpLandingPage($_SERVER['QUERY_STRING'])->parseDataResponseFromSdpLandingPage()->registerWithLandingPage();
        $registerResult = $landingPage->getRegisterResult();
        if (isset($registerResult->status) && $registerResult->status == EXIT_SUCCESS) {
            // Đăng ký thành công => cần gen link thông báo đăng ký thành công
            redirect();
        } else {
            // Đăng ký thất bại -> gen link thông báo đăng ký thất bại
            redirect();
        }
    }
}
