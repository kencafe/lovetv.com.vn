<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/12/2017
 * Time: 9:46 AM
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
    protected $service_id;
    protected $service_cf_id;
    protected $service_transaction;
    protected $_webServices;
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
            'requests',
            'vinaphone_utilities',
            'Vina_Services/libs_db_services',
            'Vina_Services/libs_db_packages',
            'Vina_Services/libs_db_mt_config'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_transaction_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->service_id          = config_item('service_id');
        $this->service_cf_id       = config_item('service_cf_id');
        $this->service_transaction = config_item('service_transaction');
        $this->_webServices        = config_item('vinaphone_web_services');
        $this->vascloud            = config_item('vascloud');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_services']['cancel']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['cancel']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['cancel']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['cancel']['logger_name'];
    }
    /**
     * API Xử lý Hủy dịch vụ
     *
     * @link /api/v1/cancel.html
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
            $logger->info('|======== Begin Cancel  ========|');
        }
        // Get Params
        $requestid    = $this->input->get_post('requestid', true); // Mã ngẫu nhiên
        $msisdn       = $this->input->get_post('msisdn', true); // Số thuê bao
        $packagename  = $this->input->get_post('packagename', true); // Mã gói dịch vụ, Chú ý: với mã gói “ALL”, hệ thống hủy tất cả các gói đang kích hoạt
        $policy       = $this->input->get_post('policy', true); // Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle
        $promotion    = $this->input->get_post('promotion', true); // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi. 0: hủy bình thường. Nc: miễn cước N chu kỳ. Nd: miễn cước dùng N ngày. Nw: miễn cước dùng N tuần. Nm: miễn cước dùng N tháng. Sẽ có hiệu lực nếu policy = 1
        $note         = $this->input->get_post('note', true); // Chú thích thêm cho trường thông tin policy hoặc lý do hủy
        $application  = $this->input->get_post('application', true); // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $channel      = $this->input->get_post('channel', true); // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
        $username     = $this->input->get_post('username', true); // Tên của người dùng thao tác
        $userip       = $this->input->get_post('userip', true); // IP của người dùng thao tác
        $input_params = array(
            'requestid' => $requestid,
            'msisdn' => $msisdn,
            'packagename' => $packagename,
            'policy' => $policy,
            'promotion' => $promotion,
            'note' => $note,
            'application' => $application,
            'channel' => $channel,
            'username' => $username,
            'userip' => $userip
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // filters
        if ($requestid === null || $msisdn === null || $packagename === null)
        {
            $response = array(
                'errorid' => 101,
                'errordesc' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $msisdn      = $this->phone_number->phone_number_convert($msisdn, 'new');
            $msisdn_convert  = $this->phone_number->phone_number_old_and_new($msisdn);
            $packagename = strtoupper($packagename);
            $note        = $this->vinaphone_utilities->formatNote($note);
            $count_note  = $this->vinaphone_utilities->exNote($note, true);
            $is_note     = $this->vinaphone_utilities->exNote($note);
            // Kiểm tra và lấy thông tin dịch vụ
            $services    = $this->libs_db_services->get_data($this->service_id);
            // Data check info sub
            if($services->onePack == 1 || $packagename == 'ALL')
            {
                $data_check     = array(
                    'serviceId' => $this->service_id,
                    'msisdn' => $msisdn_convert,
                    'status' => 1
                );
            }
            else
            {
                $data_check     = array(
                    'serviceId' => $this->service_id,
                    'packageId' => $packagename,
                    'msisdn' => $msisdn_convert,
                    'status' => 1
                );
            }
            /**
             * Get info Sub
             * Đoạn này xử lý thêm logic đăng ký 1 gói hay nhiều gói
             */
            $info_sub = $this->db_subscriber_model->get_info_sub($data_check);
            // filter sub
            if ($info_sub === null)
            {
                // Thuê bao đang không sử dụng gói dịch vụ này
                $mt            = $this->libs_db_mt_config->get_data($packagename, 4, 0);
                $sms_to_queues = array(
                    'data' => json_encode(array(
                        'shortcode' => config_item('service_shortcode'),
                        'msisdn' => $msisdn,
                        'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                        'mt' => $mt,
                        'note' => $note,
                        'sub_code' => 'User_is_Current_UnRegister'
                    )),
                    'status' => 0,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s')
                );
                $response      = array(
                    'errorid' => 1,
                    'errordesc' => 'Thuê bao đang không sử dụng gói dịch vụ này'
                );
            }
            else
            {
                $packages    = $this->libs_db_packages->get_data($packagename, $this->service_id);
                if($this->vascloud === true)
                {
                    $mo_command  = $this->input->get_post('commandcode', true);
                    /**
                     * Call VASCloud
                     */
                    $vas_request = '{"result":0,"errorid":0,"desc":"Success","eventName":"cancel"}';
                }
                else
                {
                    $mo_command  = $count_note > 1 ? strtoupper($is_note[1]) : '';
                    /**
                     * Call VAS Gateway
                     */
                    $vas_url     = private_api_url($this->_webServices['charging']['url']);
                    $vas_token   = $this->_webServices['charging']['token'];
                    $vas_prefix  = $this->_webServices['charging']['prefix'];
                    $vas_price   = 0;
                    $vas_params  = array(
                        'msisdn' => $msisdn,
                        'packageName' => $packagename,
                        'eventName' => $this->service_transaction['eventName']['cancel'],
                        'price' => $vas_price,
                        'originalPrice' => $packages->price,
                        'promotion' => $promotion,
                        'channel' => $channel,
                        'signature' => md5($msisdn . $vas_prefix . $packagename . $vas_prefix . $this->service_transaction['eventName']['cancel'] . $vas_prefix . $vas_price . $vas_prefix . $promotion . $vas_prefix . $channel . $vas_prefix . $vas_token)
                    );
                    $vas_request = $this->requests->sendRequest($vas_url, $vas_params);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Send Request VAS URL ' . $vas_url);
                        $logger->info('Send Request VAS Params ', $vas_params);
                        $logger->info('Response from Request ' . $vas_request);
                    }
                }
                // Parse Request
                $parse_request = json_decode($vas_request);
                if (isset($parse_request->result) && $parse_request->result == 0)
                {
                    // Hủy thành công
                    $is_status = 0;
                    /**
                     * Create Subscriber
                     */
                    $user_data = array(
                        'lastTimeUnSubscribe' => date('Y-m-d H:i:s'),
                        'expireTime' => null,
                        'status' => $is_status,
                        'promotion' => $promotion,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logs' => 'Đã hủy dịch vụ'
                    );
                    // Update lại thông tin khách hàng sau khi hủy thành công
                    $user_id = $this->db_subscriber_model->update_services_subscribers($data_check, $user_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Logger Subscriber <----|');
                        $logger->info('Update Subscriber Data ', $user_data);
                        $logger->info('Update Subscriber ROWS: ' . $user_id);
                    }
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => $requestid,
                        'dtId' => 1,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename,
                        'moCommand' => $mo_command,
                        'msisdn' => $msisdn,
                        'eventName' => $this->service_transaction['eventName']['cancel'],
                        'status' => $this->service_transaction['status']['unregister_ok'],
                        'price' => 0,
                        'amount' => 0,
                        'mo' => $mo_command,
                        'application' => $application,
                        'channel' => $channel,
                        'username' => $username,
                        'userip' => $userip,
                        'promotion' => $promotion,
                        'note' => $note,
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
                    /**
                     * Create Queues Transaction
                     */
                    $transaction_to_queues = array(
                        'service_id' => $this->service_cf_id,
                        'route' => 'moCancel',
                        'data' => json_encode(array(
                            'phone' => $msisdn,
                            'package' => $packagename,
                            'event' => $this->service_transaction['eventName']['cancel'],
                            'message' => $mo_command,
                            'note' => $note,
                            'type' => 1, // 1 = SMS, 2 = WAP, 3 = Quá hạn Retry, 4 = CSKH hủy
                            'application' => $application, // Theo tài liệu Vinaphone
                            'channel' => $channel, // Theo tài liệu Vinaphone,
                            'status' => 0, // 0 = Thành công, 1 = Thất bại
                            'status_charge' => 1 // 0 = Có phát sinh charge cước, 1 = Không phát sinh charge cước
                            // 'price' => $vas_price
                        )),
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    /**
                     * Xử lý SMS trả về
                     */
                    $mt                    = $this->libs_db_mt_config->get_data($packagename, 2, 0);
                    $sms_to_queues         = array(
                        'data' => json_encode(array(
                            'shortcode' => config_item('service_shortcode'),
                            'msisdn' => $msisdn,
                            'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                            'mt' => $mt,
                            'note' => $note,
                            'sub_code' => 'Cancel_is_Success'
                        )),
                        'status' => 0,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    // Response
                    $response              = array(
                        'errorid' => 0,
                        'errordesc' => 'Hủy thành công'
                    );
                }
                else
                {
                    // Hủy không thành công
                    $response = array(
                        'errorid' => 102,
                        'errordesc' => 'Hủy không thành công'
                    );
                }
            }
            if($this->vascloud === false)
            {
                /**
                 * Push data transaction to Queues
                 */
                if (isset($transaction_to_queues))
                {
                    $this->load->model('Vina_Services/db_queues_model');
                    $trans_queue_id = $this->db_queues_model->add($transaction_to_queues);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Logger Transaction to Queues <----|');
                        $logger->info('Create Transaction Queues Data ', $transaction_to_queues);
                        $logger->info('Create Transaction Queues ID: ' . $trans_queue_id);
                    }
                    $this->db_queues_model->close();
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Dong ket noi den DB Queues!');
                    }
                }
                /**
                 * Push SMS to Queues
                 */
                if (isset($sms_to_queues))
                {
                    /**
                     * 08/11/2017: Bổ sung cơ chế gửi tin ngay
                     */
                    $sms_to_queue_status = config_item('service_sms_to_queue');
                    if ($sms_to_queue_status === true)
                    {
                        /**
                         * Trong trường hợp quy định biến service_sms_to_queue === true
                         * sẽ đẩy tất cả MT vào 1 queue
                         * và sử dụng mô hình worker để trả MT
                         */
                        $this->load->model('Vina_Services/db_sms_queues_model');
                        $sms_queue_id = $this->db_sms_queues_model->add($sms_to_queues);
                        if ($this->DEBUG === true)
                        {
                            $logger->info('|----> Logger SMS to Queues <----|');
                            $logger->info('Create SMS Queues Data ', $sms_to_queues);
                            $logger->info('Create SMS Queues ID: ' . $sms_queue_id);
                        }
                        $this->db_sms_queues_model->close();
                        if ($this->DEBUG === true)
                        {
                            $logger->info('Dong ket noi den DB Queues SMS!');
                        }
                    }
                    else
                    {
                        /**
                         * Trả tin trực tiếp qua Webservice
                         */
                        if ($this->DEBUG === true)
                        {
                            $logger->info('|----> Send SMS to Webservice SMS <----|');
                        }
                        $sms_url       = private_api_url($this->_webServices['sendSms']['url']);
                        $sms_token     = $this->_webServices['sendSms']['token'];
                        $sms_prefix    = $this->_webServices['sendSms']['prefix'];
                        $data_sms      = json_decode(trim($sms_to_queues['data']), true);
                        $sms_mt_params = array(
                            'msisdn' => $data_sms['msisdn'],
                            'mo' => $data_sms['mo'],
                            'mt' => $data_sms['mt'],
                            'note' => $data_sms['note'],
                            'sub_code' => $data_sms['sub_code'],
                            'signature' => md5($data_sms['msisdn'] . $sms_prefix . $data_sms['mt'] . $sms_prefix . $sms_token)
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
            }else{
                // Load config Vascloud
                $this->config->load('config_vinaphone_vascloud');
                $this->apiVascloud = config_item('vascloud_api_services');
                /**
                 * Trả tin trực tiếp qua Vascloud
                 */
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Send SMS to Webservice SMS <----|');
                }
                $sms_url       = private_api_url($this->apiVascloud['sendSms']['url']);
                $sms_token     = $this->apiVascloud['sendSms']['token'];
                $sms_prefix    = $this->apiVascloud['sendSms']['prefix'];
                $data_sms      = json_decode(trim($sms_to_queues['data']), true);
                $sms_mt_params = array(
                    'msisdn' => $data_sms['msisdn'],
                    'mo' => $data_sms['mo'],
                    'mt' => $data_sms['mt'],
                    'note' => $data_sms['note'],
                    'sub_code' => $data_sms['sub_code'],
                    'signature' => md5($data_sms['msisdn'] . $sms_prefix . $data_sms['mt'] . $sms_prefix . $sms_token)
                );
                if($this->apiVascloud['sendSms']['is_development'] == true){
                    $sms_mt_params['send_method'] = 'Msg_Log';
                }
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
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('error', 'API Cancel - Dong ket noi CSDL!');
    }
}
/* End of file Api_cancel.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-Cancel/controllers/Api_cancel.php */
