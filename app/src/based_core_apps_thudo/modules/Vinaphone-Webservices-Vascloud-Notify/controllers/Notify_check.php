<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/17/2018
 * Time: 9:57 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Notify_check extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_apiServices;
    private $_configNotify;
    private $vascloud_transaction;
    /**
     * Notify_check constructor.
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
            'Vina_Services/libs_db_services',
            'Vina_Services/libs_db_packages',
            'Vina_Services/libs_db_mt_config'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_transaction_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->service_id = config_item('service_id');
        $this->config->load('config_vinaphone_vascloud');
        $this->_configNotify        = config_item('Notify');
        $this->_apiServices         = config_item('vascloud_api_services');
        $this->vascloud_transaction = config_item('vascloud_transaction');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['notifyCheck']['debug'];
        $this->logger_path = $this->mono['vascloud']['notifyCheck']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['notifyCheck']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['notifyCheck']['logger_name'];
    }
    /**
     * Webservice xử lý nhận MO từ SMSGW Vascloud phương thức XML
     * Phương thức: HTTP/XML
     * Được xây dựng trên chuẩn SDP Notify Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/notify_check.html
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
        $xml               = $this->input->raw_input_stream;
        // Chuyển XML về dạng mảng
        $data_xml          = simplexml_load_string($xml);
        // Input Params
        $input_requestid   = trim($data_xml->requestid); // ID đồng bộ đăng ký/ hủy
        $input_service_url = trim($data_xml->service_url); // Url của Cp cung cấp
        $input_msisdn      = trim($data_xml->msisdn); //là số điện thoại thuê bao kiểm tra
        $input_channel     = trim($data_xml->channel); //Kênh đăng ký
        $input_service     = trim($data_xml->service); //ID dịch vụ
        $input_package     = trim($data_xml->package); //ID gói dịch vụ
        $input_note        = trim($data_xml->note); //Ghi chú
        $input_params      = array(
            'requestid' => $input_requestid,
            'service_url' => $input_service_url,
            'msisdn' => $input_msisdn,
            'channel' => $input_channel,
            'service' => $input_service,
            'package' => $input_package,
            'note' => $input_note
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_requestid === null || $input_msisdn === null || $input_channel === null || $input_service === null || $input_package === null)
        {
            $response = "<RPLY name=\"CheckServiceInfo\">
    <requestid>$input_requestid</requestid>
    <msisdn>$input_msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>1</status>
    <error_id>1</error_id>
    <error_desc>Sai hoac thieu tham so</error_desc>
</RPLY>";
        }
        else
        {
            // Kiểm tra và lấy thông tin dịch vụ
            $services       = $this->libs_db_services->get_data($this->service_id);
            $packagename    = $this->libs_db_packages->get_data_code($input_package, $this->service_id);
            $msisdn         = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
            $mo             = $packagename->command;
            $application    = $this->_configNotify['application'];
            $username       = $this->_configNotify['username'];
            $userip         = $this->_configNotify['userip'];
            // Data check info sub
            if ($services->onePack == 1)
            {
                $data_check = array(
                    'serviceId' => $this->service_id,
                    'msisdn' => $msisdn_convert
                );
            }
            else
            {
                $data_check = array(
                    'serviceId' => $this->service_id,
                    'packageId' => $packagename->packageId,
                    'msisdn' => $msisdn_convert
                );
            }
            /**
             * Get info Sub
             * Đoạn này xử lý thêm logic đăng ký 1 gói hay nhiều gói
             */
            $info_sub = $this->db_subscriber_model->get_info_sub($data_check);
            if (!isset($info_sub) || $info_sub === null)
            {
                /**
                 * Trường hợp thuê bao đăng ký dịch vụ lần đầu
                 * Create Transaction
                 */
                $transaction_data = array(
                    'requestId' => 'CHECK_' . $input_requestid,
                    'dtId' => 1,
                    'serviceId' => $this->service_id,
                    'packageId' => $packagename->packageId,
                    'moCommand' => $mo,
                    'msisdn' => $input_msisdn,
                    'eventName' => $this->vascloud_transaction['eventName']['notify_check'],
                    'status' => $this->vascloud_transaction['status']['notify_check_ok'],
                    'price' => $packagename->price,
                    'amount' => 0,
                    'mo' => $mo,
                    'application' => $application,
                    'channel' => $input_channel,
                    'username' => $username,
                    'userip' => $userip,
                    'promotion' => 0,
                    'note' => $input_note,
                    'type' => 2,
                    'extendType' => 1,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                $transaction_id   = $this->db_transaction_model->add($transaction_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Logger Transaction <----|');
                    $logger->info('Create Transaction Data ', $transaction_data);
                    $logger->info('Create Transaction ID: ' . $transaction_id);
                }
                // Trả Reponse
                $response = "<RPLY name=\"CheckServiceInfo\">
    <requestid>$input_requestid</requestid>
    <msisdn>$msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>1</status>
    <error_id>0</error_id>
    <error_desc>Success</error_desc>
</RPLY>";

            }
            else
            {
                if ($info_sub->status == 1)
                {
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => 'CHECK_' . $input_requestid,
                        'dtId' => 1,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename->packageId,
                        'moCommand' => $mo,
                        'msisdn' => $msisdn,
                        'eventName' => $this->vascloud_transaction['eventName']['notify_check'],
                        'status' => $this->vascloud_transaction['status']['notify_check_fail'],
                        'price' => $packagename->price,
                        'amount' => 0,
                        'mo' => $mo,
                        'application' => $application,
                        'channel' => $input_channel,
                        'username' => $username,
                        'userip' => $userip,
                        'promotion' => 0,
                        'note' => $input_note,
                        'type' => 2,
                        'extendType' => 2,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $transaction_id   = $this->db_transaction_model->add($transaction_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Logger Transaction <----|');
                        $logger->info('Create Transaction Data ', $transaction_data);
                        $logger->info('Create Transaction ID: ' . $transaction_id);
                    }
                    // Trả Reponse
                    $response = "<RPLY name=\"CheckServiceInfo\">
    <requestid>$input_requestid</requestid>
    <msisdn>$msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>0</status>
    <error_id>0</error_id>
    <error_desc>Success</error_desc>
</RPLY>";
                }
                else
                {
                    /**
                     * Trường hợp thuê bao đăng ký lại
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => 'CHECK_' . $input_requestid,
                        'dtId' => 1,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename->packageId,
                        'moCommand' => $mo,
                        'msisdn' => $msisdn,
                        'eventName' => $this->vascloud_transaction['eventName']['notify_check'],
                        'status' => $this->vascloud_transaction['status']['notify_check_ok'],
                        'price' => $packagename->price,
                        'amount' => 0,
                        'mo' => $mo,
                        'application' => $application,
                        'channel' => $input_channel,
                        'username' => $username,
                        'userip' => $userip,
                        'promotion' => 0,
                        'note' => $input_note,
                        'type' => 2,
                        'extendType' => 2,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $transaction_id   = $this->db_transaction_model->add($transaction_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Logger Transaction <----|');
                        $logger->info('Create Transaction Data ', $transaction_data);
                        $logger->info('Create Transaction ID: ' . $transaction_id);
                    }
                    // Trả Reponse
                    $response = "<RPLY name=\"CheckServiceInfo\">
    <requestid>$input_requestid</requestid>
    <msisdn>$msisdn</msisdn>  
    <service>$input_service</service>  
    <package>$input_package</package> 
    <status>1</status>
    <error_id>0</error_id>
    <error_desc>Success</error_desc>
</RPLY>";
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
     * Notify_check destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('debug', 'Webservice Notify Check info Vascloud - Close DB Connection!');
    }
}
/* End of file Notify_check.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Notify/controllers/Notify_check.php */
