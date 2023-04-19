<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/19/2018
 * Time: 4:22 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_cancel extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_webServices;
    private $_apiSubman;
    private $_private_token;
    private $_prefix_token;
    /**
     * Api_cancel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string'
        ));
        $this->load->library(array(
            'phone_number',
            'vinaphone_utilities',
            'Vina_Services/libs_db_mt_config'
        ));
        $this->config->load('config_vinaphone_vascloud');
        $this->_webServices   = config_item('vascloud_api_subman');
        $this->_apiSubman     = config_item('API_SUBMAN');
        $this->_private_token = $this->_webServices['cancel']['token'];
        $this->_prefix_token  = $this->_webServices['cancel']['prefix'];
        $this->config->load('config_vinaphone_services');
        $this->service = config_item('service_id');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['submanCancel']['debug'];
        $this->logger_path = $this->mono['vascloud']['submanCancel']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['submanCancel']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['submanCancel']['logger_name'];
    }
    /**
     * Webservice xử lý gửi Api Subman Cancel Vascloud
     * Phương thức: HTTP/XML
     * Được xây dựng trên chuẩn SubmanApi Vascloud mới của Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/subman/cancel.html
     */
    public function index($msisdn = null, $package = null)
    {
        $getMethod = $this->input->method(true);
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $logger->info('|======== Begin Api Subman Cancel ========|');
        }

        // Input Params
        $input_msisdn      = $msisdn;
        $input_package     = $package;
        $input_send_method = $this->input->get_post('send_method', true);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'package' => $input_package
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_package === null)
        {
            // Sai hoặc thiếu tham số
            $response = array(
                'errorid' => 0,
                'errordesc' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $msisdn          = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            // Thu thập thông tin cần thiết (Nạp đạn)
            $subman_url      = $this->_apiSubman['url'];
            $subman_username = $this->_apiSubman['username'];
            $subman_userip   = $this->_apiSubman['userip'];
            $application     = $this->_apiSubman['application'];
            $channel         = $this->_apiSubman['channel'];
            $service_id      = $this->_apiSubman['service_id'];
            $this->load->library('Vina_Services/libs_db_packages');
            $packageID = $this->libs_db_packages->get_data($input_package);
            // Creat request cancel to SDP
            $requestid = date('YmdHis').ceil(microtime(true) * 1000);
            $data_xml  = "<RQST><name>unsubscribe</name><requestid>$requestid</requestid><msisdn>$msisdn</msisdn>"
                ."<service>$service_id</service><package>$packageID->packageCode</package><sub_package>-1</sub_package>"
                ."<promotion></promotion><policy>0</policy><note></note><application>$application</application>"
                ."<channel>$channel</channel><username>$subman_username</username><userip>$subman_userip</userip></RQST>";
            // Send Request SMS
            if ($input_send_method !== null)
            {
                $request_sms = "<RPLY name=\"unsubscribe\"><requestid>$requestid</requestid >"
                    ."<error>0</error><error_desc>Success</error_desc></RPLY>";
            }
            else
            {
                $request_sms = $this->vinaphone_utilities->getHTTPResponse($subman_url, $data_xml, $this->_apiSubman['timeout']);
            }
            if ($this->DEBUG === true)
            {
                $logger->info('Data from Request SDP: ' . $data_xml);
                $logger->info('Response from Request SDP: ' . $request_sms);
            }
            // Parse Request
            $error_id_request = $this->vinaphone_utilities->getValue($request_sms, "<error>", "</error>");
            if ($error_id_request == 0)
            {

                // Success
                $response = array(
                    'errorid' => 0,
                    'errordesc' => 'Success'
                );
            }
            elseif (($error_id_request == '-1'))
            {
                // Subscriber is not exist
                $response = array(
                    'errorid' => 101,
                    'errordesc' => 'Subscriber is not exist.'
                );
            }
            elseif (($error_id_request == '-3'))
            {
                // Channel không hợp lệ
                $response = array(
                    'errorid' => 103,
                    'errordesc' => 'Channel không hợp lệ.'
                );
            }
            elseif (($error_id_request == '-2'))
            {
                // Service hoặc package không hợp lệ
                $response = array(
                    'errorid' => 102,
                    'errordesc' => 'Service hoặc package không hợp lệ.'
                );
            }
            elseif (($error_id_request == '-99'))
            {
                // Lỗi exception
                $response = array(
                    'errorid' => 104,
                    'errordesc' => 'exception.'
                );
            }
            elseif (($error_id_request == '401'))
            {
                // Lỗi Permission denied
                $response = array(
                    'errorid' => 105,
                    'errordesc' => 'Permission denied.'
                );
            }
            else
            {
                // Lỗi Wrong request
                $response = array(
                    'errorid' => 106,
                    'errordesc' => 'Wrong request.'
                );
            }


            // Trả tin nhắn hủy thành công
            if($response['errorid'] == 0){
                // Load config Vascloud
                $this->config->load('config_vinaphone_vascloud');
                $this->apiVascloud = config_item('vascloud_api_services');
                $sms_url       = private_api_url($this->apiVascloud['sendSms']['url']);
                $sms_token     = $this->apiVascloud['sendSms']['token'];
                $sms_prefix    = $this->apiVascloud['sendSms']['prefix'];
                $mt = $this->libs_db_mt_config->get_data($package, 2, 0);
                $sms_mt_params = array(
                    'msisdn' => $msisdn,
                    'mo' => $package,
                    'mt' => $mt,
                    'note' => 'MO|UNREG|'.$msisdn.'|'.$package,
                    'sub_code' => 'Unreg_CSKH',
                    'signature' => md5($msisdn . $sms_prefix . $mt . $sms_prefix . $sms_token)
                );
                if ($this->DEBUG === true)
                {
                    $logger->info('Send SMS to URL: ' . $sms_url);
                    $logger->info('Send SMS with Params: ', $sms_mt_params);
                }
                $request_sms = $this->requests->sendRequest($sms_url, $sms_mt_params);
                if ($this->DEBUG === true)
                {
                    $logger->info('Send SMS Result: ' . $request_sms);
                }
            }
        }
        /**
         * Log Response
         */
        if ($this->DEBUG === true && isset($response))
        {
            if (is_array($response))
            {
                $logger->info('Response', $response);
            }
            else
            {
                $logger->info('Response ' . json_encode($response));
            }
        }
        /**
         * Response
         */
        if (isset($response) && is_array($response))
        {
            $set_content_type = 'application/json';
            $set_output       = json_encode($response);
        }
        else
        {
            $decodeResp       = json_decode($response);
            $set_content_type = ($decodeResp === null) ? 'text/plain' : 'application/json';
            $set_output       = $response;
        }
        $this->output->set_content_type($set_content_type)->set_output($set_output)->_display();
        // Exit
        exit();
    }
    /**
     * Api_cancel destructor.
     */
    public function __destruct()
    {
        log_message('debug', 'Webservices Subman Cancel tool CSKH - Close DB Connection!');
    }
}
/* End of file Api_cancel.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Subman/controllers/Api_cancel.php */
