<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 16:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Viettel
 *
 * @property object config
 * @property object msisdn
 * @property object session
 * @property object input
 */
class Viettel extends MX_Controller
{
    const MOBIFONE                = 'MobiFone';
    const RETURN_URL_METHOD       = 'dich-vu/viettel/dang-ky-thanh-cong/';
    const RETURN_URL_METHOD_UNREG = 'dich-vu/viettel/huy-dich-vu-thanh-cong/';
    const VALID_SIGNATURE         = FALSE; // TRUE sẽ xác thực chữ ký nhà mạng trả vê
    const SESSION_LINK_REDIRECT   = 'link_redirect_viettel';
    protected $loggerOptions;
    protected $providerTelcos;
    protected $providerServices;
    protected $packageSettings;
    protected $telcoWebSignUp;

    /**
     * Viettel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library('msisdn');
        $this->config->load('config_vas_telcos');
        $this->providerTelcos   = config_item('provider_telcos');
        $this->providerServices = config_item('provider_services');
        $this->packageSettings  = config_item('package_settings');
        $this->telcoWebSignUp   = config_item('telco_web_sign_up');
        $this->loggerOptions    = [
            'debugStatus'         => TRUE,
            'debugLoggerPath'     => APPPATH . 'logs-data/VasGate/',
            'debugLoggerFilename' => 'Log-' . date('Y-m-d') . '.log'
        ];
    }

    /**
     * Function callback - nhận dữ liệu Msisdn
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/20/18 17:23
     *
     * @link  : /viettel/msisdn.html
     *
     */
    public function callback()
    {
        $data_encrypted = $this->input->get('DATA');
        $signature      = $this->input->get('SIG');
        if ($this->session->has_userdata(self::SESSION_LINK_REDIRECT)) {
            $link_redirect = site_url($this->session->userdata(self::SESSION_LINK_REDIRECT));
        } else {
            $link_redirect = site_url();
        }
        if ($data_encrypted === NULL || $signature === NULL) {
            $this->msisdn->user_is_guest();
            redirect();
        } else {
            $this->msisdn->mpsViettelDecode($data_encrypted, $signature, $link_redirect);
        }
    }

    /**
     * Function analyze - Hàm phân tích dữ liệu trả về từ Mps
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 16:12
     */
    public function analyze()
    {
        $data = $this->input->get('data');
        $data = trim($data);
        if ($this->session->has_userdata(self::SESSION_LINK_REDIRECT)) {
            $link_redirect = site_url($this->session->userdata(self::SESSION_LINK_REDIRECT));
        } else {
            $link_redirect = site_url();
        }
        $this->msisdn->viettelAnalyzeDataFromMps($data, $link_redirect);
    }

    /**
     * Function subscribe - Hàm đăng ký dịch vụ Viettel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 15:47
     *
     * @param string $packageId
     *
     * @link  /dich-vu/viettel/dang-ky-su-dung-dich-vu/(:any).html
     */
    public function subscribe($packageId = '')
    {
        $this->msisdn->viettelSubscribeRedirectToMps($packageId);
    }

    /**
     * Function unsubscribe - Hàm hủy dịch vụ Viettel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 15:49
     *
     * @param string $packageId
     *
     * @link  /dich-vu/viettel/huy-su-dung-dich-vu/(:any).html
     */
    public function unsubscribe($packageId = '')
    {
        $this->msisdn->viettelUnsubscribeRedirectToMps($packageId);
    }

    /**
     * Function simple_unsubscribe - Hàm hủy dịch vụ Viettel gọi qua API Gateway
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 15:55
     *
     * @param string $packageId
     *
     * @link  /dich-vu/viettel/simple-unsubscribe/(:any).html
     */
    public function simple_unsubscribe($packageId = '')
    {
        $this->msisdn->viettelSimpleUnsubscribe($packageId);
    }
}