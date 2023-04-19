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
class Charging extends MX_Controller
{
    const CCGW_STATUS_SUCCESS = 'Success';
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    protected $useProxy;
    private $_charge_proxy;
    private $_webServices;
    private $_private_token;
    private $_prefix_token;
    /**
     * Charging constructor.
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
            'td_proxy_vina_charge',
            'vinaphone_ccgw'
        ));
        $this->load->model('Vina_Services/db_charge_log_model');
        $this->config->load('config_vinaphone_services');
        $this->config->load('config_vinaphone_charging');
        $this->_charge_proxy  = config_item('charging_proxy');
        $this->_webServices   = config_item('vinaphone_web_services');
        $this->_private_token = $this->_webServices['charging']['token'];
        $this->_prefix_token  = $this->_webServices['charging']['prefix'];
        $this->useProxy       = config_item('use_proxy');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_web_services']['charging']['debug'];
        $this->logger_path = $this->mono['vina_web_services']['charging']['logger_path'];
        $this->logger_file = $this->mono['vina_web_services']['charging']['logger_file'];
        $this->logger_name = $this->mono['vina_web_services']['charging']['logger_name'];
    }
    /**
     * Webservice xử lý charging
     *
     * Tiến hành gọi qua PROXY Charge tập trung
     * và lưu log charge vào DB
     *
     * @link /web/v1/charging.html
     */
    /**
     * Webservice xử lý charging
     * Webservice cung cấp 2 phương thức gọi charge
     * - Gọi charge qua PROXY tập trung và lưu log vào DB
     * - Gọi charge qua CCGW Vinaphone và lưu log vào DB
     *
     * @author      dev@nguyenanhung.com
     * @copyright   Hung Nguyen <dev@nguyenanhung.com>
     * @package     Charging
     * @package     Base Vinaphone
     * @link        /web/v1/charging.html
     * @version     1.1.0
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
            $logger->info('|======== Begin Charging  ========|');
        }
        // Input Params
        $input_msisdn        = $this->input->get_post('msisdn', true);
        $input_packageName   = $this->input->get_post('packageName', true);
        $input_eventName     = $this->input->get_post('eventName', true); // renew, retry, register, cancel, buy
        $input_price         = $this->input->get_post('price', true);
        $input_originalPrice = $this->input->get_post('originalPrice', true);
        $input_promotion     = $this->input->get_post('promotion', true);
        $input_channel       = $this->input->get_post('channel', true);
        $input_signature     = $this->input->get_post('signature', true);
        $valid_signature     = md5($input_msisdn . $this->_prefix_token . $input_packageName . $this->_prefix_token . $input_eventName . $this->_prefix_token . $input_price . $this->_prefix_token . $input_promotion . $this->_prefix_token . $input_channel . $this->_prefix_token . $this->_private_token);
        $input_params        = array(
            'msisdn' => $input_msisdn,
            'packageName' => $input_packageName,
            'eventName' => $input_eventName,
            'price' => $input_price,
            'originalPrice' => $input_originalPrice,
            'promotion' => $input_promotion,
            'channel' => $input_channel,
            'signature' => $input_signature,
            'valid_signature' => $valid_signature
        );
        if ($this->DEBUG === true)
        {
            if ($getMethod === 'GET')
            {
                $logger->info('Request to URL: ' . current_url() . '?' . http_build_query($input_params));
            }
            else
            {
                $logger->info('Request ' . $getMethod . ' to URL: ' . current_url(), $input_params);
            }
        }
        // Filter
        if ($input_msisdn === null || $input_packageName === null || $input_eventName === null || $input_price === null || $input_channel === null || $input_signature === null)
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
                'valid' => (ENVIRONMENT === 'development') ? $valid_signature : null
            );
        }
        else
        {
            $requestId   = date('YmdHis') . random_string('numeric', 6);
            $serviceName = $this->_charge_proxy['serviceName'];
            $packageName = strtoupper($input_packageName);
            $eventName   = strtolower($input_eventName);
//            $msisdn      = $this->phone_number->format($input_msisdn);
            $msisdn      = $this->phone_number->phone_number_convert($input_msisdn, 'new');
            $channel     = strtoupper($input_channel);
            // filter Event
            if ($eventName == 'renew' || $eventName == 'retry')
            {
                /**
                 * Charge gia hạn
                 * Cần kiểm tra số lần gia hạn thành công trong ngày
                 */
                $check_renew_success_data = array(
                    'serviceName' => $serviceName,
                    'packageName' => $packageName,
                    'msisdn' => $msisdn,
                    'eventName' => array(
                        'renew',
                        'retry'
                    ),
                    'status' => 0,
                    'day' => date('Ymd')
                );
                $check_renew_success      = $this->db_charge_log_model->check_log_today($check_renew_success_data);
                if ($check_renew_success > 0)
                {
                    $response = array(
                        'result' => 5,
                        'desc' => 'Đã phát hiện giao dịch gia hạn thành công trong ngày trước đó.',
                        'details' => array(
                            'eventName' => $eventName
                        )
                    );
                }
                else
                {
                    if ($this->useProxy === true)
                    {
                        // Gọi qua PROXY Charging
                        $this->td_proxy_vina_charge->setApi();
                        $this->td_proxy_vina_charge->setServiceName($serviceName);
                        $this->td_proxy_vina_charge->setSecretKey($this->_charge_proxy['secret']);
                        // Request
                        $getRequest   = $this->td_proxy_vina_charge->renew($requestId, $msisdn, $packageName, $input_price, $input_promotion, $channel);
                        $chargeResult = json_decode(trim($getRequest));
                        if ($chargeResult === null)
                        {
                            $response = array(
                                'result' => 500,
                                'errorid' => 1,
                                'desc' => 'Error',
                                'eventName' => $eventName,
                                'amount' => 0,
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                        else
                        {
                            if ($chargeResult->Result == 0)
                            {
                                $response = array(
                                    'result' => 0,
                                    'errorid' => 0,
                                    'desc' => 'Success',
                                    'eventName' => $eventName,
                                    'amount' => $input_price,
                                    'details' => array(
                                        'useProxy' => $this->useProxy,
                                        'getRequest' => $getRequest
                                    )
                                );
                            }
                            else
                            {
                                $response = array(
                                    'result' => 1,
                                    'errorid' => 1,
                                    'desc' => 'Failed',
                                    'eventName' => $eventName,
                                    'amount' => 0,
                                    'details' => array(
                                        'useProxy' => $this->useProxy,
                                        'getRequest' => $getRequest
                                    )
                                );
                            }
                        }
                    }
                    else
                    {
                        // Gọi qua Vinaphone CCGW
                        $getRequest = $this->vinaphone_ccgw->renew($requestId, $msisdn, $packageName, $input_price, $input_promotion, $channel);
                        if (isset($getRequest['Status']) && $getRequest['Status'] == self::CCGW_STATUS_SUCCESS)
                        {
                            // Charge cước thành công
                            $response = array(
                                'result' => 0,
                                'errorid' => 0,
                                'desc' => 'Success',
                                'eventName' => $eventName,
                                'amount' => $input_price,
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                        else
                        {
                            // Không charge được cước
                            $response = array(
                                'result' => 500,
                                'errorid' => 1,
                                'desc' => 'Failed',
                                'eventName' => $eventName,
                                'amount' => 0,
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                    }
                    // Cập nhật log charge
                    $log_data = array(
                        'requestId' => $requestId,
                        'serviceName' => $serviceName,
                        'packageName' => $packageName,
                        'msisdn' => $msisdn,
                        'price' => $input_price,
                        'amount' => $response['amount'],
                        'originalPrice' => $input_price,
                        'eventName' => $response['eventName'],
                        'channel' => $channel,
                        'promotion' => $input_promotion,
                        'status' => $response['errorid'],
                        'response' => (is_array($getRequest)) ? json_encode($getRequest) : $getRequest,
                        'day' => date('Ymd'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'logs' => json_encode($response)
                    );
                    $log_id   = $this->db_charge_log_model->add($log_data);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('|----> Call Renew / Retry <----|');
                        if (is_array($getRequest))
                        {
                            $logger->info('Request Charge Result ', $getRequest);
                        }
                        else
                        {
                            $logger->info('Request Charge Result ' . $getRequest);
                        }
                        $logger->info('Log Charge Data ', $log_data);
                        $logger->info('Log Charge ID ' . $log_id);
                    }
                }
            }
            elseif ($eventName == 'register' || $eventName == 'reg')
            {
                // Đăng ký
                if ($this->useProxy === true)
                {
                    // Gọi qua Proxy
                    $this->td_proxy_vina_charge->setApi();
                    $this->td_proxy_vina_charge->setServiceName($serviceName);
                    $this->td_proxy_vina_charge->setSecretKey($this->_charge_proxy['secret']);
                    // Request
                    $getRequest   = $this->td_proxy_vina_charge->register($requestId, $msisdn, $packageName, $input_price, $input_promotion, $channel);
                    $chargeResult = json_decode(trim($getRequest));
                    if ($chargeResult === null)
                    {
                        $response = array(
                            'result' => 500,
                            'errorid' => 1,
                            'desc' => 'Error',
                            'eventName' => 'register',
                            'amount' => 0,
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                    else
                    {
                        if ($chargeResult->Result == 0)
                        {
                            $response = array(
                                'result' => 0,
                                'errorid' => 0,
                                'desc' => 'Success',
                                'eventName' => 'register',
                                'amount' => $input_price,
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                        else
                        {
                            $response = array(
                                'result' => 1,
                                'errorid' => 1,
                                'desc' => 'Failed',
                                'eventName' => 'register',
                                'amount' => 0,
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                    }
                }
                else
                {
                    // Ko sử dụng Proxy, gọi thằng qua Vinaphone CCGW
                    $getRequest = $this->vinaphone_ccgw->register($requestId, $msisdn, $packageName, $input_price, $input_promotion, $channel);
                    if (isset($getRequest['Status']) && $getRequest['Status'] == self::CCGW_STATUS_SUCCESS)
                    {
                        // Charge cước thành công
                        $response = array(
                            'result' => 0,
                            'errorid' => 0,
                            'desc' => 'Success',
                            'eventName' => 'register',
                            'amount' => $input_price,
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                    else
                    {
                        // Không charge được cước
                        $response = array(
                            'result' => 500,
                            'errorid' => 1,
                            'desc' => 'Failed',
                            'eventName' => 'register',
                            'amount' => 0,
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                }
                // Cập nhật log charge
                $log_data = array(
                    'requestId' => $requestId,
                    'serviceName' => $serviceName,
                    'packageName' => $packageName,
                    'msisdn' => $msisdn,
                    'price' => $input_price,
                    'amount' => $response['amount'],
                    'originalPrice' => $input_price,
                    'eventName' => $response['eventName'],
                    'channel' => $channel,
                    'promotion' => $input_promotion,
                    'status' => $response['errorid'],
                    'response' => (is_array($getRequest)) ? json_encode($getRequest) : $getRequest,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'logs' => json_encode($response)
                );
                $log_id   = $this->db_charge_log_model->add($log_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Call Register <----|');
                    if (is_array($getRequest))
                    {
                        $logger->info('Request Charge Result ', $getRequest);
                    }
                    else
                    {
                        $logger->info('Request Charge Result ' . $getRequest);
                    }
                    $logger->info('Log Charge Data ', $log_data);
                    $logger->info('Log Charge ID ' . $log_id);
                }
            }
            elseif ($eventName == 'cancel' || $eventName == 'unreg')
            {
                // Gọi Hủy
                // Request
                if ($this->useProxy === true)
                {
                    // Sử dụng Proxy, gọi qua Libraries: Td_proxy_vina_charge
                    $this->td_proxy_vina_charge->setApi();
                    $this->td_proxy_vina_charge->setServiceName($serviceName);
                    $this->td_proxy_vina_charge->setSecretKey($this->_charge_proxy['secret']);
                    $getRequest   = $this->td_proxy_vina_charge->cancel($requestId, $msisdn, $packageName, $channel);
                    $chargeResult = json_decode(trim($getRequest));
                    if ($chargeResult === null)
                    {
                        $response = array(
                            'result' => 500,
                            'errorid' => 1,
                            'desc' => 'Error',
                            'eventName' => 'cancel',
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                    else
                    {
                        if ($chargeResult->Result == 0)
                        {
                            $response = array(
                                'result' => 0,
                                'errorid' => 0,
                                'desc' => 'Success',
                                'eventName' => 'cancel',
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                        else
                        {
                            $response = array(
                                'result' => 1,
                                'errorid' => 1,
                                'desc' => 'Failed',
                                'eventName' => 'cancel',
                                'details' => array(
                                    'useProxy' => $this->useProxy,
                                    'getRequest' => $getRequest
                                )
                            );
                        }
                    }
                }
                else
                {
                    // Ko sử dụng Proxy, gọi thằng qua Vinaphone CCGW
                    $getRequest = $this->vinaphone_ccgw->cancel($requestId, $msisdn, $packageName, $channel);
                    if (isset($getRequest['Status']) && $getRequest['Status'] == self::CCGW_STATUS_SUCCESS)
                    {
                        // Charge cước thành công
                        $response = array(
                            'result' => 0,
                            'errorid' => 0,
                            'desc' => 'Success',
                            'eventName' => 'cancel',
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                    else
                    {
                        // Không charge được cước
                        $response = array(
                            'result' => 500,
                            'errorid' => 1,
                            'desc' => 'Failed',
                            'eventName' => 'cancel',
                            'details' => array(
                                'useProxy' => $this->useProxy,
                                'getRequest' => $getRequest
                            )
                        );
                    }
                }
                // Cập nhật log charge
                $log_data = array(
                    'requestId' => $requestId,
                    'serviceName' => $serviceName,
                    'packageName' => $packageName,
                    'msisdn' => $msisdn,
                    'price' => 0,
                    'amount' => 0,
                    'originalPrice' => 0,
                    'eventName' => $response['eventName'],
                    'channel' => $channel,
                    'promotion' => 1,
                    'status' => $response['errorid'],
                    'response' => (is_array($getRequest)) ? json_encode($getRequest) : $getRequest,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'logs' => json_encode($response)
                );
                $log_id   = $this->db_charge_log_model->add($log_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Call Cancel <----|');
                    if (is_array($getRequest))
                    {
                        $logger->info('Request Charge Result ', $getRequest);
                    }
                    else
                    {
                        $logger->info('Request Charge Result ' . $getRequest);
                    }
                    $logger->info('Log Charge Data ', $log_data);
                    $logger->info('Log Charge ID ' . $log_id);
                }
            }
            elseif ($eventName == 'buy' || $eventName == 'buy_content' || $eventName == 'download')
            {
                // Mua hoặc tải lẻ nội dung
                // Kênh này hiện Proxy chưa hỗ trợ phương thức gọi charge
                $getRequest = $this->vinaphone_ccgw->buy_content($requestId, $msisdn, $packageName, $input_price, $input_promotion, $channel);
                if (isset($getRequest['Status']) && $getRequest['Status'] == self::CCGW_STATUS_SUCCESS)
                {
                    // Charge cước thành công
                    $response  = array(
                        'result' => 0,
                        'errorid' => 0,
                        'desc' => 'Success',
                        'eventName' => $eventName,
                        'details' => array(
                            'useProxy' => false,
                            'getRequest' => $getRequest
                        )
                    );
                    $log_trans = array(
                        'price' => $input_price,
                        'amount' => $input_price,
                        'originalPrice' => $input_originalPrice
                    );
                }
                else
                {
                    // Không charge được cước
                    $response  = array(
                        'result' => 500,
                        'errorid' => 1,
                        'desc' => 'Failed',
                        'eventName' => $eventName,
                        'details' => array(
                            'useProxy' => false,
                            'getRequest' => $getRequest
                        )
                    );
                    $log_trans = array(
                        'price' => $input_price,
                        'amount' => 0,
                        'originalPrice' => $input_originalPrice
                    );
                }
                // Cập nhật log charge
                $log_data = array(
                    'requestId' => $requestId,
                    'serviceName' => $serviceName,
                    'packageName' => $packageName,
                    'msisdn' => $msisdn,
                    'price' => $log_trans['price'],
                    'amount' => $log_trans['amount'],
                    'originalPrice' => $log_trans['originalPrice'],
                    'eventName' => $response['eventName'],
                    'channel' => $channel,
                    'promotion' => 0,
                    'status' => $response['errorid'],
                    'response' => (is_array($getRequest)) ? json_encode($getRequest) : $getRequest,
                    'day' => date('Ymd'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'logs' => json_encode($response)
                );
                $log_id   = $this->db_charge_log_model->add($log_data);
                if ($this->DEBUG === true)
                {
                    $logger->info('|----> Call Buy Content <----|');
                    if (is_array($getRequest))
                    {
                        $logger->info('Request Charge Result ', $getRequest);
                    }
                    else
                    {
                        $logger->info('Request Charge Result ' . $getRequest);
                    }
                    $logger->info('Log Charge Data ', $log_data);
                    $logger->info('Log Charge ID ' . $log_id);
                }
            }
            else
            {
                $response = array(
                    'result' => 4,
                    'desc' => 'Tham số event không hợp lệ hoặc chưa được khai báo.',
                    'details' => array(
                        'eventName' => $eventName
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
            $decodeResp       = json_decode(trim($response));
            $set_content_type = ($decodeResp === null) ? 'text/plain' : 'application/json';
            $set_output       = $response;
        }
        $this->output->set_content_type($set_content_type)->set_output($set_output)->_display();
        // Exit
        exit();
    }
    /**
     * Charging destructor.
     */
    public function __destruct()
    {
        $this->db_charge_log_model->close();
        log_message('debug', 'Webservice Charging - Close DB Connection!');
    }
}
/* End of file Charging.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Webservices-Charging/controllers/Charging.php */
