<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 10/11/2017
 * Time: 2:22 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Vinaphone_xmlgw
{
    protected $CI;
    protected $mono;
    protected $DEBUG;
    protected $logger_path;
    protected $logger_file;
    protected $view360;
    protected $vasprov_url;
    protected $timeout;
    protected $application;
    protected $service;
    protected $channel;
    protected $username;
    protected $userip;
    /**
     * Vinaphone_xmlgw constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->DEBUG = false;
        $this->CI->load->helper(array(
            'url',
            'html',
            'string'
        ));
        $this->CI->load->library('vinaphone_utilities');
        $this->CI->config->load('config_vinaphone_vasprov');
        $this->view360     = config_item('Vina_VIEW360');
        $this->vasprov_url = config_item('Vas_Provisioning_URL');
        $this->timeout     = $this->view360['timeout'];
        $this->application = config_item('Vina_CpName'); //VNP cung cấp
        $this->service     = config_item('Vina_ServiceName'); //VNP cung cấp
        $this->channel     = $this->view360['channel']; // VNP cung cấp
        $this->username    = $this->view360['username']; // Chính là account đăng nhập VIEW360
        $this->userip      = $this->view360['userip']; // Chính là ip của account đăng nhập VIEW360
        // Setup Log
        $this->logger_path = APPPATH . 'logs-data/Modules/Vinaphone-XmlGW/';
        $this->logger_file = 'Log-' . date('Y-m-d') . '.log';
        $this->mono        = array(
            // the default date format is "Y-m-d H:i:s"
            'dateFormat' => "Y-m-d H:i:s u",
            // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
            'outputFormat' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'monoBubble' => true,
            'monoFilePermission' => 0777
        );
    }
    /**
     * subscribe
     *
     * @access      public
     * @author 		Hung Nguyen <dev@nguyenanhung.com>
     * @version     1.0.2
     * @since       11/10/2017
     */
    public function subscribe($msisdn, $package)
    {
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . 'Subscribe/' . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger('subscribe');
        $logger->pushHandler($stream);
        $logger->info('||=========== Logger Subscribe ===========||');
        //echo self::$channel; die;
        $requestid = random_string('numeric', 10);
        //1. Đăng ký dịch vụ
        $subscribe = "<RQST>" . "<name>subscribe</name>" . "<requestid>" . $requestid . "</requestid>" . //id ngẫu nhiên
            "<msisdn>" . $msisdn . "</msisdn>" . //số thuê bao
            "<service>" . $this->service . "</service>" . "<package>" . $package . "</package>" . "<promotion>0</promotion>" . "<trial>0</trial>" . "<bundle>0</bundle>" . "<note>note</note>" . //nếu có
            "<application>" . $this->application . "</application>" . "<channel>" . $this->channel . "</channel>" . "<username>" . $this->username . "</username>" . "<userip>" . $this->userip . "</userip>" . "</RQST>";
        //echo "<pre>". htmlspecialchars($subscribe) . "</pre>";
        if ($this->DEBUG === true)
        {
            $logger->info('|===== Create Request Subscribe =====|');
            $logger->info('Request: ' . $subscribe);
        }
        $subscribe_response = $this->CI->vinaphone_utilities->getHTTPResponse($this->vasprov_url, $subscribe, $this->timeout);
        // Save Logs
        if ($this->DEBUG === true)
        {
            $logger->info('Response: ' . $subscribe_response);
        }
        // echo "<pre>" . htmlspecialchars($subscribe_response) . "</pre>";
        // die;
        /*<?xml version="1.0" encoding="UTF-8"?><RPLY name="subscribe"><requestid>20150225210505</requestid><error>3</error><error_desc>Dang ky thanh cong va khong bi tru cuoc</error_desc><extra_information>3</extra_information></RPLY>*/
        $subscribe_error = $this->CI->vinaphone_utilities->getValue($subscribe_response, "<error>", "</error>");
        if ($this->DEBUG === true)
        {
            $logger->info('ErrorID: ' . $subscribe_error);
        }
        //echo "subscribe return: " . $subscribe_error . "\r\n";
        return $subscribe_error;
    }
    /**
     * unsubscribe
     *
     * @access      public
     * @author 		Hung Nguyen <dev@nguyenanhung.com>
     * @version     1.0.2
     * @since       11/10/2017
     */
    public function unsubscribe($msisdn, $package)
    {
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . 'Unsubscribe/' . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger('unsubscribe');
        $logger->pushHandler($stream);
        $logger->info('||=========== Logger Unsubscribe ===========||');
        //2. Hủy đăng ký dịch vụ
        $requestid   = random_string('numeric', 10);
        $unsubscribe = "<RQST>" . "<name>unsubscribe</name>" . "<requestid>" . $requestid . "</requestid>" . //id ngẫu nhiên
            "<msisdn>" . $msisdn . "</msisdn>" . //số thuê bao
            "<service>" . $this->service . "</service>" . "<package>" . $package . "</package>" . "<policy>0</policy>" . "<promotion>0</promotion>" . "<note>note</note>" . //nếu có
            "<application>" . $this->application . "</application>" . "<channel>" . $this->channel . "</channel>" . "<username>" . $this->username . "</username>" . "<userip>" . $this->userip . "</userip>" . "</RQST>";
        //echo "<pre>". htmlspecialchars($unsubscribe) . "</pre>";
        if ($this->DEBUG === true)
        {
            $logger->info('|===== Create Request Unsubscribe =====|');
            $logger->info('Request: ' . $unsubscribe);
        }
        $unsubscribe_response = $this->CI->vinaphone_utilities->getHTTPResponse($this->vasprov_url, $unsubscribe, $this->timeout);
        // Save Logs
        if ($this->DEBUG === true)
        {
            $logger->info('Response: ' . $unsubscribe_response);
        }
        //echo "<pre>". htmlspecialchars($unsubscribe_response) . "</pre>";
        /*<?xml version="1.0" encoding="UTF-8"?><RPLY name="unsubscribe"><requestid>20150225210505</requestid><error>0</error><error_desc>Success</error_desc><extra_information>0</extra_information></RPLY>*/
        $unsubscribe_error = $this->CI->vinaphone_utilities->getValue($unsubscribe_response, "<error>", "</error>");
        if ($this->DEBUG === true)
        {
            $logger->info('ErrorID: ' . $unsubscribe_error);
        }
        //echo "unsubscribe return: " . $unsubscribe_error . "\r\n";
        return $unsubscribe_error;
    }
}
/* End of file Vinaphone_xmlgw.php */
/* Location: ./based_core_apps_thudo/libraries/Vinaphone_xmlgw.php */
