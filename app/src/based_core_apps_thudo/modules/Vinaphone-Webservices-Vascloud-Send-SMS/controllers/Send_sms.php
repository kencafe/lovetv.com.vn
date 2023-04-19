<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
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
    private $_smsgw_vascloud;
    private $_private_token;
    private $_prefix_token;
    private $_brandname;
    private $_serviceid;
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
            'vinaphone_utilities',
            'Vina_Services/libs_db_packages'
        ));
        $this->load->model('Vina_Services/db_sms_history_model');
        $this->config->load('config_vinaphone_services');
        $this->_webServices = config_item('vinaphone_web_services');
        $this->_brandname   = config_item('brandname');
        $this->_serviceid   = config_item('service_id');
        $this->config->load('config_send_sms');
        $this->_smsgw_vascloud = config_item('smsgw_vascloud');
        $this->_private_token  = $this->_webServices['sendSms']['token'];
        $this->_prefix_token   = $this->_webServices['sendSms']['prefix'];
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['sendSms']['debug'];
        $this->logger_path = $this->mono['vascloud']['sendSms']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['sendSms']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['sendSms']['logger_name'];
    }
    /**
     * Webservice xử lý gửi MT SMSGW Vascloud XML
     * Phương thức: HTTP/XML
     * Được xây dựng trên chuẩn SMSGW Vascloud mới của Vina
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @link /vascloud/v1/sendSms.html
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
        $input_msisdn      = $this->input->get_post('msisdn', true); // Số thuê bao
        $input_mo          = $this->input->get_post('mo', true); // Mo
        $input_mt          = $this->input->get_post('mt', true); // MT
        $input_note        = $this->input->get_post('note', true); // Chú thích
        $input_signature   = $this->input->get_post('signature', true); // Chữ ký bí mật
        $input_sub_code    = $this->input->get_post('sub_code', true); // Mã gói con
        $input_mtIsTT08    = $this->input->get_post('mtIsTT08', TRUE);
        $input_send_method = $this->input->get_post('send_method', true); // Phương thức gửi Msg_Log: test
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
//            $msisdn             = $this->phone_number->format($input_msisdn);
            $msisdn             = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $mt                 = trim($input_mt);
            $mt_base            = base64_encode($mt);
            $mo                 = ($input_mo !== null) ? trim($input_mo) : '';
            $note               = ($input_note !== null) ? trim($input_note) : '';
            $sub_code           = ($input_sub_code !== null) ? trim($input_sub_code) : '';
            /**
             * Tiến hành forward SMS tới SMS Gateway
             */
            $sms_url            = $this->_smsgw_vascloud['url'];
            $sms_shortcode      = $this->_smsgw_vascloud['shortcode'];
            $sms_usernamecp     = $this->_smsgw_vascloud['username_cp'];
            $sms_cp_code        = $this->_smsgw_vascloud['cp_code'];
            $sms_cp_charge      = $this->_smsgw_vascloud['cp_charge'];
            $transaction_id     = ceil(microtime(true) * 1000);
            $authenticate       = $this->_smsgw_vascloud['authenticate'];
            $price              = $this->_smsgw_vascloud['default_price'];
            $acount_send_sms    = $this->_smsgw_vascloud['account'];
            $input_package_code = $this->_smsgw_vascloud['package'];
            if ($input_mtIsTT08 == 'YES') {
                $price              = 0;
                $input_package_code = 'TT08_' . $this->_serviceid;
            } else {
                $price              = $this->_smsgw_vascloud['default_price'];
                $input_package_code = $this->_smsgw_vascloud['package'];
            }
            $sms_authenticate   = md5(md5($transaction_id . $sms_usernamecp) . md5($acount_send_sms . $msisdn) . $authenticate);
            $content_xml           = "<MODULE>SMSGW</MODULE><MESSAGE_TYPE>REQUEST</MESSAGE_TYPE><COMMAND><transaction_id>$transaction_id</transaction_id><mo_id>0</mo_id><destination_address>$msisdn</destination_address><source_address>$sms_shortcode</source_address><brandname>$this->_brandname</brandname><content_type>TEXT</content_type><encode_content>1</encode_content><user_name>$sms_usernamecp</user_name><authenticate>$sms_authenticate</authenticate><info>$mt_base</info><command_code>$mo</command_code><cp_code>$sms_cp_code</cp_code><cp_charge>$sms_cp_charge</cp_charge><service_code>$this->_serviceid</service_code><package_code>$input_package_code</package_code><package_price>$price</package_price></COMMAND>";
            $data_xml           = '<?xml version="1.0" encoding="utf-8"?><ACCESSGW xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . $content_xml . '</ACCESSGW>';
            if ($this->DEBUG === true)
            {
                $logger->info('=====> Send SMS to Vascloud <=====');
                $logger->info('Send Request to URL ' . $sms_url);
                $logger->info('Send Request Data ' . $data_xml);
            }
            // Send Request SMS
            $request_sms = (($input_send_method !== null) && ($input_send_method == 'Msg_Log')) ? $this->_smsgw_vascloud['msg_log_response'] : $this->vinaphone_utilities->getHTTPResponse($sms_url, $data_xml, $this->_smsgw_vascloud['timeout']);
            if ($this->DEBUG === true)
            {
                $logger->info('Response from Request: ' . $request_sms);
            }
            // Parse Request
            $error_id_request = $this->vinaphone_utilities->getValue($request_sms, "<error_id>", "</error_id>");
            if ($request_sms === false)
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
            else
            {
                if ($error_id_request == 0)
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
     * Send_sms destructor.
     */
    public function __destruct()
    {
        $this->db_sms_history_model->close();
        log_message('debug', 'Webservice Send SMS - Close DB Connection!');
    }
}
/* End of file Send_sms.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-Send-SMS/controllers/Send_sms.php */
