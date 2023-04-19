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
class Send_sms extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_webServices;
    private $_sms_gateway;
    private $_private_token;
    private $_prefix_token;
    /**
     * Send_sms constructor.
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
            'requests'
        ));
        $this->load->model('Vina_Services/db_sms_history_model');
        $this->config->load('config_vinaphone_services');
        $this->config->load('config_send_sms');
        $this->_sms_gateway   = config_item('sms_gateway');
        $this->_webServices   = config_item('vinaphone_web_services');
        $this->_private_token = $this->_webServices['sendSms']['token'];
        $this->_prefix_token  = $this->_webServices['sendSms']['prefix'];
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_web_services']['sendSms']['debug'];
        $this->logger_path = $this->mono['vina_web_services']['sendSms']['logger_path'];
        $this->logger_file = $this->mono['vina_web_services']['sendSms']['logger_file'];
        $this->logger_name = $this->mono['vina_web_services']['sendSms']['logger_name'];
    }
    /**
     * Webservice xử lý gửi SMS
     *
     * Được xây dựng trên chuẩn SMS Gateway mới của Thủ Đô
     * Chi tiết tham khảo file: Document Send SMS new SMS Gateway.pdf
     *
     * @link /web/v1/sendSms.html
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
        $input_msisdn      = $this->input->get_post('msisdn', true);
        $input_mo          = $this->input->get_post('mo', true);
        $input_mt          = $this->input->get_post('mt', true);
        $input_note        = $this->input->get_post('note', true);
        $input_signature   = $this->input->get_post('signature', true);
        $input_sub_code    = $this->input->get_post('sub_code', true);
        $input_send_method = $this->input->get_post('send_method', true);
        $valid_signature   = md5($input_msisdn . $this->_prefix_token . $input_mt . $this->_prefix_token . $this->_private_token);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'mo' => $input_mo,
            'mt' => $input_mt,
            'note' => $input_note,
            'sub_code' => $input_sub_code,
            'send_method' => $input_send_method,
            'signature' => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_mt === null || $input_signature === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($input_signature != $valid_signature)
        {
            $response = array(
                'ec' => 3,
                'msg' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
//            $msisdn        = $this->phone_number->format($input_msisdn);
            $msisdn        = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $mt            = trim($input_mt);
            $mo            = ($input_mo !== null) ? trim($input_mo) : '';
            $note          = ($input_note !== null) ? trim($input_note) : '';
            $sub_code      = ($input_sub_code !== null) ? trim($input_sub_code) : '';
            /**
             * Tiến hành forward SMS tới SMS Gateway
             */
            $sms_method    = $this->_sms_gateway['method'];
            $sms_url       = $this->_sms_gateway['url'];
            $sms_shortcode = $this->_sms_gateway['shortcode'];
            $sms_params    = array(
                'service' => $sms_shortcode,
                'msisdn' => $msisdn,
                'msg' => $mt
            );
            if ($this->DEBUG === true)
            {
                $logger->info('=====> Send SMS to Gateway <=====');
                if ($sms_method === 'GET')
                {
                    $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url . '?' . http_build_query($sms_params));
                }
                else
                {
                    $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url);
                    $logger->info('Send Request Data ', $sms_params);
                }
            }
            // Send Request SMS
            $request_sms = (($input_send_method !== null) && ($input_send_method == 'Msg_Log')) ? $this->_sms_gateway['msg_log_response'] : $this->requests->sendRequest($sms_url, $sms_params, $sms_method);
            if ($this->DEBUG === true)
            {
                $logger->info('Response from Request: ' . $request_sms);
            }
            // Parse Request
            $parse_request = json_decode($request_sms);
            if (isset($parse_request->ec))
            {
                if ($parse_request->ec == 0)
                {
                    // Gửi SMS Thành công
                    $response = array(
                        'ec' => 0,
                        'msg' => 'Success.',
                        'details' => 'Gui tin nhan thanh cong.',
                        'data' => array(
                            'msisdn' => $msisdn,
                            'mo' => $mo,
                            'mt' => $mt,
                            'note' => $note
                        )
                    );
                }
                else
                {
                    // Gửi SMS thất bại
                    $response = array(
                        'ec' => 1,
                        'msg' => 'Failed.',
                        'details' => 'Gui tin nhan khong thanh cong.',
                        'res' => $request_sms,
                        'data' => array(
                            'msisdn' => $msisdn,
                            'mo' => $mo,
                            'mt' => $mt,
                            'note' => $note
                        )
                    );
                }
            }
            else
            {
                $response = array(
                    'ec' => 1,
                    'msg' => 'Parse Request is Error',
                    'res' => $request_sms,
                    'data' => array(
                        'msisdn' => $msisdn,
                        'mo' => $mo,
                        'mt' => $mt,
                        'note' => $note
                    )
                );
            }
            /**
             * Tiến hành xử lý lưu Log SMS
             */
            $create_log = array(
                'shortcode' => $sms_shortcode,
                'msisdn' => $msisdn,
                'mo' => $mo,
                'mt' => $mt,
                'note' => $note,
                'status' => (isset($response['ec'])) ? $response['ec'] : 2,
                'day' => date('Ymd'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'sub_code' => $sub_code,
                'response' => json_encode(array(
                    'request_sms' => $request_sms,
                    'send_method' => $input_send_method
                ))
            );
            $log_id     = $this->db_sms_history_model->add($create_log);
            if ($this->DEBUG === true)
            {
                $logger->info('|----> Save log Send SMS to DB <----');
                $logger->info('Create Log Data: ', $create_log);
                $logger->info('Result Log ID: ' . $log_id);
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
     * Webservice xử lý gửi SMS hàng ngày
     *
     * Được xây dựng trên chuẩn SMS Gateway mới của Thủ Đô
     * Chi tiết tham khảo file: Document Send SMS new SMS Gateway.pdf
     * Check MO to day
     *
     * @link /web/v1/sendDailySms.html
     */
    public function daily_sms()
    {
        $getMethod = $this->input->method(true);
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . 'daily-SMS' . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $logger->info('|======== Begin Send SMS  ========|');
        }
        // Input Params
        $input_msisdn      = $this->input->get_post('msisdn', true);
        $input_mo          = $this->input->get_post('mo', true);
        $input_mt          = $this->input->get_post('mt', true);
        $input_note        = $this->input->get_post('note', true);
        $input_signature   = $this->input->get_post('signature', true);
        $input_sub_code    = $this->input->get_post('sub_code', true);
        $input_send_method = $this->input->get_post('send_method', true);
        $valid_signature   = md5($input_msisdn . $this->_prefix_token . $input_mt . $this->_prefix_token . $this->_private_token);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'mo' => $input_mo,
            'mt' => $input_mt,
            'note' => $input_note,
            'sub_code' => $input_sub_code,
            'send_method' => $input_send_method,
            'signature' => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_mt === null || $input_signature === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($input_signature != $valid_signature)
        {
            $response = array(
                'ec' => 3,
                'msg' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
            $msisdn          = $this->phone_number->format($input_msisdn);
            $mt              = trim($input_mt);
            $mo              = ($input_mo !== null) ? trim($input_mo) : '';
            $note            = ($input_note !== null) ? trim($input_note) : '';
            $sub_code        = ($input_sub_code !== null) ? trim($input_sub_code) : '';
            /**
             * filter SMS
             */
            $check_msg_today = $this->db_sms_history_model->check_daily_content($msisdn, $mo, date('Ymd'));
            if ($check_msg_today)
            {
                $response = array(
                    'ec' => 100,
                    'msg' => 'Đã tồn tại bản tin trong ngày'
                );
            }
            else
            {
                /**
                 * Tiến hành forward SMS tới SMS Gateway
                 */
                $sms_method    = $this->_sms_gateway['method'];
                $sms_url       = $this->_sms_gateway['url'];
                $sms_shortcode = $this->_sms_gateway['shortcode'];
                $sms_params    = array(
                    'service' => $sms_shortcode,
                    'msisdn' => $msisdn,
                    'msg' => $mt
                );
                if ($this->DEBUG === true)
                {
                    $logger->info('=====> Send SMS to Gateway <=====');
                    if ($sms_method === 'GET')
                    {
                        $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url . '?' . http_build_query($sms_params));
                    }
                    else
                    {
                        $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url);
                        $logger->info('Send Request Data ', $sms_params);
                    }
                }
                // Send Request SMS
                $request_sms = (($input_send_method !== null) && ($input_send_method == 'Msg_Log')) ? $this->_sms_gateway['msg_log_response'] : $this->requests->sendRequest($sms_url, $sms_params, $sms_method);
                if ($this->DEBUG === true)
                {
                    $logger->info('Response from Request: ' . $request_sms);
                }
                // Parse Request
                $parse_request = json_decode($request_sms);
                if (isset($parse_request->ec))
                {
                    if ($parse_request->ec == 0)
                    {
                        // Gửi SMS Thành công
                        $response = array(
                            'ec' => 0,
                            'msg' => 'Success.',
                            'details' => 'Gui tin nhan thanh cong.',
                            'data' => array(
                                'msisdn' => $msisdn,
                                'mo' => $mo,
                                'mt' => $mt,
                                'note' => $note
                            )
                        );
                    }
                    else
                    {
                        // Gửi SMS thất bại
                        $response = array(
                            'ec' => 1,
                            'msg' => 'Failed.',
                            'details' => 'Gui tin nhan khong thanh cong.',
                            'res' => $request_sms,
                            'data' => array(
                                'msisdn' => $msisdn,
                                'mo' => $mo,
                                'mt' => $mt,
                                'note' => $note
                            )
                        );
                    }
                }
                else
                {
                    $response = array(
                        'ec' => 1,
                        'msg' => 'Parse Request is Error',
                        'res' => $request_sms,
                        'data' => array(
                            'msisdn' => $msisdn,
                            'mo' => $mo,
                            'mt' => $mt,
                            'note' => $note
                        )
                    );
                }
                /**
                 * Tiến hành xử lý lưu Log SMS
                 */
                $create_log = array(
                    'shortcode' => $sms_shortcode,
                    'msisdn' => $msisdn,
                    'mo' => $mo,
                    'mt' => $mt,
                    'note' => $note,
                    'status' => (isset($response['ec'])) ? $response['ec'] : 2,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'sub_code' => $sub_code,
                    'response' => json_encode(array(
                        'request_sms' => $request_sms,
                        'send_method' => $input_send_method
                    ))
                );
                $log_id     = $this->db_sms_history_model->add($create_log);
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Save log Send SMS to DB <----');
                    $logger->info('Create Log Data: ', $create_log);
                    $logger->info('Result Log ID: ' . $log_id);
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
     * Webservice xử lý forward SMS
     *
     * Được xây dựng trên chuẩn SMS Gateway mới của Thủ Đô
     * Chi tiết tham khảo file: Document Send SMS new SMS Gateway.pdf
     * API chỉ thực hiện forward SMS, không lưu log
     *
     * @link /web/v1/forwardSms.html
     */
    public function forward_sms()
    {
        $getMethod = $this->input->method(true);
        // create a log channel
        $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
        $stream    = new StreamHandler($this->logger_path . 'forwardSms/' . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
        $stream->setFormatter($formatter);
        $logger = new Logger($this->logger_name);
        $logger->pushHandler($stream);
        if ($this->DEBUG === true)
        {
            $logger->info('|======== Begin Send SMS  ========|');
        }
        // Input Params
        $input_msisdn      = $this->input->get_post('msisdn', true);
        $input_mo          = $this->input->get_post('mo', true);
        $input_mt          = $this->input->get_post('mt', true);
        $input_note        = $this->input->get_post('note', true);
        $input_signature   = $this->input->get_post('signature', true);
        $input_sub_code    = $this->input->get_post('sub_code', true);
        $input_send_method = $this->input->get_post('send_method', true);
        $valid_signature   = md5($input_msisdn . $this->_prefix_token . $input_mt . $this->_prefix_token . $this->_private_token);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'mo' => $input_mo,
            'mt' => $input_mt,
            'note' => $input_note,
            'sub_code' => $input_sub_code,
            'send_method' => $input_send_method,
            'signature' => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_mt === null || $input_signature === null)
        {
            $response = array(
                'ec' => 2,
                'msg' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($input_signature != $valid_signature)
        {
            $response = array(
                'ec' => 3,
                'msg' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
            $msisdn        = $this->phone_number->format($input_msisdn);
            $mt            = trim($input_mt);
            $mo            = ($input_mo !== null) ? trim($input_mo) : '';
            $note          = ($input_note !== null) ? trim($input_note) : '';
            $sub_code      = ($input_sub_code !== null) ? trim($input_sub_code) : '';
            /**
             * Tiến hành forward SMS tới SMS Gateway
             */
            $sms_method    = $this->_sms_gateway['method'];
            $sms_url       = $this->_sms_gateway['url'];
            $sms_shortcode = $this->_sms_gateway['shortcode'];
            $sms_params    = array(
                'service' => $sms_shortcode,
                'msisdn' => $msisdn,
                'msg' => $mt
            );
            if ($this->DEBUG === true)
            {
                $logger->info('=====> Send SMS to Gateway <=====');
                if ($sms_method === 'GET')
                {
                    $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url . '?' . http_build_query($sms_params));
                }
                else
                {
                    $logger->info('Send Request ' . $sms_method . ' to URL ' . $sms_url);
                    $logger->info('Send Request Data ', $sms_params);
                }
            }
            // Send Request SMS
            $request_sms = $this->requests->sendRequest($sms_url, $sms_params, $sms_method);
            if ($this->DEBUG === true)
            {
                $logger->info('Response from Request: ' . $request_sms);
            }
            // Parse Request
            $parse_request = json_decode($request_sms);
            if (isset($parse_request->ec))
            {
                if ($parse_request->ec == 0)
                {
                    // Gửi SMS Thành công
                    $response = array(
                        'ec' => 0,
                        'msg' => 'Success.',
                        'details' => 'Gui tin nhan thanh cong.',
                        'data' => array(
                            'msisdn' => $msisdn,
                            'mo' => $mo,
                            'mt' => $mt,
                            'note' => $note
                        )
                    );
                }
                else
                {
                    // Gửi SMS thất bại
                    $response = array(
                        'ec' => 1,
                        'msg' => 'Failed.',
                        'details' => 'Gui tin nhan khong thanh cong.',
                        'res' => $request_sms,
                        'data' => array(
                            'msisdn' => $msisdn,
                            'mo' => $mo,
                            'mt' => $mt,
                            'note' => $note
                        )
                    );
                }
            }
            else
            {
                $response = array(
                    'ec' => 1,
                    'msg' => 'Parse Request is Error',
                    'res' => $request_sms,
                    'data' => array(
                        'msisdn' => $msisdn,
                        'mo' => $mo,
                        'mt' => $mt,
                        'note' => $note
                    )
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
     * Send_sms destructor.
     */
    public function __destruct()
    {
        $this->db_sms_history_model->close();
        log_message('debug', 'Webservice Send SMS - Close DB Connection!');
    }
}
/* End of file Send_sms.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Send-SMS/controllers/Send_sms.php */
