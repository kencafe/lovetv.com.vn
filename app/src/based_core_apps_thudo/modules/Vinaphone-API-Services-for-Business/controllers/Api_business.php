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
class Api_business extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    protected $webServices;
    protected $apiServices;
    protected $serviceId;
    protected $vascloud;
    protected $apiVascloud;
    private $_private_token;
    private $_private_prefix;
    /**
     * Api_business constructor.
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
            'Vina_Services/libs_db_services',
            'Vina_Services/libs_db_mt_config'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_sms_queues_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->serviceId       = config_item('service_id');
        $this->webServices     = config_item('vinaphone_web_services');
        $this->apiServices     = config_item('vinaphone_api_services');
        $this->_private_token  = $this->apiServices['business']['token'];
        $this->_private_prefix = $this->apiServices['business']['prefix'];
        $this->vascloud        = config_item('vascloud');
        $this->config->load('config_vinaphone_vascloud');
        $this->apiVascloud = config_item('vascloud_api_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_services']['business']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['business']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['business']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['business']['logger_name'];
    }
    /**
     * API Xử lý các cú pháp Business
     *
     * @link /api/v1/business.html
     */
    public function index()
    {
        $system_error = $this->libs_db_mt_config->get_data('SYSTEM_ERROR', 5, 0);
        $syntax_error = $this->libs_db_mt_config->get_data('SYNTAX_ERROR', 6, 0);
        // create a log channel
        $formatter    = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream       = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $logger->info('|======== Begin Business  ========|');
        }
        $input_method    = $this->input->method(true);
        // Get Params
        $get_shortcode   = $this->input->get_post('shortcode', true); // Đầu số dịch vụ
        $get_msisdn      = $this->input->get_post('msisdn', true); // SĐT
        $get_message     = $this->input->get_post('mo'); // Message
        $signature       = $this->input->get_post('signature', true); // Chữ ký xác thực
        $str_to_signal   = $get_msisdn . $this->_private_prefix . $get_message . $this->_private_prefix . $get_shortcode . $this->_private_prefix . $this->_private_token;
        $valid_signature = md5($str_to_signal);
        $input_params    = array(
            'shortcode' => $get_shortcode,
            'msisdn' => $get_msisdn,
            'mo' => $get_message,
            'signature' => $signature,
            'str_to_signal' => $str_to_signal,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($input_method . ' ' . current_url(), $input_params);
        }
        // filters
        if ($get_shortcode === null || $get_msisdn === null || $get_message === null || $signature === null)
        {
            $response = array(
                'Result' => 2,
                'Desc' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($signature != $valid_signature)
        {
            $response = array(
                'Result' => 3,
                'Desc' => 'Sai chữ ký xác thực.',
                'Valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
            /**
             * Tiền xử lý các thông tin đầu vào
             */
            $shortcode      = intval($get_shortcode);
            $msisdn         = $this->phone_number->phone_number_convert($get_msisdn, 'new');
            $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
            $mo_msg         = strtoupper($get_message);
            $ex_msg         = explode(' ', $mo_msg);
            $services       = $this->libs_db_services->get_data($this->serviceId);
            // Bổ sung check theo số điện thoại mới
            $data_check     = array(
                'serviceId' => $this->serviceId,
                'msisdn' => $msisdn_convert,
                'status' => 1
            );
            /**
             * Filter và xử lý các cú pháp
             */
            if ($mo_msg == 'HD' || $mo_msg == 'TG')
            {
                // Cú pháp trợ giúp
                $mt_msg   = $this->libs_db_mt_config->get_data('HUONG_DAN', 7, 0);
                $response = array(
                    'Result' => 0,
                    'Desc' => 'Tin nhan huong dan su dung.',
                    'Data' => array(
                        'shortcode' => $shortcode,
                        'msisdn' => $msisdn,
                        'mo' => $mo_msg,
                        'mt' => $mt_msg,
                        'note' => 'MO_BUS|' . $mo_msg . '|' . $msisdn . '|' . $shortcode,
                        'sub_code' => 'HUONG_DAN'
                    )
                );
            }
            elseif ($mo_msg == 'KT')
            {
                // Cú pháp kiểm tra thông tin dịch vụ
                /**
                 * Gen Mt
                 */
                // MT khi không sử dụng dịch vụ
                $mt_if_unreg    = $this->libs_db_mt_config->get_data('KIEM_TRA', 8, 0);
                // Mt khi đang sử dụng dịch vụ
                $mt_if_register = $this->libs_db_mt_config->get_data('KIEM_TRA', 9, 0);
                if ($services->onePack == 1)
                {
                    // Chỉ sử dụng 1 gói
                    /**
                     * Kiểm tra tài khoản
                     */
                    $data_select = 'id, serviceId, packageId, msisdn';
                    $info_sub    = $this->db_subscriber_model->getInfoSubscribers($data_check, false, false, $data_select);
                    if (empty($info_sub))
                    {
                        $mt_msg     = $mt_if_unreg;
                        $mt_subcode = 'KT_UNREGISTER';
                    }
                    else
                    {
                        $mt_msg     = str_replace('[danh_sach_ma_dich_vu]', 'Goi ' . $info_sub->packageId, $mt_if_register);
                        $mt_subcode = 'KT_REGISTER';
                    }
                }
                else
                {
                    // Sử dụng nhiều gói
                    $data_select = 'id, serviceId, packageId, msisdn';
                    $info_sub    = $this->db_subscriber_model->getInfoSubscribers($data_check, true, false, $data_select);
                    if (empty($info_sub))
                    {
                        $mt_msg     = $mt_if_unreg;
                        $mt_subcode = 'KT_MULTIPACKAGE_UNREGISTER';
                    }
                    else
                    {
                        $listPack = '';
                        foreach ($info_sub as $sub)
                        {
                            $listPack .= 'Goi ' . $sub->packageId . ', ';
                        }
                        $ds_packagename = trim($listPack, ', ');
                        $mt_msg         = str_replace('[danh_sach_ma_dich_vu]', $ds_packagename, $mt_if_register);
                        $mt_subcode     = 'KT_MULTIPACKAGE_REGISTER';
                    }
                }
                // Response
                $response = array(
                    'Result' => 0,
                    'Desc' => 'Check Subscribe',
                    'Data' => array(
                        'shortcode' => $shortcode,
                        'msisdn' => $msisdn,
                        'mo' => $mo_msg,
                        'mt' => $mt_msg,
                        'note' => 'MO_BUS|' . $mo_msg . '|' . $msisdn . '|' . $shortcode,
                        'sub_code' => $mt_subcode
                    )
                );
            }
            elseif ($mo_msg == 'TC')
            {
                $tools_push_sms = config_item('thudo_tools_push_sms');
                // Cú pháp từ chối nhận tin nhắn
                $url            = $tools_push_sms['blacklist']['url'];
                $params         = array(
                    'shortcode' => $shortcode,
                    'msisdn' => $msisdn,
                    'signature' => md5($msisdn . $tools_push_sms['blacklist']['prefix'] . $shortcode . $tools_push_sms['blacklist']['prefix'] . $tools_push_sms['blacklist']['token'])
                );
                $get_request    = $this->requests->sendRequest($url, $params);
                $parse_request  = json_decode(trim($get_request));
                if (isset($parse_request->msg) && !empty($parse_request->msg))
                {
                    $mt_msg = $parse_request->msg;
                }
                {
                    $mt_msg = $this->libs_db_mt_config->get_data('TU_CHOI', 7, 0);
                }
                $response = array(
                    'Result' => 0,
                    'Desc' => 'Successfully',
                    'Data' => array(
                        'shortcode' => $shortcode,
                        'msisdn' => $msisdn,
                        'mo' => $mo_msg,
                        'mt' => $mt_msg,
                        'note' => 'MO_BUS|' . $mo_msg . '|' . $msisdn . '|' . $shortcode,
                        'sub_code' => 'TU_CHOI'
                    )
                );
                if ($this->DEBUG === true)
                {
                    $logger->info('=====> Send Request to API Blacklist Msisdn <=====');
                    $logger->info('Send Request to URL: ' . $url);
                    $logger->info('Send Request Params: ' . $params);
                    $logger->info('Response from Request: ' . $get_request);
                }
            }
            elseif ($mo_msg == 'MK')
            {
                // Cú pháp lấy mật khẩu
                $this->load->model('Vina_Services/db_subscriber_model');
                $new_password   = random_string('numeric', 6);
                $new_salt       = random_string('md5');
                /**
                 * Gen Mt
                 */
                // MT khi không sử dụng dịch vụ
                $mt_if_unreg    = $this->libs_db_mt_config->get_data('MK', 8, 0);
                // Mt khi đang sử dụng dịch vụ
                $mt_if_register = $this->libs_db_mt_config->get_data('MK', 9, 0);
                $mt_if_reg      = str_replace('[password]', $new_password, $mt_if_register);
                /**
                 * Kiểm tra tài khoản
                 */
                $data_select    = 'id, serviceId, packageId, msisdn';
                if ($this->DEBUG === true)
                {
                    $logger->info('Kiem tra xem MSISDN co su dung dich vu hay khong.');
                }
                $info_sub = $this->db_subscriber_model->getInfoSubscribers($data_check, false, false, $data_select);
                if ($info_sub === null)
                {
                    $mt_msg     = $mt_if_unreg;
                    $mt_subcode = 'RESET_PASSWORD_UNREGISTER';
                }
                else
                {
                    /**
                     * Reset Password
                     */
                    $update_password = array(
                        'password' => $new_password,
                        'salt' => $new_salt,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'logs' => 'Reset Password'
                    );
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Cap nhat lai mat khau moi.');
                    }
                    $update_sub_id = $this->db_subscriber_model->update_services_subscribers($data_check, $update_password);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Reset Password Data: ', $update_password);
                        $logger->info('Reset Password Id: ' . $update_sub_id);
                    }
                    if ($update_sub_id)
                    {
                        $mt_msg     = $mt_if_reg;
                        $mt_subcode = 'RESET_PASSWORD';
                    }
                    else
                    {
                        $mt_msg     = $system_error;
                        $mt_subcode = 'SYSTEM_ERROR';
                    }
                }
                // Response
                $response = array(
                    'Result' => 0,
                    'Desc' => 'Reset Password',
                    'Data' => array(
                        'shortcode' => $shortcode,
                        'msisdn' => $msisdn,
                        'mo' => $mo_msg,
                        'mt' => $mt_msg,
                        'note' => 'MO_BUS|' . $mo_msg . '|' . $msisdn . '|' . $shortcode,
                        'sub_code' => $mt_subcode
                    )
                );
            }
            else
            {
                // Sai cú pháp
                $response = array(
                    'Result' => 0,
                    'Desc' => 'Error Syntax',
                    'Data' => array(
                        'shortcode' => $shortcode,
                        'msisdn' => $msisdn,
                        'mo' => $mo_msg,
                        'mt' => $syntax_error,
                        'note' => 'MO_BUS|' . $mo_msg . '|' . $msisdn . '|' . $shortcode,
                        'sub_code' => 'ERROR_SYNTAX'
                    )
                );
            }
            /**
             * Push SMS To Queues
             */
            $data_sms_to_queues = array(
                'data' => json_encode($response['Data']),
                'status' => 0,
                'day' => date('Ymd'),
                'created_at' => date('Y-m-d H:i:s')
            );
            if ($this->vascloud !== true)
            {
                /**
                 * 11/11/2017: Bổ sung cơ chế gửi tin ngay
                 */
                $sms_to_queue_status = config_item('service_sms_to_queue');
                if ($sms_to_queue_status === true)
                {
                    /**
                     * Trong trường hợp quy định biến service_sms_to_queue === true
                     * sẽ đẩy tất cả MT vào 1 queue
                     * và sử dụng mô hình worker để trả MT
                     */
                    $sms_queue_id = $this->db_sms_queues_model->add($data_sms_to_queues);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|--> Logger SMS to Queues <--|');
                        $logger->info('SMS Data to Queues: ', $data_sms_to_queues);
                        $logger->info('SMS Queues ID: ' . $sms_queue_id);
                    }
                    $sms_response = array(
                        'Transaction' => array(
                            'transactionId' => $sms_queue_id
                        )
                    );
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
                    $sms_url       = private_api_url($this->webServices['sendSms']['url']);
                    $sms_token     = $this->webServices['sendSms']['token'];
                    $sms_prefix    = $this->webServices['sendSms']['prefix'];
                    $data_sms      = json_decode(trim($data_sms_to_queues['data']), true);
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
            else
            {
                /**
                 * Trả tin trực tiếp qua SMSMT Vascloud
                 */
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Send SMS to Webservice SMS Vascloud <----|');
                }
                $sms_url       = private_api_url($this->apiVascloud['sendSms']['url']);
                $sms_token     = $this->apiVascloud['sendSms']['token'];
                $sms_prefix    = $this->apiVascloud['sendSms']['prefix'];
                $data_sms      = json_decode(trim($data_sms_to_queues['data']), true);
                $sms_mt_params = array(
                    'msisdn' => $data_sms['msisdn'],
                    'mo' => $data_sms['mo'],
                    'mt' => $data_sms['mt'],
                    'note' => $data_sms['note'],
                    'sub_code' => $data_sms['sub_code'],
                    'signature' => md5($data_sms['msisdn'] . $sms_prefix . $data_sms['mt'] . $sms_prefix . $sms_token)
                );
                //                if($this->apiVascloud['sendSms']['is_development'] == true){
                //                    $sms_mt_params['send_method'] = 'Msg_Log';
                //                }
                if ($this->DEBUG === true)
                {
                    $logger->info('Send SMS to URL: ' . $sms_url);
                    $logger->info('Send SMS with Params: ', $sms_mt_params);
                }
                $request_sms = $this->requests->sendRequest($sms_url, $sms_mt_params);
                //                var_dump($request_sms);die;
                if ($this->DEBUG === true)
                {
                    $logger->info('Send SMS Result: ' . $request_sms);
                }
            }
        }
        /**
         * Fix Response
         */
        if (isset($sms_response))
        {
            $fixed_response = array_merge($response, $sms_response);
        }
        else
        {
            $fixed_response = $response;
        }
        /**
         * Log Response
         */
        if ($this->DEBUG === true && isset($fixed_response))
        {
            if (is_array($fixed_response))
            {
                $logger->info('Response', $fixed_response);
            }
            else
            {
                $logger->info('Response ' . json_encode($fixed_response));
            }
        }
        /**
         * Response
         */
        if (isset($fixed_response) && is_array($fixed_response))
        {
            $set_content_type = 'application/json';
            $set_output       = json_encode($fixed_response);
        }
        else
        {
            $decodeResp       = json_decode($fixed_response);
            $set_content_type = ($decodeResp === null) ? 'text/plain' : 'application/json';
            $set_output       = $fixed_response;
        }
        $this->output->set_content_type($set_content_type)->set_output($set_output)->_display();
        // Exit
        exit();
    }
    /**
     * Api_business destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_sms_queues_model->close();
        log_message('error', 'API Business - Dong ket noi CSDL!');
    }
}
/* End of file Api_business.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-for-Business/controllers/Api_business.php */
