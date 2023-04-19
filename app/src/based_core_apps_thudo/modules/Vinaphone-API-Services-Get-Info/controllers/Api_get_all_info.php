<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/19/2017
 * Time: 3:18 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_get_all_info extends MX_Controller
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
     * Api_get_all_info constructor.
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
        $this->DEBUG       = $this->mono['vina_api_services']['getAllInfo']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['getAllInfo']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['getAllInfo']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['getAllInfo']['logger_name'];
    }
    /**
     * API Xử lý lấy thông tin tất cả gói dịch vụ
     *
     * @link /api/v1/get-info-all.html
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
        $application  = $this->input->get_post('application', true); // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $channel      = $this->input->get_post('channel', true); // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
        $username     = $this->input->get_post('username', true); // Tên của người dùng thao tác
        $userip       = $this->input->get_post('userip', true); // IP của người dùng thao tác
        $input_params = array(
            'requestid' => $requestid,
            'msisdn' => $msisdn,
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
            $msisdn        = $this->phone_number->format($msisdn);
            $msisdn_old    = $this->phone_number->convert_phone($msisdn, 'old');
            // Data check info sub
            if($msisdn != $msisdn_old)
            {
                $dataCheck = array(
                    'serviceId' => $this->service_id,
                    'packageId' => null,
                    'msisdn' => array($msisdn, $msisdn_old),
                );
            }
            else
            {
                $dataCheck = array(
                    'serviceId' => $this->service_id,
                    'packageId' => null,
                    'msisdn' => $msisdn,
                );
            }
            /**
             * Lấy thông tin tất cả các gói dịch vụ
             */
            $info_all_sub  = $this->db_subscriber_model->check_info_subscribe('packageId,status,lastTimeSubscribe,lastTimeUnSubscribe,lastTimeRenew,lastTimeRetry,expireTime', $dataCheck, false, 0);
            $count_all_sub = $this->db_subscriber_model->check_info_subscribe('id', $dataCheck, true);
            // Convert dữ liệu về chuẩn của nhà mạng vina
            if ($info_all_sub !== null)
            {
                // Nếu có dữ liệu
                $response = array(
                    'errorid' => 0,
                    'errordesc' => 'Thành công.',
                    'totalpackage' => $count_all_sub,
                    'data' => array()
                );
                foreach ($info_all_sub as $item)
                {
                    $response['data'][] = array(
                        'packagename' => $item->packageId,
                        'status' => $item->status,
                        'last_time_subscribe' => ($item->lastTimeSubscribe !== "0000-00-00 00:00:00" && $item->lastTimeSubscribe !== null) ? date_format(date_create($item->lastTimeSubscribe), 'YmdHis') : null,
                        'last_time_unsubscribe' => ($item->lastTimeUnSubscribe !== "0000-00-00 00:00:00" && $item->lastTimeUnSubscribe !== null) ? date_format(date_create($item->lastTimeUnSubscribe), 'YmdHis') : null,
                        'last_time_renew' => ($item->lastTimeRenew !== "0000-00-00 00:00:00" && $item->lastTimeRenew !== null) ? date_format(date_create($item->lastTimeRenew), 'YmdHis') : null,
                        'last_time_retry' => ($item->lastTimeRetry !== "0000-00-00 00:00:00" && $item->lastTimeRetry !== null) ? date_format(date_create($item->lastTimeRetry), 'YmdHis') : null,
                        'expire_time' => ($item->expireTime !== "0000-00-00 00:00:00" && $item->expireTime !== null) ? date_format(date_create($item->expireTime), 'YmdHis') : null
                    );
                }
                //                var_dump($info_all_sub);die();
            }
            else
            {
                // Nếu dữ liệu rỗng
                $response = array(
                    'errorid' => 102,
                    'errordesc' => 'Không có thông tin nào.'
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
     * Api_get_all_info destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('error', 'API Get All Info - Dong ket noi CSDL!');
    }
}
/* End of file Api_get_all_info.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-Get-Info/controllers/Api_get_all_info.php */
