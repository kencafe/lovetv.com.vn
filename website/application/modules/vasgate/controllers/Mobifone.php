<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/1/18
 * Time: 08:48
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Mobifone
 *
 * @property object config
 * @property object msisdn
 */
class Mobifone extends MX_Controller
{
    const MOBIFONE                = 'MobiFone';
    const RETURN_URL_METHOD       = 'dich-vu/mobifone/dang-ky-thanh-cong/';
    const RETURN_URL_METHOD_UNREG = 'dich-vu/mobifone/huy-dich-vu-thanh-cong/';
    protected $loggerOptions;
    protected $providerTelcos;
    protected $providerServices;
    protected $packageSettings;
    protected $telcoWebSignUp;

    /**
     * Mobifone constructor.
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
     * Function msisdn_request - Hàm Request MSISDN MobiFone
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 10:27
     *
     * @link  : /mobifone/msisdn.html
     */
    public function msisdn_request()
    {
        $this->msisdn->mobifoneRequestMsisdn();
    }

    /**
     * Function msisdn_callback - Hàm Callback MSISDN MobiFone
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 10:37
     * @link  : /mobifone/msisdn/callback.html
     *
     */
    public function msisdn_callback()
    {
        $this->msisdn->mobifoneCallbackMsisdn();
    }

    /**
     * Function subscribe - Hàm Request tới Gateway Register của MobiFone
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 10:46
     * @link  : /dich-vu/mobifone/dang-ky-su-dung-dich-vu/(:any).html
     *
     * @param string $packageId
     */
    public function subscribe($packageId = '')
    {
        $this->msisdn->mobifoneRequestRegister($packageId);
    }

    /**
     * Function subscribe_callback - Hàm callback Register
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 10/1/18 11:07
     * @link  : /mobifone/register/callback.html
     */
    public function subscribe_callback()
    {
        $this->msisdn->mobifoneCallbackRegister();
    }
}
