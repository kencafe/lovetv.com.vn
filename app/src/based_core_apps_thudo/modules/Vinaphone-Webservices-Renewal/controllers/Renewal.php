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
class Renewal extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_webServices;
    private $_renewal;
    private $_private_token;
    private $_prefix_token;
    /**
     * Renewal constructor.
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
            'Vina_Services/libs_db_packages'
        ));
        $this->load->model(array(
            'Vina_Services/db_transaction_model',
            'Vina_Services/db_subscriber_model',
            'Vina_Services/db_queues_model'
        ));
        $this->config->load('config_vinaphone_services');
        $this->_renewal            = config_item('service_renewal');
        $this->_webServices        = config_item('vinaphone_web_services');
        $this->_private_token      = $this->_webServices['renewal']['token'];
        $this->_prefix_token       = $this->_webServices['renewal']['prefix'];
        $this->service_id          = config_item('service_id');
        $this->service_cf_id       = config_item('service_cf_id');
        $this->service_transaction = config_item('service_transaction');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_web_services']['renewal']['debug'];
        $this->logger_path = $this->mono['vina_web_services']['renewal']['logger_path'];
        $this->logger_file = $this->mono['vina_web_services']['renewal']['logger_file'];
        $this->logger_name = $this->mono['vina_web_services']['renewal']['logger_name'];
    }
    /**
     * Webservice Renewal
     *
     * Trung tâm xử lý gia hạn dịch vụ
     *
     * @link /web/v1/renewal.html
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
            $logger->info('|======== Begin Renewal  ========|');
        }
        // Input Params
        $input_msisdn      = $this->input->get_post('msisdn', true);
        $input_packageName = $this->input->get_post('packageName', true);
        $input_eventName   = $this->input->get_post('eventName', true);
        $input_price       = $this->input->get_post('price', true);
        $input_channel     = $this->input->get_post('channel', true);
        $input_signature   = $this->input->get_post('signature', true);
        $valid_signature   = md5($input_msisdn . $this->_prefix_token . $input_packageName . $this->_prefix_token . $input_eventName . $this->_prefix_token . $input_price . $this->_prefix_token . $input_channel . $this->_prefix_token . $this->_private_token);
        $input_params      = array(
            'msisdn' => $input_msisdn,
            'packageName' => $input_packageName,
            'eventName' => $input_eventName,
            'price' => $input_price,
            'channel' => $input_channel,
            'signature' => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            $logger->info($getMethod . ' ' . current_url(), $input_params);
        }
        // Filter
        if ($input_msisdn === null || $input_packageName === null || $input_price === null || $input_signature === null)
        {
            $response = array(
                'result' => 2,
                'desc' => 'Sai hoặc thiếu tham số.'
            );
        }
        elseif ($input_signature != $valid_signature)
        {
            $response = array(
                'result' => 3,
                'desc' => 'Sai chữ ký xác thực.',
                'valid' => (ENVIRONMENT === 'production') ? $valid_signature : null
            );
        }
        else
        {
            $msisdn       = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $msisdn_convert   = $this->phone_number->phone_number_old_and_new($msisdn);
            $serviceId    = config_item('service_id');
            $packageName  = strtoupper($input_packageName);
            $price        = intval($input_price);
            $up_eventName = strtoupper($input_eventName);
            $lo_eventName = strtolower($input_eventName);
            $up_channel   = strtoupper($input_channel);
            $lo_channel   = strtolower($input_channel);
            $is_note      = $up_eventName . '|' . $msisdn . '|' . $price . '|' . $packageName;
            $dtId         = 1;
            // Lấy thông tin sub
            $data_check   = array(
                'serviceId' => $serviceId,
                'msisdn' => $msisdn_convert,
                'packageId' => $packageName
            );
            $data_select  = 'serviceId, packageId, moCommand, msisdn, numberRetry';
            $sub_info     = $this->db_subscriber_model->getInfoSubscribers($data_check, false, false, $data_select);
            $package_info = $this->libs_db_packages->get_data($packageName, $serviceId);
            if ($sub_info === null || $package_info === null)
            {
                $response = array(
                    'result' => 4,
                    'desc' => 'Không tìm thấy thông tin sub hoặc package'
                );
            }
            else
            {
                // Send Request to Charging
                $charging_url       = private_api_url($this->_webServices['charging']['url']);
                $charging_token     = $this->_webServices['charging']['token'];
                $charging_prefix    = $this->_webServices['charging']['prefix'];
                $charging_promotion = 0;
                $charging_params    = array(
                    'msisdn' => $msisdn,
                    'packageName' => $packageName,
                    'eventName' => $lo_eventName,
                    'price' => $price,
                    'originalPrice' => $price,
                    'promotion' => $charging_promotion,
                    'channel' => $up_channel,
                    'signature' => md5($msisdn . $charging_prefix . $packageName . $charging_prefix . $lo_eventName . $charging_prefix . $price . $charging_prefix . $charging_promotion . $charging_prefix . $up_channel . $charging_prefix . $charging_token)
                );
                $send_request       = $this->requests->sendRequest($charging_url, $charging_params);
                if ($this->DEBUG === true)
                {
                    $logger->info('|-----> Call Webservice Charging <-----|');
                    $logger->info('Send Request to URL: ' . $charging_url);
                    $logger->info('Send Request with Params: ', $charging_params);
                    $logger->info('Response from Request ' . $send_request);
                }
                $parse_request = json_decode($send_request);
                if (isset($parse_request->result) && $parse_request->result == 0)
                {
                    // Charge thành công
                    $amount           = $price;
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => date('YmdHis') . random_string('numeric', 10),
                        'dtId' => 1,
                        'serviceId' => $serviceId,
                        'packageId' => $packageName,
                        'moCommand' => $sub_info->moCommand,
                        'msisdn' => $msisdn,
                        'eventName' => $this->service_transaction['eventName'][$lo_eventName],
                        'status' => $this->service_transaction['status'][$lo_eventName . '_ok'],
                        'price' => $price,
                        'amount' => $amount,
                        'mo' => $sub_info->moCommand,
                        'application' => 'SYSTEM',
                        'channel' => $up_channel,
                        'username' => 'CRONJOB',
                        'userip' => '127.0.0.1',
                        'promotion' => $charging_promotion,
                        'trial' => null,
                        'bundle' => 0,
                        'note' => $is_note,
                        'reason' => null,
                        'policy' => null,
                        'type' => 2,
                        'extendType' => 2,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs' => null
                    );
                    $transaction_id   = $this->db_transaction_model->add($transaction_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|---> Create Transaction <---|');
                        $logger->info('Transaction Data: ', $transaction_data);
                        $logger->info('Transaction ID: ' . $transaction_id);
                    }
                    /**
                     * Update Subscriber
                     */
                    $expire   = $this->vinaphone_utilities->getExpireTime($package_info->duration);
                    $data_sub = array();
                    if ($lo_eventName == 'renew')
                    {
                        $data_sub['lastTimeRenew'] = date('Y-m-d H:i:s');
                    }
                    if ($lo_eventName == 'retry')
                    {
                        $data_sub['lastTimeRetry'] = date('Y-m-d H:i:s');
                    }
                    $data_sub['expireTime']  = $expire['time'];
                    $data_sub['status']      = 1;
                    $data_sub['numberRetry'] = 0;
                    $data_sub['updated_at']  = date('Y-m-d H:i:s');
                    $update_sub = $this->db_subscriber_model->update_services_subscribers($data_check, $data_sub);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|---> Update Subscriber <---|');
                        $logger->info('Subscriber Data: ', $data_sub);
                        $logger->info('Subscriber ResultId: ' . $update_sub);
                    }
                    // Response
                    $response = array(
                        'result' => 0,
                        'desc' => 'Charge thành công',
                        'data' => array(
                            'msisdn' => $msisdn,
                            'package' => $packageName,
                            'event' => $lo_eventName,
                            'amount' => $amount
                        )
                    );
                }
                else
                {
                    // Charge thất bại
                    $amount           = 0;
                    /**
                     * Create Transaction
                     */
                    $transaction_data = array(
                        'requestId' => date('YmdHis') . random_string('numeric', 10),
                        'dtId' => 1,
                        'serviceId' => $serviceId,
                        'packageId' => $packageName,
                        'moCommand' => $sub_info->moCommand,
                        'msisdn' => $msisdn,
                        'eventName' => $this->service_transaction['eventName'][$lo_eventName],
                        'status' => $this->service_transaction['status'][$lo_eventName . '_fail'],
                        'price' => $price,
                        'amount' => $amount,
                        'mo' => $sub_info->moCommand,
                        'application' => 'SYSTEM',
                        'channel' => $up_channel,
                        'username' => 'CRONJOB',
                        'userip' => '127.0.0.1',
                        'promotion' => $charging_promotion,
                        'trial' => null,
                        'bundle' => 0,
                        'note' => $is_note,
                        'reason' => null,
                        'policy' => null,
                        'type' => 2,
                        'extendType' => 2,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs' => null
                    );
                    $transaction_id   = $this->db_transaction_model->add($transaction_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|---> Create Transaction <---|');
                        $logger->info('Transaction Data: ', $transaction_data);
                        $logger->info('Transaction ID: ' . $transaction_id);
                    }
                    /**
                     * Update Subscriber
                     */
                    $data_sub = array();
                    if ($lo_eventName == 'renew')
                    {
                        $data_sub['lastTimeRenew'] = date('Y-m-d H:i:s');
                    }
                    if ($lo_eventName == 'retry')
                    {
                        $data_sub['lastTimeRetry'] = date('Y-m-d H:i:s');
                    }
                    $data_sub['status']      = 1;
                    $data_sub['numberRetry'] = $sub_info->numberRetry + 1;
                    $data_sub['updated_at']  = date('Y-m-d H:i:s');
                    $update_sub = $this->db_subscriber_model->update_services_subscribers($data_check, $data_sub);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|---> Update Subscriber <---|');
                        $logger->info('Subscriber Data: ', $data_sub);
                        $logger->info('Subscriber ResultId: ' . $update_sub);
                    }
                    // Response
                    $response = array(
                        'result' => 1,
                        'desc' => 'Charge thất bại',
                        'data' => array(
                            'msisdn' => $msisdn,
                            'package' => $packageName,
                            'event' => $lo_eventName,
                            'amount' => $amount
                        )
                    );
                    /**
                     * Hủy dịch vụ nếu charge Failed quá số lần quy định.
                     */
                    if (($lo_eventName == 'retry') && ($data_sub['numberRetry'] > $this->_renewal['maxRetrySlot']))
                    {
                        // Tiến hành hủy dịch vụ
                        $log_charge_failed             = 'Unsub by numberRetry: ' . $data_sub['numberRetry'] . ' and maxRetrySlot: ' . $this->_renewal['maxRetrySlot'] . '.';
                        // Update Unsub to DB Subscriber
                        $data_unsub                    = array(
                            'lastTimeUnSubscribe' => date('Y-m-d H:i:s'),
                            'expireTime' => null,
                            'status' => 0,
                            'numberRetry' => 0,
                            'logs' => $log_charge_failed,
                            'updated_at' => date('Y-m-d H:i:s')
                        );
                        $result_unsub_by_charge_failed = $this->db_subscriber_model->update_services_subscribers($data_check, $data_unsub);
                        if ($this->DEBUG === true)
                        {
                            $logger->info('Data UnSubscriber Update: ', $data_unsub);
                            $logger->info('Result UnSubscriber Update: ' . $result_unsub_by_charge_failed);
                        }
                        // Update Unsub to DB Transaction
                        $data_unsub_by_charge_failed   = array(
                            'requestId' => date('YmdHis') . random_string('numeric', 10),
                            'dtId' => 1,
                            'serviceId' => $serviceId,
                            'packageId' => $packageName,
                            'moCommand' => $sub_info->moCommand,
                            'msisdn' => $msisdn,
                            'eventName' => $this->service_transaction['eventName']['cancel'],
                            'status' => $this->service_transaction['status']['unregister_ok'],
                            'price' => 0,
                            'amount' => 0,
                            'mo' => $sub_info->moCommand,
                            'application' => 'SYSTEM',
                            'channel' => $up_channel,
                            'username' => 'CRONJOB',
                            'userip' => '127.0.0.1',
                            'promotion' => $charging_promotion,
                            'trial' => null,
                            'bundle' => 0,
                            'note' => $is_note,
                            'reason' => null,
                            'policy' => null,
                            'type' => 2,
                            'extendType' => 2,
                            'day' => date('Ymd'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'logs' => $log_charge_failed
                        );
                        $result_unsub_by_charge_failed = $this->db_transaction_model->add($data_unsub_by_charge_failed);
                        if ($this->DEBUG === true)
                        {
                            $logger->info('Data Transaction Unsubscriber by chargeFailed: ', $data_unsub_by_charge_failed);
                            $logger->info('ID Transaction Unsubscriber by chargeFailed: ' . $result_unsub_by_charge_failed);
                        }
                    }
                }
                /**
                 * Push Data Charge Transaction to Queues
                 *
                 * Ghi dữ liệu Transaction vào Queues để đẩy đồng bộ vào CMS tập trung
                 */
                if (isset($transaction_data))
                {
                    $tran_data              = json_encode(array(
                        'phone' => $transaction_data['msisdn'],
                        'package' => $transaction_data['packageId'],
                        'event' => $up_eventName,
                        'status' => $response['result'],
                        'price' => $transaction_data['price'],
                        'note' => $transaction_data['note']
                    ));
                    $data_queue_transaction = array(
                        'service_id' => config_item('service_cf_id'),
                        'route' => 'moCharge',
                        'data' => $tran_data,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs' => null
                    );
                    $queue_id               = $this->db_queues_model->add($data_queue_transaction);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Queue Transaction Data: ', $data_queue_transaction);
                        $logger->info('Queue Transaction ID: ' . $queue_id);
                    }
                    unset($queue_id);
                    unset($data_queue_transaction);
                    unset($transaction_data);
                    unset($tran_data);
                }
                /**
                 * Push Data Cancel Transaction to Queues
                 *
                 * Ghi dữ liệu Transaction vào Queues để đẩy đồng bộ vào CMS tập trung
                 */
                if (isset($data_unsub_by_charge_failed))
                {
                    $tran_cancel_data  = json_encode(array(
                        'phone' => $data_unsub_by_charge_failed['msisdn'],
                        'package' => $data_unsub_by_charge_failed['packageId'],
                        'event' => $up_eventName,
                        'message' => null,
                        'note' => null,
                        'type' => 3,
                        'application' => $data_unsub_by_charge_failed['application'],
                        'channel' => $data_unsub_by_charge_failed['channel'],
                        'status' => 0,
                        'status_charge' => 1
                    ));
                    $data_queue_cancel = array(
                        'service_id' => config_item('service_cf_id'),
                        'route' => 'moCancel',
                        'data' => $tran_cancel_data,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs' => null
                    );
                    $queue_cancel_id   = $this->db_queues_model->add($data_queue_cancel);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Queue Transaction Data: ', $data_queue_cancel);
                        $logger->info('Queue Transaction ID: ' . $queue_cancel_id);
                    }
                    unset($queue_cancel_id);
                    unset($data_queue_cancel);
                    unset($data_unsub_by_charge_failed);
                    unset($tran_cancel_data);
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
     * Renewal destructor.
     */
    public function __destruct()
    {
        $this->db_transaction_model->close();
        $this->db_subscriber_model->close();
        $this->db_queues_model->close();
        log_message('debug', 'Webservice Renewal - Close DB Connection!');
    }
}
/* End of file Renewal.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Renewal/controllers/Renewal.php */
