<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 9/5/18
 * Time: 09:18
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Vinaphone
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 *
 * @property object config
 * @property object msisdn
 */
class Vinaphone extends MX_Controller
{
    const VINAPHONE               = 'Vinaphone';
    const RETURN_URL_METHOD       = 'dich-vu/vinaphone/dang-ky-thanh-cong/';
    const RETURN_URL_METHOD_UNREG = 'dich-vu/vinaphone/huy-dich-vu-thanh-cong/';
    protected $loggerOptions;
    protected $webSignUp;
    protected $vinaConfig;

    /**
     * Vinaphone constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library('msisdn');
        $this->config->load('config_vas_telcos');
        $this->webSignUp     = config_item('telco_web_sign_up');
        $this->vinaConfig    = $this->webSignUp[self::VINAPHONE];
        $this->loggerOptions = [
            'debugStatus'         => TRUE,
            'debugLevel'          => 'error',
            'debugLoggerPath'     => APPPATH . 'logs-data/VasGate/',
            'debugLoggerFilename' => 'Log-' . date('Y-m-d') . '.log'
        ];
    }

    /**
     * Function subscribe
     * Hàm đăng ký dịch vụ nhà mạng Vinaphone
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/20/18 11:26
     *
     * @param string $packageId
     *
     * @link  /dich-vu/vinaphone/dang-ky-su-dung-dich-vu/packagename.html
     */
    public function subscribe($packageId = '')
    {
        $vina = new nguyenanhung\VnTelcoPhoneNumberDetect\Telcos\Vinaphone($this->loggerOptions);
        $vina->setVasGate($this->vinaConfig);
        if ($this->vinaConfig['useVasCloud'] === TRUE) {
            $msisdn = $this->msisdn->getMsisdn();
            if (empty($msisdn)) {
                $vina->vascloudIsWeb(TRUE);
            }
            $vina->vasCloud($packageId, 'REG');
        } else {
            $vina->subscribe($packageId);
        }
    }

    /**
     * Function unsubscribe
     * Hàm hủy sử dụng dịch vụ nhà mạng Vinaphone
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/27/18 15:28
     *
     * @param string $packageId
     *
     * @link  /dich-vu/vinaphone/huy-su-dung-dich-vu/packagename.html
     */
    public function unsubscribe($packageId = '')
    {
        $vina = new nguyenanhung\VnTelcoPhoneNumberDetect\Telcos\Vinaphone($this->loggerOptions);
        $vina->setVasGate($this->vinaConfig);
        if ($this->vinaConfig['useVasCloud'] === TRUE) {
            $msisdn = $this->msisdn->getMsisdn();
            if (empty($msisdn)) {
                $vina->vascloudIsWeb(TRUE);
            }
            $vina->vasCloud($packageId, 'UNREG');
        } else {
            $vina->unsubscribe($packageId);
        }
    }
}
