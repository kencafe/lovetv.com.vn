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
class Notify_sub extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_apiServices;
    private $_configNotify;
    private $_shortcode;
    /**
     * Notify_sub constructor.
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
            'requests'
        ));
        $this->load->model(array(
//            'Vina_Services/db_subscriber_model',
//            'Vina_Services/db_transaction_model'
            'Vina_Services/db_queues_error_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->_shortcode     = config_item('service_shortcode');
        $this->_apiServices   = config_item('vinaphone_api_services');
        $this->config->load('config_vinaphone_vascloud');
        $this->_configNotify  = config_item('Notify');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['notifySub']['debug'];
        $this->logger_path = $this->mono['vascloud']['notifySub']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['notifySub']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['notifySub']['logger_name'];
    }
    /**
     * Webservice xử lý nhận MO từ SMSGW Vascloud phương thức XML
     * Phương thức: HTTP/XML
     * Được xây dựng trên chuẩn SDP Notify Reg/Unreg Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/notify_reg.html
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
            $logger->info('XMS Vascloud: ' . $xml);
        }
        // Chuyển XML về dạng mảng
        $data_xml = simplexml_load_string($xml);
        // Input Params
        $input_queueID      = trim($data_xml->COMMAND->queueID); // ID đồng bộ đăng ký/ hủy
        $input_resultCode   = trim($data_xml->COMMAND->resultCode); // Mã lỗi trả về của SDP
        $input_errorDesc    = trim($data_xml->COMMAND->errorDesc); // Thông tin lỗi trả về
        $input_startTime    = trim($data_xml->COMMAND->startTime); // THời gian bắt đầu gửi
        $input_startTimeCP  = trim($data_xml->COMMAND->startTimeCP); // Thời gian cp nhận
        $input_cpURL        = trim($data_xml->COMMAND->cpURL); // Url của Cp cung cấp
        $input_regID        = trim($data_xml->COMMAND->regID); //Tham số định danh thao tác
        $input_msisdn       = trim($data_xml->COMMAND->msisdn); //là số điện thoại thuê bao đăng ký hoặc hủy dịch vụ
        $input_regType      = trim($data_xml->COMMAND->regType); //Xác định thao tác mà SDP đồng bộ sang, 1-Thao tác đăng ký, 2- Thao tác hủy
        $input_channel      = trim($data_xml->COMMAND->channel); //Kênh đăng ký
        $input_service_id   = trim($data_xml->COMMAND->service_id); //ID dịch vụ
        $input_package_id   = trim($data_xml->COMMAND->package_id); //ID gói dịch vụ
        $input_originalprice = trim($data_xml->COMMAND->originalprice); //Giá gốc
        $input_price        = trim($data_xml->COMMAND->price); //Giá charge
        $input_commandcode  = trim($data_xml->COMMAND->commandcode); //Cú pháp đăng ký/hủy từ SMS
        $input_serviceCode  = trim($data_xml->COMMAND->serviceCode); //Mã dịch vụ
        $input_packageCode  = trim($data_xml->COMMAND->packageCode); //Mã  gói
        $input_subpackageCode = trim($data_xml->COMMAND->subpackageCode); //Mã  gói Subpackage
        $input_autoRenew    = trim($data_xml->COMMAND->autoRenew); //Tính năng tự động gia hạn gói cước
        $input_subcribeTime = trim($data_xml->COMMAND->subcribeTime); //thời gian có sub có hiệu lực
        $input_expiredTime  = trim($data_xml->COMMAND->expiredTime); //thời gian hết hạn
        $input_updateTime   = trim($data_xml->COMMAND->updateTime); //thời gian update
        $input_params      = array(
            'queueID'       => $input_queueID,
            'resultCode'    => $input_resultCode,
            'errorDesc'     => $input_errorDesc,
            'startTime'     => $input_startTime,
            'startTimeCP'   => $input_startTimeCP,
            'cpURL'         => $input_cpURL,
            'regID'         => $input_regID,
            'msisdn'        => $input_msisdn,
            'regType'       => $input_regType,
            'channel'       => $input_channel,
            'service_id'    => $input_service_id,
            'package_id'    => $input_package_id,
            'originalprice' => $input_originalprice,
            'price'         => $input_price,
            'commandcode'   => $input_commandcode,
            'serviceCode'   => $input_serviceCode,
            'packageCode'   => $input_packageCode,
            'subpackageCode' => $input_subpackageCode,
            'autoRenew'     => $input_autoRenew,
            'subcribeTime'  => $input_subcribeTime,
            'expiredTime'   => $input_expiredTime,
            'updateTime'    => $input_updateTime
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_regID === null || $input_regType === null || $input_resultCode === null || $input_commandcode === null || $input_packageCode === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            /**
             * Phân luồng đăng ký/hủy
             * 1: Đăng ký
             * 2: Hủy
             */
            if($input_regType == 1)
            {
                // Đăng ký dịch vụ
                $reg_url = private_api_url($this->_apiServices['register']['url']);
                $this->load->library('Vina_Services/libs_db_packages');
                $reg_packagename = $this->libs_db_packages->get_data_code($input_package_id);
                $reg_data = array(
                    'requestid' => ceil(microtime(true) * 1000),
                    'msisdn' => $input_msisdn,
                    'packagename' => $reg_packagename->packageId,
                    'promotion' => 0,
                    'trial' => 0,
                    'bundle' => 0,
                    'note' => 'MO_REG|'.$input_commandcode.'|'.$input_msisdn.'|'.$this->_shortcode,
                    'application' => $this->_configNotify['application'],
                    'channel' => $input_channel,
                    'username' => $this->_configNotify['username'],
                    'userip' => $this->_configNotify['userip'],
                    'commandcode'   => $input_commandcode,
                    'subcribeTime' => self::convertTime($input_subcribeTime),
                    'expiredTime'   => self::convertTime($input_expiredTime),
                    'originalprice' => $input_originalprice,
                    'price'         => $input_price
                );
                // Send request sang router register
                $reg_request = $this->requests->sendRequest($reg_url, $reg_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Send Request Register URL ' . $reg_url);
                    $logger->info('Send Request Register Params ', $reg_data);
                    $logger->info('Response from Request ' . $reg_request);
                }
                $parse_request = json_decode($reg_request);
                // Nếu không tạo được subcriber thì lưu vào queues error để cảnh báo
                if(isset($parse_request->errorid) && in_array($parse_request->errorid, array(
                        3,
                        4
                    )) == FALSE){
                    // Create queues error
                    $error_data = array(
                        'service_id' => $input_service_id,
                        'route' => 'Register',
                        'data' => json_encode($input_params),
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    // $queues_error
                    $this->db_queues_error_model->add($error_data);
                }
                // Trả response về SDP
                $response = "<ACCESSGW>
    <MODULE>SDP NOTIFIER</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
          <error_id>0</error_id>
          <error_desc>successfully</error_desc>
          <queueID>$input_queueID</queueID>
          <msisdn>$input_msisdn</msisdn>
          <service_id>$input_service_id</service_id>
          <package_id>$input_package_id</package_id>
    </COMMAND>
</ACCESSGW>";
            }
            elseif($input_regType == 2)
            {
                // Hủy dịch vụ
                $this->load->library('Vina_Services/libs_db_packages');
                $cancel_url = private_api_url($this->_apiServices['cancel']['url']);
                $cancel_token = $this->_apiServices['cancel']['token'];
                $cancel_prefix = $this->_apiServices['cancel']['prefix'];
                $cancel_packagename = $this->libs_db_packages->get_data_code($input_package_id);
                $cancel_data = array(
                    'requestid' => ceil(microtime(true) * 1000),
                    'msisdn' => $input_msisdn,
                    'packagename' => $cancel_packagename->packageId,
                    'policy' => 0,
                    'promotion' => 0,
                    'note' => 'MO_UNREG|'.$input_commandcode.'|'.$input_msisdn.'|'.$this->_shortcode,
                    'application' => $this->_configNotify['application'],
                    'channel' => $input_channel,
                    'username' => $this->_configNotify['username'],
                    'userip' => $this->_configNotify['userip'],
                    'commandcode'   => $input_commandcode
                );
                // Send request sang router cancel
                $cancel_request = $this->requests->sendRequest($cancel_url, $cancel_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('Send Request Unregister URL ' . $cancel_url);
                    $logger->info('Send Request Unregister Params ', $cancel_data);
                    $logger->info('Response from Request ' . $cancel_request);
                }
                $parse_request = json_decode($cancel_request);
                // Nếu hủy không thành công thì lưu vào bảng queues error để cảnh báo
                if($parse_request->errorid != 0){
                    // Create queues error
                    $error_data = array(
                        'service_id' => $input_service_id,
                        'route' => 'Register',
                        'data' => json_encode($input_params),
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    // $queues_error
                    $this->db_queues_error_model->add($error_data);
                }
                // Trả response về SDP
                $response = "<ACCESSGW>
    <MODULE>SDP NOTIFIER</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
          <error_id>0</error_id>
          <error_desc>successfully</error_desc>
          <queueID>$input_queueID</queueID>
          <msisdn>$input_msisdn</msisdn>
          <service_id>$input_service_id</service_id>
          <package_id>$input_package_id</package_id>
    </COMMAND>
</ACCESSGW>";
            }
            else
            {
                // Create queues error cảnh báo
                $error_data = array(
                    'service_id' => $input_service_id,
                    'route' => 'Register',
                    'data' => json_encode($input_params),
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                // $queues_error
                $this->db_queues_error_model->add($error_data);

                // Trả response về cho SDP
                $response = "<ACCESSGW>
    <MODULE>SDP NOTIFIER</MODULE>
    <MESSAGE_TYPE>RESPONSE</MESSAGE_TYPE>
    <COMMAND>
          <error_id>0</error_id>
          <error_desc>successfully</error_desc>
          <queueID>$input_queueID</queueID>
          <msisdn>$input_msisdn</msisdn>
          <service_id>$input_service_id</service_id>
          <package_id>$input_package_id</package_id>
    </COMMAND>
</ACCESSGW>";
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

    public function convertTime($stringTime = null)
    {
        $Y = substr($stringTime,0, 4);
        $m = substr($stringTime,4, 2);
        $d = substr($stringTime,6, 2);
        $H = substr($stringTime,8, 2);
        $i = substr($stringTime,10, 2);
        $s = substr($stringTime,12, 2);
        return $Y.'-'.$m.'-'.$d.' '.$H.':'.$i.':'.$s;
    }

    /**
     * Notify_sub destructor.
     */
    public function __destruct()
    {
//        $this->db_subscriber_model->close();
//        $this->db_transaction_model->close();
        log_message('debug', 'Webservice Notify Reg/Unreg Vascloud - Close DB Connection!');
    }
}
/* End of file Notify_sub.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Notify/controllers/Notify_sub.php */