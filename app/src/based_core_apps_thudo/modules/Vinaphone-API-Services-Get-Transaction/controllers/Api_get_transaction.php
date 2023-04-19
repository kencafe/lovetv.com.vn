<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/19/2017
 * Time: 3:51 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_get_transaction extends MX_Controller
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
     * Api_get_transaction constructor.
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
        $this->DEBUG       = $this->mono['vina_api_services']['getTransaction']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['getTransaction']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['getTransaction']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['getTransaction']['logger_name'];
    }
    /**
     * API Xử lý lấy thông tin tất cả gói dịch vụ
     *
     * @link /api/v1/get-transaction.html
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
            $logger->info('|======== Begin Get All Info  ========|');
        }
        // Get Params
        $requestid    = $this->input->get_post('requestid', true); // Mã ngẫu nhiên
        $msisdn       = $this->input->get_post('msisdn', true); // Số thuê bao
        $fromdate     = $this->input->get_post('fromdate', true); // Từ ngày(yyyyMMDD)
        $todate       = $this->input->get_post('todate', true); // Đến ngày(yyyyMMDD)
        $pagesize     = $this->input->get_post('pagesize', true); // Số item trên 1 trang
        $pageindex    = $this->input->get_post('pageindex', true); // Chỉ số trang cần lấy
        $application  = $this->input->get_post('application', true); // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $channel      = $this->input->get_post('channel', true); // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
        $username     = $this->input->get_post('username', true); // Tên của người dùng thao tác
        $userip       = $this->input->get_post('userip', true); // IP của người dùng thao tác
        $input_params = array(
            'requestid' => $requestid,
            'msisdn' => $msisdn,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
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
        if ($requestid === null || $msisdn === null)
        {
            $response = array(
                'errorid' => 101,
                'errordesc' => 'Sai hoặc thiếu tham số.'
            );
        }
        else
        {
            $msisdn = $this->phone_number->phone_number_convert($msisdn, 'new');
            $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
            // Data check info sub
            $data_check     = array(
                'serviceId' => $this->serviceId,
                'msisdn' => $msisdn_convert
            );
            /**
             * Lấy thông tin giao dịch của thuê bao
             */
            $info_all_transaction  = $this->db_transaction_model->get_info_transaction($data_check, $pagesize, $pageindex, $fromdate, $todate, $application, $channel, $username, $userip);
            $count_all_transaction = $this->db_transaction_model->get_info_transaction($data_check, $pagesize, $pageindex, $fromdate, $todate, $application, $channel, $username, $userip, true);
            $total_page_number     = ceil($count_all_transaction / $pagesize);
            // Convert dữ liệu về chuẩn của nhà mạng vina
            if ($info_all_transaction !== null)
            {
                // Nếu có dữ liệu
                $response = array(
                    'errorid' => 0,
                    'errordesc' => 'Thành công.',
                    'totalpackage' => $total_page_number,
                    'data' => array()
                );
                foreach ($info_all_transaction as $item)
                {
                    $response['data'][] = array(
                        'datetime' => $item->created_at,
                        'eventname' => $item->eventName,
                        'packagename' => $item->packageId,
                        'price' => $item->price,
                        'application' => $item->application,
                        'channel' => $item->channel,
                        'username' => $item->username,
                        'userip' => $item->userip
                    );
                }
            }
            else
            {
                // Nếu dữ liệu rỗng
                $response = array(
                    'errorid' => 102,
                    'errordesc' => 'Không có thông tin giao dịch nào.'
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
     * Api_get_transaction destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('error', 'API Get Transaction - Dong ket noi CSDL!');
    }
}
/* End of file Api_get_transaction.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-Get-Transaction/controllers/Api_get_transaction.php */
