<?php
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/30/2018
 * Time: 4:07 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_charge extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_webServices;
    private $_private_token;
    private $_prefix_token;
    private $_infoCharge;
    private $_CP_name;
    private $_CP_id;
    /**
     * Api_charge constructor.
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
            'vinaphone_utilities'
        ));
        $this->load->model('Vina_Services/db_charge_log_model');
        // Load Config
        $this->config->load('config_vinaphone_vascloud');
        $this->_webServices   = config_item('vascloud_api_services');
        $this->_private_token = $this->_webServices['charging']['token'];
        $this->_prefix_token  = $this->_webServices['charging']['prefix'];
        $this->_infoCharge    = config_item('vascloud_charge');
        $this->_CP_name       = config_item('CP_name');
        $this->_CP_id         = config_item('CP_id');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['charge']['debug'];
        $this->logger_path = $this->mono['vascloud']['charge']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['charge']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['charge']['logger_name'];
    }
    /**
     * Webservice xử lý Charge với phương thức XML
     *
     * Được xây dựng trên chuẩn charge Vascloud Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/charge.html
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
            $logger->info('|======== Begin Send SMS  ========|');
        }
        // Input Params
        $input_msisdn        = $this->input->get_post('msisdn', true);
        $input_packageName   = $this->input->get_post('packageName', true);
        $input_eventName     = $this->input->get_post('eventName', true); // renew, retry, register, cancel, buy
        $input_price         = $this->input->get_post('price', true);
        $input_originalPrice = $this->input->get_post('originalPrice', true);
        $input_promotion     = $this->input->get_post('promotion', true);
        $input_channel       = $this->input->get_post('channel', true);
        $input_signature     = $this->input->get_post('signature', true);
        $input_send_method   = $this->input->get_post('send_method', true);
        $valid_signature     = md5($input_msisdn . $this->_prefix_token . $input_packageName . $this->_prefix_token . $this->_private_token);
        $input_params        = array(
            'msisdn'        => $input_msisdn,
            'packageName'   => $input_packageName,
            'eventName'     => $input_eventName,
            'price'         => $input_price,
            'originalPrice' => $input_originalPrice,
            'promotion'     => $input_promotion,
            'channel'       => $input_channel,
            'signature'     => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_packageName === null || $input_eventName === null || $input_price === null || $input_channel === null || $input_signature === null)
        {
            $response = array(
                'result' => 2,
                'desc' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($input_signature != $valid_signature)
        {
            $response = array(
                'result' => 3,
                'desc' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
//            $requestId   = date('YmdHis') . random_string('numeric', 6);
            $packageName = strtoupper($input_packageName);
//          $eventName   = strtolower($input_eventName); // Chuyển về chữ thường
            $eventName   = strtoupper($input_eventName); // Đổi lên chữ hoa
//            $msisdn      = $this->phone_number->format($input_msisdn);
            $msisdn      = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $channel     = strtoupper($input_channel);
            $sequenceNumber = date('mdHis').rand(100,999); // Theo chuẩn tài liệu vascloud: mmddhhmissSSS (SSS là random từ 100 -> 999)
            $module      = $this->_infoCharge['module'];
            $servicename = $this->_infoCharge['servicename'];
            $username    = $this->_infoCharge['username'];
            $password    = $this->_infoCharge['password'];
            $contentid   = $this->_infoCharge['contentid'];
            $charge_url  = $this->_infoCharge['url'];
            // Data XML gui len ChargeGW Vascloud
            $data_xml = "<ACCESSGW>
                <MODULE>$module</MODULE>
                <MESSAGETYPE>REQUEST</MESSAGETYPE>
                <COMMAND>
                    <CCGWRequest  servicename=\"$servicename\"  username =\"$username\" password=\"$password\">
                        <RequestType>1</RequestType>
                        <SequenceNumber>$sequenceNumber</SequenceNumber>
                        <SubId>$msisdn</SubId>
                        <Price>$input_price</Price>
                        <Reason>$eventName</Reason>
                        <ORIGINALPRICE>$input_originalPrice</ORIGINALPRICE>
                        <PROMOTION>$input_promotion</PROMOTION>
                        <NOTE></NOTE>
                        <CHANNEL>$channel</CHANNEL>
                        <Content>
                            <item contenttype=\"CONTENT\" subcontenttype=\"VI\" contentid =\"$contentid\" contentname=\"$packageName\" subcontentname=\"\" cpname=\"$this->_CP_name\" contentprice=\"$input_price\"  note=\"\" /> 
                        </Content>
                    </CCGWRequest>
                </COMMAND>
            </ACCESSGW>";
            if ($this->DEBUG === true)
            {
                $logger->info('=====> Charging to ChargeGW Vascloud <=====');
                $logger->info('Send Request POST to URL ' . $charge_url);
                $logger->info('Send Request Data '. $data_xml);
            }
            // Send Request SMS
            $request_charge = (($input_send_method !== null) && ($input_send_method == 'Msg_Log')) ? $this->_infoCharge['msg_log_response'] : $this->vinaphone_utilities->getHTTPResponse($charge_url, $data_xml, 60);
            if ($this->DEBUG === true)
            {
                $logger->info('Response from Request: ' . $request_charge);
            }
            // Parse Request
            $error_id_request = $this->vinaphone_utilities->getValue($request_charge, "<Error>", "</Error>");
            $error_desc_request = $this->vinaphone_utilities->getValue($request_charge, "<ErrorDesc>", "</ErrorDesc>");

            if($error_id_request == 0)
            {
                // Charge thành công
                $response = array(
                    'result' => 0,
                    'errorid' => 0,
                    'desc' => 'Success',
                    'eventName' => $eventName,
                    'amount' => $input_price,
                    'details' => array(
                        'getRequest' => $error_desc_request
                    )
                );
            }
            else
            {
                $response = array(
                    'result' => 1,
                    'errorid' => 1,
                    'desc' => 'Failed',
                    'eventName' => $eventName,
                    'amount' => 0,
                    'details' => array(
                        'getRequest' => $error_desc_request
                    )
                );
            }
            // Cập nhật log charge
            $log_data = array(
                'requestId' => $sequenceNumber,
                'serviceName' => $servicename,
                'packageName' => $packageName,
                'msisdn' => $msisdn,
                'price' => $input_price,
                'amount' => ($error_id_request == 0) ? $input_price : 0,
                'originalPrice' => $input_price,
                'eventName' => $eventName,
                'channel' => $channel,
                'promotion' => $input_promotion,
                'status' => $error_id_request,
                'response' => $error_desc_request,
                'day' => date('Ymd'),
                'created_at' => date('Y-m-d H:i:s'),
                'logs' => $request_charge
            );
            $log_id   = $this->db_charge_log_model->add($log_data);
            if ($this->DEBUG === true)
            {
                $logger->info('|----> Call Renew / Retry <----|');
                $logger->info('Request Charge Result ' . $error_desc_request);
                $logger->info('Log Charge Data ', $log_data);
                $logger->info('Log Charge ID ' . $log_id);
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
     * Api_charge destructor.
     */
    public function __destruct()
    {
        $this->db_charge_log_model->close();
        log_message('debug', 'Webservice Api Charging - Close DB Connection!');
    }
}
/* End of file Api_charge.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Charging/controllers/Api_charge.php */