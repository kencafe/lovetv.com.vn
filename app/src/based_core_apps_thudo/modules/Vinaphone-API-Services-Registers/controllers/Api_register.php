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
class Api_register extends MX_Controller
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
    protected $vascloud;
    /**
     * Api_register constructor.
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
        $this->config->load('config_vinaphone_services');
        $this->service_id          = config_item('service_id');
        $this->service_cf_id       = config_item('service_cf_id');
        $this->service_transaction = config_item('service_transaction');
        $this->_webServices        = config_item('vinaphone_web_services');
        $this->vascloud            = config_item('vascloud');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_api_services']['register']['debug'];
        $this->logger_path = $this->mono['vina_api_services']['register']['logger_path'];
        $this->logger_file = $this->mono['vina_api_services']['register']['logger_file'];
        $this->logger_name = $this->mono['vina_api_services']['register']['logger_name'];
    }
    /**
     * API Xử lý đăng ký dịch vụ
     *
     * @link /api/v1/register.html
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
        $requestid    = $this->input->get_post('requestid', true); // Mã ngẫu nhiên
        $msisdn       = $this->input->get_post('msisdn', true); // Số thuê bao
        $packagename  = $this->input->get_post('packagename', true); // Mã gói dịch vụ
        $promotion    = $this->input->get_post('promotion', true); // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.
        $trial        = $this->input->get_post('trial', true); // Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.
        $bundle       = $this->input->get_post('bundle', true); // Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)
        $note         = $this->input->get_post('note', true); // Chú thích về đăng ký/khuyến mãi/dùng thử/tên gói bundle/MO đến
        $application  = $this->input->get_post('application', true); // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
        $channel      = $this->input->get_post('channel', true); // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
        $username     = $this->input->get_post('username', true); // Tên của người dùng thao tác
        $userip       = $this->input->get_post('userip', true); // IP của người dùng thao tác
        $input_params = array(
            'requestid' => $requestid,
            'msisdn' => $msisdn,
            'packagename' => $packagename,
            'promotion' => $promotion,
            'trial' => $trial,
            'bundle' => $bundle,
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
            // Thu thập thông tin cần thiết
            $msisdn         = $this->phone_number->phone_number_convert($msisdn, 'new');
            $msisdn_convert = $this->phone_number->phone_number_old_and_new($msisdn);
            $packagename    = strtoupper($packagename);
            $note           = $this->vinaphone_utilities->formatNote($note);
            $count_note     = $this->vinaphone_utilities->exNote($note, true);
            $is_note        = $this->vinaphone_utilities->exNote($note);
            // Kiểm tra và lấy thông tin dịch vụ
            $services       = $this->libs_db_services->get_data($this->service_id);
            // Data check info sub
            if ($services->onePack == 1)
            {
                $data_check = array(
                    'serviceId' => $this->service_id,
                    'msisdn' => $msisdn_convert
                );
            }
            else
            {
                $data_check = array(
                    'serviceId' => $this->service_id,
                    'packageId' => $packagename,
                    'msisdn' => $msisdn_convert
                );
            }
            /**
             * Get info Sub
             * Đoạn này xử lý thêm logic đăng ký 1 gói hay nhiều gói
             */
            $info_sub = $this->db_subscriber_model->get_info_sub($data_check);
            if ($info_sub === null)
            {
                $is_status   = 1;
                // Trường hợp thuê bao đăng ký mới
                $packages    = $this->libs_db_packages->get_data($packagename, $this->service_id);
                $is_password = random_string('numeric', 6);
                $is_salt     = random_string('md5');
                $dtId        = 1;
                if ($this->vascloud === true)
                {
                    // Nếu là Vascloud
                    $mo_command     = $this->input->get_post('commandcode', true);
                    $expire['time'] = date_format(date_create($this->input->get_post('expiredTime', true)), 'Y-m-d H:i:s');
                    $subtime        = date_format(date_create($this->input->get_post('subcribeTime', true)), 'Y-m-d H:i:s');
                    $amount         = $this->input->get_post('price', true);
                    /**
                     * Call VASCloud
                     */
                    $vas_request    = '{"result":0,"errorid":0,"desc":"Success","eventName":"register","amount":' . $amount . '}';
                }
                else
                {
                    // Nếu là Vas Gateway
                    $mo_command  = $count_note > 1 ? strtoupper($is_note[1]) : '';
                    $expire      = $this->vinaphone_utilities->getExpireTime($packages->duration);
                    $subtime     = date('Y-m-d H:i:s');
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
                        'eventName' => $this->service_transaction['eventName']['register'],
                        'price' => $vas_price,
                        'originalPrice' => $packages->price,
                        'promotion' => $promotion,
                        'channel' => $channel,
                        'signature' => md5($msisdn . $vas_prefix . $packagename . $vas_prefix . $this->service_transaction['eventName']['register'] . $vas_prefix . $vas_price . $vas_prefix . $promotion . $vas_prefix . $channel . $vas_prefix . $vas_token)
                    );
                    $vas_request = $this->requests->sendRequest($vas_url, $vas_params);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Send Request VAS URL ' . $vas_url);
                        $logger->info('Send Request VAS Params ', $vas_params);
                        $logger->info('Response from Request ' . $vas_request);
                    }
                }
                $parse_request = json_decode($vas_request);
                if (isset($parse_request->result) && $parse_request->result == 0)
                {
                    /**
                     * Create Subscriber
                     */
                    $user_data = array(
                        'requestId' => $requestid,
                        'dtId' => $dtId,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename,
                        'moCommand' => $mo_command,
                        'msisdn' => $msisdn,
                        'password' => $is_password,
                        'salt' => $is_salt,
                        'price' => $packages->price,
                        'lastTimeSubscribe' => $subtime,
                        'expireTime' => $expire['time'],
                        'status' => $is_status,
                        'promotion' => $promotion,
                        'trial' => $trial,
                        'bundle' => $bundle,
                        'note' => $note,
                        'application' => $application,
                        'channel' => $channel,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $user_id   = $this->db_subscriber_model->add($user_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Logger Subscriber <----|');
                        $logger->info('Create Subscriber Data ', $user_data);
                        $logger->info('Create Subscriber ID: ' . $user_id);
                    }
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => $requestid,
                        'dtId' => $dtId,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename,
                        'moCommand' => $mo_command,
                        'msisdn' => $msisdn,
                        'eventName' => $this->service_transaction['eventName']['register'],
                        'status' => $this->service_transaction['status']['register_ok'],
                        'price' => $packages->price,
                        'amount' => 0,
                        'mo' => $mo_command,
                        'application' => $application,
                        'channel' => $channel,
                        'username' => $username,
                        'userip' => $userip,
                        'promotion' => $promotion,
                        'trial' => $trial,
                        'bundle' => $bundle,
                        'note' => $note,
                        'type' => 2,
                        'extendType' => 1,
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
                        'route' => 'moRegister',
                        'data' => json_encode(array(
                            'phone' => $msisdn,
                            'package' => $packagename,
                            'event' => $this->service_transaction['eventName']['register'],
                            'message' => $mo_command,
                            'note' => $note,
                            'password' => $is_password,
                            'type' => 1,
                            'application' => $application,
                            'channel' => $channel,
                            'status' => 0,
                            'status_charge' => 1
                            // 'price' => $vas_price
                        )),
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    /**
                     * Xử lý SMS trả về
                     */
                    $mt_msg                = $this->libs_db_mt_config->get_data($packagename, 0, 1);
                    $mt                    = str_replace('[password]', $is_password, $mt_msg);
                    $sms_to_queues         = array(
                        'data' => json_encode(array(
                            'shortcode' => config_item('service_shortcode'),
                            'msisdn' => $msisdn,
                            'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                            'mt' => $mt,
                            'note' => $note,
                            'sub_code' => 'New_Register'
                        )),
                        'status' => 0,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    /**
                     * Response
                     */
                    $response              = array(
                        'errorid' => 3,
                        'errordesc' => 'Đăng ký thành công dịch vụ và không bị trừ cước đăng ký'
                    );
                }
                else
                {
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => $requestid,
                        'dtId' => $dtId,
                        'serviceId' => $this->service_id,
                        'packageId' => $packagename,
                        'moCommand' => $mo_command,
                        'msisdn' => $msisdn,
                        'eventName' => $this->service_transaction['eventName']['register'],
                        'status' => $this->service_transaction['status']['register_fail'],
                        'price' => $packages->price,
                        'amount' => 0,
                        'mo' => $mo_command,
                        'application' => $application,
                        'channel' => $channel,
                        'username' => $username,
                        'userip' => $userip,
                        'promotion' => $promotion,
                        'trial' => $trial,
                        'bundle' => $bundle,
                        'note' => $note,
                        'type' => 2,
                        'extendType' => 1,
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
                        'route' => 'moRegister',
                        'data' => json_encode(array(
                            'phone' => $msisdn,
                            'package' => $packagename,
                            'event' => $this->service_transaction['eventName']['register'],
                            'message' => $mo_command,
                            'note' => $note,
                            'password' => $is_password,
                            'type' => 1,
                            'application' => $application,
                            'channel' => $channel,
                            'status' => 1,
                            'status_charge' => 0
                            // 'price' => 0
                        )),
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    // Response
                    $response              = array(
                        'errorid' => 102,
                        'errordesc' => 'Đăng ký không thành công'
                    );
                }
            }
            else
            {
                if ($info_sub->status == 1)
                {
                    /**
                     * Xử lý SMS trả về
                     */
                    $mt            = $this->libs_db_mt_config->get_data($packagename, 2, 1);
                    $sms_to_queues = array(
                        'data' => json_encode(array(
                            'shortcode' => config_item('service_shortcode'),
                            'msisdn' => $msisdn,
                            'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                            'mt' => $mt,
                            'note' => $note,
                            'sub_code' => 'User_is_Current_Register'
                        )),
                        'status' => 0,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $response      = array(
                        'errorid' => 1,
                        'errordesc' => 'Thuê bao đang sử dụng gói dịch vụ này'
                    );
                }
                else
                {
                    /**
                     * Trường hợp thuê bao đăng ký lại
                     */
                    $packages    = $this->libs_db_packages->get_data($packagename, $this->service_id);
                    $mo_command  = $count_note > 1 ? strtoupper($is_note[1]) : '';
                    $is_password = random_string('numeric', 6);
                    $is_salt     = random_string('md5');
                    $dtId        = 1;
                    if ($this->vascloud === true)
                    {
                        // Nếu là Vascloud
                        $mo_command     = $this->input->get_post('commandcode', true);
                        $expire['time'] = date_format(date_create($this->input->get_post('expiredTime', true)), 'Y-m-d H:i:s');
                        $subtime        = date_format(date_create($this->input->get_post('subcribeTime', true)), 'Y-m-d H:i:s');
                        $amount         = $this->input->get_post('price', true);
                        /**
                         * Call VASCloud
                         */
                        $vas_request    = '{"result":0,"errorid":0,"desc":"Success","eventName":"register","amount":' . $amount . '}';
                    }
                    else
                    {
                        // Nếu là Vas Gateway
                        $mo_command  = $count_note > 1 ? strtoupper($is_note[1]) : '';
                        $expire      = $this->vinaphone_utilities->getExpireTime($packages->duration);
                        $subtime     = date('Y-m-d H:i:s');
                        /**
                         * Call VAS Gateway
                         */
                        $vas_url     = private_api_url($this->_webServices['charging']['url']);
                        $vas_token   = $this->_webServices['charging']['token'];
                        $vas_prefix  = $this->_webServices['charging']['prefix'];
                        $vas_price   = $packages->price;
                        $vas_params  = array(
                            'msisdn' => $msisdn,
                            'packageName' => $packagename,
                            'eventName' => $this->service_transaction['eventName']['register'],
                            'price' => $vas_price,
                            'originalPrice' => $packages->price,
                            'promotion' => $promotion,
                            'channel' => $channel,
                            'signature' => md5($msisdn . $vas_prefix . $packagename . $vas_prefix . $this->service_transaction['eventName']['register'] . $vas_prefix . $vas_price . $vas_prefix . $promotion . $vas_prefix . $channel . $vas_prefix . $vas_token)
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
                        $is_status = 1;
                        /**
                         * Create Subscriber
                         */
                        $user_data = array(
                            'requestId' => $requestid,
                            'dtId' => $dtId,
                            'serviceId' => $this->service_id,
                            'packageId' => $packagename,
                            'moCommand' => $mo_command,
                            'msisdn' => $msisdn,
                            'password' => $is_password,
                            'salt' => $is_salt,
                            'price' => $packages->price,
                            'lastTimeSubscribe' => $subtime,
                            'expireTime' => $expire['time'],
                            'status' => $is_status,
                            'promotion' => $promotion,
                            'trial' => $trial,
                            'bundle' => $bundle,
                            'note' => $note,
                            'application' => $application,
                            'channel' => $channel,
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        // Update data user
                        $user_id   = $this->db_subscriber_model->update_services_subscribers($data_check, $user_data);
                        if ($this->DEBUG === true)
                        {
                            $logger->info('|----> Logger Subscriber <----|');
                            $logger->info('Update Subscriber Data ', $user_data);
                            $logger->info('Update Subscriber ID: ' . $user_id);
                        }
                        /**
                         * Create Transaction
                         */
                        $transaction_data = array(
                            'requestId' => $requestid,
                            'dtId' => $dtId,
                            'serviceId' => $this->service_id,
                            'packageId' => $packagename,
                            'moCommand' => $mo_command,
                            'msisdn' => $msisdn,
                            'eventName' => $this->service_transaction['eventName']['register'],
                            'status' => $this->service_transaction['status']['re_register_ok'],
                            'price' => $packages->price,
                            'amount' => $packages->price,
                            'mo' => $mo_command,
                            'application' => $application,
                            'channel' => $channel,
                            'username' => $username,
                            'userip' => $userip,
                            'promotion' => $promotion,
                            'trial' => $trial,
                            'bundle' => $bundle,
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
                            'route' => 'moRegister',
                            'data' => json_encode(array(
                                'phone' => $msisdn,
                                'package' => $packagename,
                                'event' => $this->service_transaction['eventName']['register'],
                                'message' => $mo_command,
                                'note' => $note,
                                'password' => $is_password,
                                'type' => 2,
                                'application' => $application,
                                'channel' => $channel,
                                'status' => 0,
                                'status_charge' => 0
                                // 'price' => $vas_price
                            )),
                            'day' => date('Ymd'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        /**
                         * Xử lý SMS trả về
                         */
                        $mt_msg                = $this->libs_db_mt_config->get_data($packagename, 1, 1);
                        $mt                    = str_replace('[password]', $is_password, $mt_msg);
                        $sms_to_queues         = array(
                            'data' => json_encode(array(
                                'shortcode' => config_item('service_shortcode'),
                                'msisdn' => $msisdn,
                                'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                                'mt' => $mt,
                                'note' => $note,
                                'sub_code' => 'New_Register'
                            )),
                            'status' => 0,
                            'day' => date('Ymd'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        /**
                         * Response
                         */
                        $response              = array(
                            'errorid' => 4,
                            'errordesc' => 'Đăng ký thành công dịch vụ và bị trừ cước đăng ký'
                        );
                    }
                    else
                    {
                        /**
                         * Đăng ký không thành công do không đủ tiền trong tài khoản
                         */
                        /**
                         * Create Transaction
                         */
                        $transaction_data = array(
                            'requestId' => $requestid,
                            'dtId' => $dtId,
                            'serviceId' => $this->service_id,
                            'packageId' => $packagename,
                            'moCommand' => $mo_command,
                            'msisdn' => $msisdn,
                            'eventName' => $this->service_transaction['eventName']['register'],
                            'status' => $this->service_transaction['status']['re_register_fail'],
                            'price' => $packages->price,
                            'amount' => 0,
                            'mo' => $mo_command,
                            'application' => $application,
                            'channel' => $channel,
                            'username' => $username,
                            'userip' => $userip,
                            'promotion' => $promotion,
                            'trial' => $trial,
                            'bundle' => $bundle,
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
                            'route' => 'moRegister',
                            'data' => json_encode(array(
                                'phone' => $msisdn,
                                'package' => $packagename,
                                'event' => $this->service_transaction['eventName']['register'],
                                'message' => $mo_command,
                                'note' => $note,
                                'password' => $is_password,
                                'type' => 2,
                                'application' => $application,
                                'channel' => $channel,
                                'status' => 1,
                                'status_charge' => 0
                                // 'price' => 0
                            )),
                            'day' => date('Ymd'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        /**
                         * Create Queues SMS
                         */
                        $mt                    = $this->libs_db_mt_config->get_data($packagename, 3, 1);
                        $sms_to_queues         = array(
                            'data' => json_encode(array(
                                'shortcode' => config_item('service_shortcode'),
                                'msisdn' => $msisdn,
                                'mo' => (empty($mo_command)) ? $packagename : $mo_command,
                                'mt' => $mt,
                                'note' => $note,
                                'sub_code' => 'Balance_too_low'
                            )),
                            'status' => 0,
                            'day' => date('Ymd'),
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $response              = array(
                            'errorid' => 5,
                            'errordesc' => 'Đăng ký không thành công do không đủ tiền trong tài khoản'
                        );
                    }
                }
            }
            // Ngắt luồng queues và SMS của Vascloud
            if ($this->vascloud === false)
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
            }
            else
            {
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
                    'package_code' => $packagename,
                    'sub_code' => $data_sms['sub_code'],
                    'signature' => md5($data_sms['msisdn'] . $sms_prefix . $data_sms['mt'] . $sms_prefix . $sms_token)
                );
                if ($this->apiVascloud['sendSms']['is_development'] == true)
                {
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

                // Trả tin mật khẩu cho vascloud
                if (isset($response['errorid']) && in_array($response['errorid'], array(
                        3,
                        4
                    )))
                {
                    $apiBusiness     = config_item('vinaphone_api_services');
                    $shortcode       = config_item('service_shortcode');
                    $business_url    = private_api_url($apiBusiness['business']['url']);
                    $business_token  = $apiBusiness['business']['token'];
                    $business_prefix = $apiBusiness['business']['prefix'];
                    $business_params = array(
                        'shortcode' => $shortcode,
                        'msisdn' => $data_sms['msisdn'],
                        'mo' => 'MK',
                        'signature' => md5($data_sms['msisdn'] . $business_prefix . 'MK' . $business_prefix . $shortcode . $business_prefix . $business_token)
                    );
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Send Business to URL: ' . $business_url);
                        $logger->info('Send Business with Params: ', $business_params);
                    }
                    $request_business = $this->requests->sendRequest($business_url, $business_params);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Send Business Result: ' . $request_business);
                    }
                }
            }
            /**
             * Trả tin nhắn nội dung dịch vụ
             */
            //            if (isset($response['errorid']) && in_array($response['errorid'], array(
            //                    3,
            //                    4
            //                )))
            //            {
            //                // Chỉ trả tin nếu đăng ký thành công
            //                $this->load->library('Contents/push_content');
            //                $this->push_content->setShortcode(config_item('service_shortcode'));
            //                $this->push_content->setDate();
            //                // gửi nội dung
            //                $this->push_content->send_content($msisdn, $packagename, $this->service_id);
            //            }
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
     * Api_register destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        $this->db_transaction_model->close();
        log_message('error', 'API Register - Dong ket noi CSDL!');
    }
}
/* End of file Api_register.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-Registers/controllers/Api_register.php */
