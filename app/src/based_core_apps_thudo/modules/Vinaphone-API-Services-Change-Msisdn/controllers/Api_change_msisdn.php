<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/20/2017
 * Time: 2:53 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_change_msisdn extends MX_Controller
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
     * Api_change_msisdn constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string'
        ));
        $this->load->library('phone_number');
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_transaction_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->service_id          = config_item('service_id');
        $this->service_cf_id       = config_item('service_cf_id');
        $this->service_transaction = config_item('service_transaction');
        $this->_webServices        = config_item('vinaphone_web_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_services']['changeMsisdn']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['changeMsisdn']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['changeMsisdn']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['changeMsisdn']['logger_name'];
    }
    /**
     * API Xử lý đổi số thuê bao
     *
     * @link /api/v1/change-msisdn.html
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
            $logger->info('|======== Begin Change Msisdn  ========|');
        }
        // Get Params
        $requestid    = $this->input->get_post('requestid', true); // Mã ngẫu nhiên
        $msisdnA      = $this->input->get_post('msisdnA', true); // Số thuê bao cần chuyển
        $msisdnB      = $this->input->get_post('msisdnB', true); // Số thuê bao sẽ chuyển
        $reason       = $this->input->get_post('reason', true); // Lý do chuyển
        $application  = $this->input->get_post('application', true); // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $channel      = $this->input->get_post('channel', true); // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
        $username     = $this->input->get_post('username', true); // Tên của người dùng thao tác
        $userip       = $this->input->get_post('userip', true); // IP của người dùng thao tác
        $input_params = array(
            'requestid' => $requestid,
            'msisdnA' => $msisdnA,
            'msisdnB' => $msisdnB,
            'reason' => $reason,
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
        if ($requestid === null || $msisdnA === null || $msisdnB === null)
        {
            $response = array(
                'errorid' => 101,
                'errordesc' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $msisdnA        = $this->phone_number->phone_number_convert($msisdnA, 'new');
            $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdnA);
            $msisdnB        = $this->phone_number->phone_number_convert($msisdnB, 'new');
            // Data check info sub
            $data_check     = array(
                'serviceId' => $this->serviceId,
                'msisdn' => $msisdn_convert
            );
            // khi 2 số mới và số cũ khác nhau
            $count_sub      = $this->db_subscriber_model->check_info_subscribe('id', $data_check, true);
            if ($count_sub <= 0)
            {
                /**
                 * Create Transaction
                 */
                $transaction_data = array(
                    'requestId' => $requestid,
                    'dtId' => 1,
                    'serviceId' => $this->service_id,
                    'moCommand' => '',
                    'msisdn' => $msisdnB,
                    'eventName' => $this->service_transaction['eventName']['change'],
                    'status' => $this->service_transaction['status']['change_fail'],
                    'price' => 0,
                    'amount' => 0,
                    'mo' => '',
                    'application' => $application,
                    'channel' => $channel,
                    'username' => $username,
                    'userip' => $userip,
                    'reason' => $reason,
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
                $response = array(
                    'errorid' => 1,
                    'errordesc' => 'Thuê bao A đang không sử dụng dịch vụ.'
                );
            }
            else
            {
                /**
                 * Đổi số thuê bao
                 */
                $vas_drop = array(
                    'msisdn' => $msisdnB,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $this->db_subscriber_model->drop_services_subscribers($data_check, $vas_drop);
                /**
                 * Create Transaction
                 */
                $transaction_data = array(
                    'requestId' => $requestid,
                    'dtId' => 1,
                    'serviceId' => $this->service_id,
                    'moCommand' => '',
                    'msisdn' => $msisdnB,
                    'eventName' => $this->service_transaction['eventName']['change'],
                    'status' => $this->service_transaction['status']['change_ok'],
                    'price' => 0,
                    'amount' => 0,
                    'mo' => '',
                    'application' => $application,
                    'channel' => $channel,
                    'username' => $username,
                    'userip' => $userip,
                    'reason' => $reason,
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
                // Response
                $response = array(
                    'errorid' => 0,
                    'errordesc' => 'Thành công.'
                );
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
     * Api_change_msisdn destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('error', 'API Change Msisdn - Dong ket noi CSDL!');
    }
}
/* End of file Api_change_msisdn.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-Change-Msisdn/controllers/Api_change_msisdn.php */
