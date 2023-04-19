<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/21/2017
 * Time: 2:35 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_get_info extends MX_Controller
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
     * Api_get_info constructor.
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
            'Vina_Services/libs_db_services'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->service_id          = config_item('service_id');
        $this->service_cf_id       = config_item('service_cf_id');
        $this->service_transaction = config_item('service_transaction');
        $this->_webServices        = config_item('vinaphone_web_services');
        $this->_apiServices        = config_item('vinaphone_api_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_website']['getInfo']['debug'];
        $this->logger_path = $this->mono['vina_api_website']['getInfo']['logger_path'];
        $this->logger_file = $this->mono['vina_api_website']['getInfo']['logger_file'];
        $this->logger_name = $this->mono['vina_api_website']['getInfo']['logger_name'];
    }
    /**
     * API Xử lý kiểm tra thông tin khách hàng
     *
     * @link /api/v1/utils/users-get-info.html
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
            $logger->info('|======== Begin Get Info  ========|');
        }
        // Get Params
        $msisdn          = $this->input->get_post('msisdn', true); // Số thuê bao
        $signature       = $this->input->get_post('signature', true); // Chữ kí xác thực
        $input_params    = array(
            'msisdn' => $msisdn,
            'signature' => $signature
        );
        $prefix          = $this->_apiServices['signin']['prefix'];
        $token           = $this->_apiServices['signin']['token'];
        $value_signature = md5($msisdn . $prefix . $token);
        // echo $value_signature;
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // filters
        if ($msisdn === null || $signature === null)
        {
            $response = array(
                'Result' => 2,
                'Desc' => 'Sai hoặc thiếu tham số'
            );
        }
        elseif ($value_signature !== $signature)
        {
            $response = array(
                'Result' => 3,
                'Desc' => 'Sai chữ kí xác thực'
            );
        }
        else
        {
//            $msisdn    = $this->phone_number->format($msisdn);
            $msisdn_convert   = $this->phone_number->phone_number_old_and_new($msisdn);
            // Bổ sung check theo số điện thoại mới
            $data_check = array(
                'serviceId' => $this->service_id,
                'msisdn' => $msisdn_convert
            );
            // Kiểm tra và lấy thông tin dịch vụ
            $services  = $this->libs_db_services->get_data($this->service_id);
            // GET INFO
            $user_info = $this->db_subscriber_model->check_info_subscribe('id', $data_check);
            if ($user_info > 0)
            {
                if ($services->onePack == 1)
                {
                    // Nếu có 1 người dùng chỉ đăng ký được 1 gói
                    unset($user_info);
                    // Lấy thông tin user
                    $user_info = $this->db_subscriber_model->check_info_subscribe('id, packageId, moCommand, msisdn, password, status', $data_check, false, 1);
                    $response  = array(
                        'Result' => 0,
                        'Desc' => 'Tìm thấy thông tin User đăng kí gói Sub.',
                        'Detail' => array(
                            'packageId' => $user_info->packageId,
                            'moCommand' => $user_info->moCommand,
                            'msisdn' => $user_info->msisdn,
                            'password' => $user_info->password,
                            'status' => $user_info->status
                        )
                    );
                }
                else
                {
                    // Nếu có 1 người dùng đăng ký được nhiều gói
                    $user_info = $this->db_subscriber_model->check_info_subscribe('id, packageId, moCommand, msisdn, password, status', $data_check, false, 0, false);
                    $response  = array(
                        'Result' => 0,
                        'Desc' => 'Tìm thấy thông tin User đăng kí gói Sub.'
                    );
                    foreach ($user_info as $key => $value)
                    {
                        $response['Detail'][] = array(
                            'packageId' => $value->packageId,
                            'moCommand' => $value->moCommand,
                            'msisdn' => $value->msisdn,
                            'password' => $user_info->password,
                            'status' => $user_info->status
                        );
                    }
                }
            }
            else
            {
                $response = array(
                    'Result' => 1,
                    'Desc' => 'Không tìm thấy thông tin User.'
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
     * Api_get_info destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        log_message('error', 'API Get Info for Website - Dong ket noi CSDL!');
    }
}
/* End of file Api_get_info.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Website-Get-Info/controllers/Api_get_info.php */
