<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//include __DIR__ . '/Viettel/Viettel_mps_data.php';

/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 08:58
 */

use nguyenanhung\ThuDoMultimediaVasServices\BaseMsisdn;
use nguyenanhung\MyDebug\Debug;

class Msisdn extends BaseMsisdn
{
    protected $CI;
    public    $DEBUG; // TRUE nếu bật Debug
    // Nếu đang DEV thì log lưu ở chế độ DEBUG, Đang test thì chế độ INFO, Chạy ổn thì đặt WARNING
    public $loggerLevel = 'info';
    public $loggerPath; // Thư mục ghi Log
    public $loggerFilename; // Tên file Log
    /** @var bool Cấu hình cache thư viện Detect Phone by IP */
    public $cachePhoneDetectIp = FALSE;
    /** @var bool Cấu hình thư mục lưu trữ cache thư viện Detect Phone by IP */
    public $cachePhoneDetectIpPath = NULL;
    public $providerTelcos;
    public $providerServices;
    public $packageSettings;
    public $telcoWebSignUp;
    public $objectSession; // Object Session
    public $viettelMpsDataFile; // Map tới file Data MPS
    public $_debug;
    const SESSION_ID_CURRENT_ACCESS_TOKEN = 'CURRENT_ACCESS_TOKEN';

    /**
     * Msisdn constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
//        $this->CI->config->load('config_vas_telcos');
        $this->DEBUG                   = TRUE;
        $this->loggerPath              = APPPATH . 'logs-data' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;
        $this->loggerFilename          = 'Log-' . date('Y-m-d') . '.log';
        $this->providerTelcos          = config_item('provider_telcos');
        $this->providerServices        = config_item('provider_services');
        $this->packageSettings         = config_item('package_settings');
        $this->telcoWebSignUp          = config_item('telco_web_sign_up');
        $this->objectSession           = $this->CI->session;
//        $this->viettelMpsDataFile      = new Viettel_mps_data();
        $this->notifySuccessRegister   = site_url('thong-bao/dang-ky-dich-vu-thanh-cong');
        $this->notifyFailedRegister    = site_url('thong-bao/dang-ky-dich-vu-that-bai');
        $this->notifySuccessUnRegister = site_url('thong-bao/huy-dich-vu-thanh-cong');
        $this->notifyFailedUnRegister  = site_url('thong-bao/huy-dich-vu-that-bai');
        $this->cachePhoneDetectIp      = TRUE;
        $this->cachePhoneDetectIpPath  = __DIR__ . '/../../storages/cache/';
        parent::__construct();
        // Config debug
        $this->_debug = new Debug();
        $this->_debug->setLoggerSubPath(__CLASS__);
        if ($this->DEBUG === TRUE) {
            $this->_debug->setDebugStatus($this->DEBUG);
            if (!empty($this->loggerPath)) {
                $this->_debug->setLoggerPath($this->loggerPath);
            }
            if (!empty($this->loggerFilename)) {
                $this->_debug->setLoggerFilename($this->loggerFilename);
            }
        }
        if (isset($this->loggerVendor) && !empty($this->loggerVendor) && is_array($this->loggerVendor)) {
            $this->vendorDebug = $this->loggerVendor;
        } else {
            $this->vendorDebug = [
                'debugStatus'            => isset($this->DEBUG) ? $this->DEBUG : FALSE,
                'debugLevel'             => isset($this->loggerLevel) ? $this->loggerLevel : FALSE,
                'debugLoggerPath'        => isset($this->loggerPath) ? $this->loggerPath : NULL,
                'debugLoggerFilename'    => (!empty($this->loggerFilename)) ? $this->loggerFilename : 'Log-' . date('Y-m-d') . '.log',
                'cachePhoneDetectIp'     => isset($this->cachePhoneDetectIp) ? $this->cachePhoneDetectIp : FALSE,
                'cachePhoneDetectIpPath' => isset($this->cachePhoneDetectIpPath) ? $this->cachePhoneDetectIpPath : NULL
            ];
        }
    }
}
