<?php
/**
 * Created by PhpStorm.
 * User: TungChem
 * Date: 1/31/2018
 * Time: 10:43 AM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Api_regcontent extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_apiServices;
    private $_prefix_token;
    private $_private_token;
    private $servicename;
    /**
     * Api_regcontent constructor.
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
            'Vina_Services/libs_db_commands',
            'Vina_Services/libs_db_mt_config'
        ));
        $this->load->model(array(
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_transaction_model'
        ));
        // Load config
        $this->config->load('config_vinaphone_vascloud');
        $this->servicename    = config_item('Servicename');
        $this->_apiServices   = config_item('vascloud_api_services');
        $this->_prefix_token  = $this->_apiServices['regContentVascloud']['prefix'];
        $this->_private_token = $this->_apiServices['regContentVascloud']['token'];
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vascloud']['regContent']['debug'];
        $this->logger_path = $this->mono['vascloud']['regContent']['logger_path'];
        $this->logger_file = $this->mono['vascloud']['regContent']['logger_file'];
        $this->logger_name = $this->mono['vascloud']['regContent']['logger_name'];
    }

    /**
     * API Xử lý mua content dịch vụ
     * @link /vascloud/v1/regcontent.html
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
            $logger->info('|======== Begin Register  ========|');
        }
        // Get Params
        $shortcode    = $this->input->get_post('shortcode', true); // Đầu số dịch vụ
        $moid         = $this->input->get_post('moid', true); // ID của MO tương ứng với MT
        $msisdn       = $this->input->get_post('msisdn', true); // Số thuê bao
        $mo           = $this->input->get_post('mo', true); // Cú pháp đăng ký
        $signature    = $this->input->get_post('signature', true); // Chữ ký xác thực.
        $valid_signature = md5($moid . $this->_prefix_token . $msisdn . $this->_prefix_token . $mo . $this->_prefix_token . $shortcode . $this->_prefix_token . $this->_private_token);
        $input_params = array(
            'shortcode' => $shortcode,
            'moid'      => $moid,
            'msisdn'    => $msisdn,
            'mo'        => $mo,
            'signature' => $signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // filters
        if ($moid === null || $shortcode === null || $msisdn === null || $mo === null)
        {
            $response = array(
                'errorid' => 2,
                'errordesc' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif($valid_signature !== $signature)
        {
            $response = array(
                'errorid' => 3,
                'errordesc' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
            // Thu thập thông tin cần thiết
            $msisdn         = $this->phone_number->format($msisdn);
            $packageName    = $this->libs_db_commands->get_data($mo, 'packageid');
            $package =  $this->libs_db_packages->get_data($packageName, $this->servicename);
            // Tiền hành charge tiền
            $charge_url = private_api_url($this->_apiServices['charging']['url']);
            $prefix     = $this->_apiServices['charging']['prefix'];
            $token      = $this->_apiServices['charging']['token'];
            $signature  = md5($msisdn . $prefix . $packageName . $prefix . $token);
            $charge_data = array(
                'msisdn' => $msisdn,
                'packageName' => $packageName,
                'eventName' => 'REG',
                'price' => $package->price,
                'originalPrice' => $package->price,
                'promotion' => 0,
                'channel' => 'SMS',
                'signature' => $signature
            );
            if($this->_apiServices['charging']['is_development'] == true){
                $charge_data['send_method'] = 'Msg_Log';
            }
            if ($this->DEBUG === true)
            {
                $logger->info('Charging to URL: ' . $charge_url);
                $logger->info('Charging with Params: ', $charge_data);
            }
            $request_charge = $this->requests->sendRequest($charge_url, $charge_data);
            if ($this->DEBUG === true)
            {
                $logger->info('Charging Result: ' . $request_charge);
            }
            $request_jscharge = json_decode($request_charge);
            if($request_jscharge->result === 0)
            {
                // Charge thành công
                $response = array(
                    'errorid' => 0,
                    'errordesc' => 'Đăng ký thành công.'
                );
            }
            else
            {
                // Charge thất bại
                $response = array(
                    'errorid' => 1,
                    'errordesc' => 'Đăng ký thất bại.'
                );
            }
            /**
             * Trả tin nhắn nội dung dịch vụ
             */
            if (isset($response['errorid']) && in_array($response['errorid'], array(
                    0
                )))
            {
                // Chỉ trả tin nếu đăng ký thành công
                $this->load->library('Contents/push_content');
                $this->push_content->setShortcode(config_item('service_shortcode'));
                $this->push_content->setDate();
                // gửi nội dung
                $this->push_content->send_content($msisdn, $packageName, $this->servicename, $mo, $moid, true);
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
}
/* End of file Api_regcontent.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webserrvices-Vascloud-Charging/controllers/Api_regcontent.php */