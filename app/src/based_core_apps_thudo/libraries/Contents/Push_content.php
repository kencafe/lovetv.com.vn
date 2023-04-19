<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/20/2017
 * Time: 3:25 PM
 * ------------------------
 * Ngày 14/10/2017
 *
 * Viết lại thư viện nhằm xử lý cho các dịch vụ PKD quản lý được VasGate
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Push_content
{
    public $DEBUG;
    public $logger_path;
    public $logger_file;
    protected $CI;
    protected $content_url;
    protected $content_username;
    protected $content_password;
    protected $shortcode;
    protected $date;
    protected $int_date;
    protected $check_exists_today = true;
    private $_webServices;
    private $_vascloud;
    /**
     * Xo_so constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->library(array(
            'phone_number',
            'requests'
        ));
        $this->CI->config->load('config_vinaphone_services');
        $this->_webServices     = config_item('vinaphone_web_services');
        $this->_vascloud        = config_item('vascloud');
        // Log Configures
        $this->DEBUG            = false;
        $this->logger_path      = APPPATH . 'logs-data/Libraries/Push-Contents/';
        $this->logger_file      = 'Log-' . date('Y-m-d') . '.log';
        // API Content
        $this->content_url      = 'http://123.30.235.188:1388/api/v1/ndxs.html';
        $this->content_username = 'hungna';
        $this->content_password = '43e776ae43758943e079b9313f31f414';
    }
    /**
     * Set Shortcode
     * @param string $shortcode
     * @return null|string
     */
    public function setShortcode($shortcode = '')
    {
        $this->shortcode = !empty($shortcode) ? $shortcode : null;
        return $this->shortcode;
    }
    /**
     * Set Date
     * @param string $date
     * @return false|string
     */
    public function setDate($date = '')
    {
        if (!empty($date))
        {
            $this->date     = $date;
            $this->int_date = date('Ymd', strtotime($date));
        }
        else
        {
            $this->date     = date('Y-m-d');
            $this->int_date = date('Ymd');
        }
        return $this;
    }
    /**
     * Map Package
     *
     * @param string $package
     * @return null|string
     */
    public function map_package($package = '')
    {
        $out = in_array($package, array(
            'NGAY',
            'TUAN',
            'VIP',
            'CONTENT'
        )) ? 'TV' : null;
        return $out;
    }
    /**
     * Get Content Xo So
     * @param string $package
     * @return string
     */
    public function get_content($package = '')
    {
        $package         = strtoupper($package);
        $package         = $this->map_package($package);
        $package_allowed = array(
            'TV'
        );
        if (in_array($package, $package_allowed))
        {
            $params        = array(
                'shortcode' => $this->shortcode,
                'code' => $package,
                'date' => $this->date,
                'acc' => $this->content_username,
                'signature' => md5($this->shortcode . '$' . $package . '$' . $this->date . '$' . $this->content_username . '$' . $this->content_password)
            );
            $send_request  = $this->CI->requests->sendRequest($this->content_url, $params);
            $parse_request = json_decode(trim($send_request));
            if (isset($parse_request->details) && !empty($parse_request->details))
            {
                $msg = $parse_request->details;
            }
            else
            {
                $msg = null;
            }
        }
        else
        {
            $msg = null;
        }
        return trim($msg);
    }

    /**
     * Send Content to Msisdn
     * @param string $msisdn
     * @param string $package
     * @param string $service_id
     * @param bool $ready
     * @return bool
     */
    public function send_content($msisdn = '', $package = '', $service_id = '', $mo = '', $moid = '', $ready = false)
    {
        $input_package = strtoupper($package);
        if($mo === '')
        {
            $send_mo       = 'DAILY CONTENT ' . $input_package;
        }
        else
        {
            $send_mo       = $mo;
        }
        self::_save_log('SMS Msisdn: ' . $msisdn);
        self::_save_log('SMS Package: ' . $package);
        self::_save_log('SMS ServiceId: ' . $service_id);
        self::_save_log('SMS Send Day: ' . $this->int_date);
        self::_save_log('SMS Send MO: ' . $send_mo);
        $mt_msg = $this->get_content($package);
        self::_save_log('SMS Send Msg: ' . $mt_msg);
        if (empty($mt_msg))
        {
            return false;
        }
        if($this->_vascloud === true)
        {
            // SMS config SMS Vascloud
            $this->CI->config->load('config_vinaphone_vascloud');
            $this->_webServices     = config_item('vascloud_api_services');
            $sms_url    = private_api_url($this->_webServices['sendSms']['url']);
            $sms_token  = $this->_webServices['sendSms']['token'];
            $sms_prefix = $this->_webServices['sendSms']['prefix'];
            // SMS Params
            $sms_params = array(
                'msisdn' => $msisdn,
                'moid' => $moid,
                'mo' => $send_mo,
                'mt' => $mt_msg,
                'note' => 'REG ' . $package,
                'package_code' => $package,
                'signature' => md5($msisdn . $sms_prefix . $mt_msg . $sms_prefix . $sms_token),
                'sub_code' => 'Register Contents'
            );
            if($this->_webServices['sendSms']['is_development'] == true){
                $sms_params['send_method'] = 'Msg_Log';
            }
            if ($this->check_exists_today === true && $ready === false)
            {
                $this->CI->load->model('Vina_Services/db_sms_history_model');
                $check_exists = $this->CI->db_sms_history_model->check_daily_content($msisdn, $send_mo, $this->int_date);
            }
            else
            {
                $check_exists = 0;
            }
            if (!$check_exists)
            {
                $sms_request = $this->CI->requests->sendRequest($sms_url, $sms_params);
            }
            self::_save_log('SMS URL: ' . $sms_url);
            self::_save_log('SMS Params: ', $sms_params);
            if (isset($sms_request))
            {
                self::_save_log('SMS Response: ' . $sms_request);
            }
        }
        else
        {
            // SMS config SMSGW
            $sms_url    = private_api_url($this->_webServices['sendSms']['url']);
            $sms_token  = $this->_webServices['sendSms']['token'];
            $sms_prefix = $this->_webServices['sendSms']['prefix'];
            // SMS Params
            $sms_params = array(
                'msisdn' => $msisdn,
                'mo' => $send_mo,
                'mt' => $mt_msg,
                'note' => 'REG ' . $package,
                'signature' => md5($msisdn . $sms_prefix . $mt_msg . $sms_prefix . $sms_token),
                'sub_code' => 'Register Contents'
            );
            if ($this->check_exists_today === true && $ready === false)
            {
                $this->CI->load->model('Vina_Services/db_sms_history_model');
                $check_exists = $this->CI->db_sms_history_model->check_daily_content($msisdn, $send_mo, $this->int_date);
            }
            else
            {
                $check_exists = 0;
            }
            if (!$check_exists)
            {
                $sms_request = $this->CI->requests->sendRequest($sms_url, $sms_params);
            }

            self::_save_log('SMS URL: ' . $sms_url);
            self::_save_log('SMS Params: ', $sms_params);
            if (isset($sms_request))
            {
                self::_save_log('SMS Response: ' . $sms_request);
            }
        }
        return true;
    }
    /**
     * Save log
     * @param string $msg
     * @param array $context
     * @return bool
     */
    private function _save_log($msg = '', $context = array())
    {
        // the default date format is "Y-m-d H:i:s"
        $dateFormat = "Y-m-d H:i:s u";
        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        $output     = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        // finally, create a formatter
        $formatter  = new LineFormatter($output, $dateFormat);
        // Create a handler
        $stream     = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, true, 0777);
        $stream->setFormatter($formatter);
        // bind it to a logger object
        $logger = new Logger('sms');
        $logger->pushHandler($stream);
        $logger->info($msg, $context);
        return true;
    }
}
