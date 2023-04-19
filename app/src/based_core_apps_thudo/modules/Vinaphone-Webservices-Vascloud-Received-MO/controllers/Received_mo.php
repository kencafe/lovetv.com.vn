<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/15/2018
 * Time: 4:16 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Received_mo extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_apiServices;
    private $_private_token;
    private $_prefix_token;
    private $_apiVascloud;
    /**
     * Received_mo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string',
            'ip_address'
        ));
        $this->load->library(array(
            'phone_number',
            'requests',
            'Vina_Services/libs_db_commands'
        ));
        // Load config
        $this->config->load('config_vinaphone_services');
        $this->_apiServices   = config_item('vinaphone_api_services');
        $this->_private_token = $this->_apiServices['business']['token'];
        $this->_prefix_token  = $this->_apiServices['business']['prefix'];
        $this->config->load('config_vinaphone_vascloud');
        $this->_apiVascloud   = config_item('vascloud_api_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['receivedMo']['debug'];
        $this->logger_path = $this->mono['vascloud']['receivedMo']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['receivedMo']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['receivedMo']['logger_name'];
    }
    /**
     * Webservice xử lý nhận MO từ SMSGW Vascloud phương thức XML
     *
     * Được xây dựng trên chuẩn Received Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD
     *
     * @link /vascloud/v1/receivedMo.html
     */
    public function index()
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
            $logger->info('|======== Begin Received SMS  ========|');
        }
        // Nhận chuỗi xml từ Vascloud
        $xml = $this->input->raw_input_stream;
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(). $xml);
        }
        // Chuyển XML về dạng mảng
        $data_xml = simplexml_load_string($xml);
        // Input Params
        $input_msisdn      = trim($data_xml->COMMAND->source_address); // Số thuê bao
        $input_brandname   = trim($data_xml->COMMAND->mo_time); // thời gian nhận mo: 20171018204520
        $input_moid        = trim($data_xml->COMMAND->mo_id); // mã id mo
        $input_shortcode   = trim($data_xml->COMMAND->destination_address); // Đầu số dịch vụ
        $input_mo          = trim($data_xml->COMMAND->content); // nội dung mo
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'moid'  => $input_moid,
            'mo'    => $input_mo,
            'shortcode' => $input_shortcode,
            'brandname' => $input_brandname
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_moid === null || $input_msisdn === null || $input_mo === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $check_command = $this->libs_db_commands->get_data($input_mo, 'packageid');
            if($check_command !== null)
            {
                /**
                 * Trường hợp cú pháp mua gói content
                 * Gửi request sang api mua content vascloud
                 */
                $regcontent_url = private_api_url($this->_apiVascloud['regContentVascloud']['url']);
                $regcontent_Token = $this->_apiVascloud['regContentVascloud']['token'];
                $regcontent_Prefix = $this->_apiVascloud['regContentVascloud']['prefix'];
                $regcontent_data = array(
                    'shortcode' => $input_shortcode,
                    'msisdn' => $input_msisdn,
                    'mo' => $input_mo,
                    'moid' => $input_moid,
                    'signature' => md5($input_moid . $regcontent_Prefix . $input_msisdn . $regcontent_Prefix . $input_mo . $regcontent_Prefix . $input_shortcode . $regcontent_Prefix . $regcontent_Token)
                );
                $regcontent_request = $this->requests->sendRequest($regcontent_url, $regcontent_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Request Business Url '. $regcontent_url);
                    $logger->info('Request Business Data', $regcontent_data);
                    $logger->info('Request Business Reponse', $regcontent_request);
                }
                $regcontent_jsrequest = json_decode($regcontent_request);
                if($regcontent_jsrequest->errorid == 0){
                    // Trả reponse thành công
                    $response = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\">
    <MODULE>SMSGW</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
        <error_id>0</error_id>
        <error_desc>$regcontent_jsrequest->errordesc</error_desc>
    </COMMAND>
</ACCESSGW>";
                }else{
                    // Thất bại trả reponse false
                    $response = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\">
    <MODULE>SMSGW</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
        <error_id>1</error_id>
        <error_desc>$regcontent_jsrequest->errordesc</error_desc>
    </COMMAND>
</ACCESSGW>";
                }
            }
            else
            {
                /**
                 * Trường hợp cú pháp business
                 * Gửi request sang api business
                 */
                $business_url = private_api_url($this->_apiServices['business']['url']);
                $business_data = array(
                    'shortcode' => $input_shortcode,
                    'msisdn' => $input_msisdn,
                    'mo' => $input_mo,
                    'signature' => md5($input_msisdn . $this->_prefix_token . $input_mo . $this->_prefix_token . $input_shortcode . $this->_prefix_token . $this->_private_token)
                );
                $business_request = $this->requests->sendRequest($business_url, $business_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Request Business Url '. $business_url);
                    $logger->info('Request Business Data', $business_data);
                    $logger->info('Request Business Reponse'. $business_request);
                }
                $business_jsrequest = json_decode($business_request);
                if($business_jsrequest->Result == 0){
                    // Trả reponse thành công
                    $response = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\"><MODULE>SMSGW</MODULE>"
                    ."<MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>0</error_id><error_desc>$business_jsrequest->Desc</error_desc>"
                    ."</COMMAND></ACCESSGW>";
                }else{
                    // Thất bại trả reponse false
                    $response = "<ACCESSGW xmlns=\"http://ws.apache.org/ns/synapse\"><MODULE>SMSGW</MODULE>"
                    ."<MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE><COMMAND><error_id>1</error_id><error_desc>$business_jsrequest->Desc</error_desc>"
                    ."</COMMAND></ACCESSGW>";
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
     * Webservice xử lý nhận MO từ SMSGW Vascloud phuwong thức SMPP
     *
     * Được xây dựng trên chuẩn Received Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD
     *
     * @link /vascloud/v1/smpp/receivedMo.html
     */
    public function index_smpp()
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
            $logger->info('|======== Begin Received SMS  ========|');
        }
        // Input Params
        $input_moid        = $this->input->get_post('mo_id', true);
        $input_shortcode   = $this->input->get_post('source_address', true);
        $input_brandname   = $this->input->get_post('mo_time', true);
        $input_msisdn      = $this->input->get_post('msisdn', true);
        $input_mo          = $this->input->get_post('mo', true);
        $input_signature   = $this->input->get_post('signature', true);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'moid'  => $input_moid,
            'mo'    => $input_mo,
            'shortcode' => $input_shortcode,
            'brandname' => $input_brandname
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_moid === null || $input_msisdn === null || $input_mo === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $check_command = $this->libs_db_commands->get_data($input_mo, 'packageid');
            if($check_command !== null) {
                /**
                 * Trường hợp cú pháp mua gói content
                 * Gửi request sang api mua content vascloud
                 */
                $regcontent_url = private_api_url($this->_apiServices['regContentVascloud']['url']);
                $regcontent_Token = $this->_apiServices['regContentVascloud']['token'];
                $regcontent_Prefix = $this->_apiServices['regContentVascloud']['prefix'];
                $regcontent_data = array(
                    'shortcode' => $input_shortcode,
                    'msisdn' => $input_msisdn,
                    'mo' => $input_mo,
                    'moid' => $input_moid,
                    'signature' => md5($input_moid . $regcontent_Prefix . $input_msisdn . $regcontent_Prefix . $input_mo . $regcontent_Prefix . $input_shortcode . $regcontent_Prefix . $regcontent_Token)
                );
                $regcontent_request = $this->requests->sendRequest($regcontent_url, $regcontent_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Request Business Url '. $regcontent_url);
                    $logger->info('Request Business Data', $regcontent_data);
                    $logger->info('Request Business Reponse', $regcontent_request);
                }
                $regcontent_jsrequest = json_decode($regcontent_request);
                if($regcontent_jsrequest->errorid == 0)
                {
                    // Trả reponse thành công
                    $response = array(
                        'error_id' => 0,
                        'error_desc' => $regcontent_jsrequest->errordesc
                    );
                }
                else
                {
                    // Thất bại trả reponse false
                    $response = array(
                        'error_id' => 1,
                        'error_desc' => $regcontent_jsrequest->errordesc
                    );
                }
            }
            else
            {
                /**
                 * Trường hợp cú pháp business
                 * Gửi request sang api business
                 */
                $business_url = private_api_url($this->_apiServices['business']['url']);
                $business_data = array(
                    'shortcode' => $input_shortcode,
                    'msisdn' => $input_msisdn,
                    'mo' => $input_mo,
                    'signature' => md5($input_msisdn . $this->_prefix_token . $input_mo . $this->_prefix_token . $input_shortcode . $this->_prefix_token . $this->_private_token)
                );
                $business_request = $this->requests->sendRequest($business_url, $business_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Request Business Url '. $business_url);
                    $logger->info('Request Business Data', $business_data);
                    $logger->info('Request Business Reponse', $business_request);
                }
                $business_jsrequest = json_decode($business_request);
                if($business_jsrequest->Result == 0){
                    // Trả reponse thành công
                    $response = array(
                        'error_id' => 0,
                        'error_desc' => $business_jsrequest->Desc
                    );
                }else{
                    // Thất bại trả reponse false
                    $response = array(
                        'error_id' => 1,
                        'error_desc' => $business_jsrequest->Desc
                    );
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
     * Received_mo destructor.
     */
    public function __destruct()
    {
        log_message('debug', 'Webservice Received Mo Vascloud - Close DB Connection!');
    }
}
/* End of file Received_mo.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Received-MO/controllers/Received_mo.php */