<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 08:58
 */
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '/Viettel/Viettel_mps_data.php';

use nguyenanhung\ThuDoMultimediaVasServices\BaseMsisdn;

/**
 * Class Msisdn
 *
 * @package   Library
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Msisdn extends BaseMsisdn
{
    protected $CI;
    public    $DEBUG; // TRUE nếu bật Debug
    // Nếu đang DEV thì log lưu ở chế độ DEBUG, Đang test thì chế độ INFO, Chạy ổn thì đặt WARNING
    public $loggerLevel = 'error';
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

    /**
     * Msisdn constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->config('config_vas_telcos');
        $this->DEBUG                   = TRUE;
        $this->loggerPath              = APPPATH . 'logs-data' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR;
        $this->loggerFilename          = 'Log-' . date('Y-m-d') . '.log';
        $this->providerTelcos          = config_item('provider_telcos');
        $this->providerServices        = config_item('provider_services');
        $this->packageSettings         = config_item('package_settings');
        $this->telcoWebSignUp          = config_item('telco_web_sign_up');
        $this->websiteData             = config_item('website_data');
        $this->viettelMpsDataFile      = new Viettel_mps_data();
        $this->notifySuccessRegister   = site_url('thong-bao/dang-ky-dich-vu-thanh-cong');
        $this->notifyFailedRegister    = site_url('thong-bao/dang-ky-dich-vu-that-bai');
        $this->notifySuccessUnRegister = site_url('thong-bao/huy-dich-vu-thanh-cong');
        $this->notifyFailedUnRegister  = site_url('thong-bao/huy-dich-vu-that-bai');
        $this->cachePhoneDetectIp      = TRUE;
        $this->cachePhoneDetectIpPath  = __DIR__ . '/../../storage/cache/';
        parent::__construct();
    }
}
