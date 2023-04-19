<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/26/2017
 * Time: 3:04 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Modules_charging extends MX_Controller
{
    protected $mono;
    protected $DEBUG;
    protected $logger;
    protected $logger_path;
    protected $logger_file;
    protected $logger_name;
    private $_webServices;
    /**
     * Modules_charging constructor.
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
        $this->load->model('Vina_Services/db_subscriber_model');
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(44);
        $this->mantis->setUsername('hungna');
        // Load Config
        $this->config->load('config_vinaphone_services');
        $this->_webServices = config_item('vinaphone_web_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_worker_services']['charging']['debug'];
        $this->logger_path = $this->mono['vina_worker_services']['charging']['logger_path'];
        $this->logger_file = $this->mono['vina_worker_services']['charging']['logger_file'];
        $this->logger_name = $this->mono['vina_worker_services']['charging']['logger_name'];
    }
    /**
     * Module Worker Charge cước dịch vụ hàng ngày
     *
     * @param string $eventName -> Sự kiện gọi charge
     * @param string $inputPackage -> Gói cước gọi charge
     *
     * ---------------- Command Interface
     *
     * php index.php workers v1 charging renew NGAY
     * php index.php workers v1 charging retry NGAY
     *
     */
    public function index($eventName = 'Renew', $inputPackage = '')
    {
        if (is_cli())
        {
            $this->output->set_status_header(200)->set_content_type('text/plain');
            $getPackage   = strtoupper($inputPackage);
            $getEventName = strtolower($eventName);
            $getMethod    = $this->input->method(true);
            if (empty($inputPackage))
            {
                $prefix_stream = '';
            }
            else
            {
                $prefix_stream = $getPackage . '/';
            }
            // create a log channel
            $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
            $stream    = new StreamHandler($this->logger_path . $prefix_stream . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
            $stream->setFormatter($formatter);
            $logger = new Logger($this->logger_name);
            $logger->pushHandler($stream);
            if ($this->DEBUG === true)
            {
                $this->benchmark->mark('code_start');
                $logger->info('|======== Begin Workers Charging ========|');
            }
            // Welcome
            echo "|===============================================|\n";
            echo "---> He thong charge cuoc dich vu LOVETV - VINA - TMS <---\n";
            echo "o0o\n";
            echo "Powered by hungna@gviet.vn\n\n";
            /**
             * Lấy tập thuê bao mang đi charge cước
             */
            $selectExpireTime = date('Y-m-d H:i:s');
            if (empty($inputPackage))
            {
                // Lấy toàn bộ Package đem đi charge.
                echo "Lay toan bo tap thue bao dem di charge.\n";
                $arrToCheck = array(
                    'status' => 1
                );
            }
            else
            {
                echo "Lay toan bo tap thue bao cua goi $getPackage dem di charge.\n";
                $arrToCheck = array(
                    'packageId' => $getPackage,
                    'status' => 1
                );
            }
            $listMsisdnToCharge  = $this->db_subscriber_model->selectMsisdnToRenew($selectExpireTime, $arrToCheck);
            $countMsisdnToCharge = count($listMsisdnToCharge);
            if ($this->DEBUG === true)
            {
                $logger->info("Tong so thue bao dem di Charge: " . $countMsisdnToCharge);
            }
            echo "Tong so thue bao dem di Charge: " . $countMsisdnToCharge . "\n";
            /**
             * Tiến hành gọi charge cước
             */
            if ($countMsisdnToCharge > 0)
            {
                // Lấy thông tin webservice Renewal
                $renewal_url     = private_api_url($this->_webServices['renewal']['url']);
                $renewal_token   = $this->_webServices['renewal']['token'];
                $renewal_prefix  = $this->_webServices['renewal']['prefix'];
                $renewal_channel = 'CRONJOB';
                // Gọi vòng lặp
                foreach ($listMsisdnToCharge as $index => $item)
                {
                    $renewal_params = array(
                        'msisdn' => $item->msisdn,
                        'packageName' => $item->packageId,
                        'eventName' => $getEventName,
                        'price' => $item->price,
                        'channel' => $renewal_channel,
                        'signature' => md5($item->msisdn . $renewal_prefix . $item->packageId . $renewal_prefix . $getEventName . $renewal_prefix . $item->price . $renewal_prefix . $renewal_channel . $renewal_prefix . $renewal_token)
                    );
                    $request_charge = $this->requests->sendRequest($renewal_url, $renewal_params);
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Request charge to Url ' . $renewal_url . ' ', $renewal_params);
                        $logger->info('Response from Charge ' . json_encode($request_charge));
                    }
                    $parse_request = json_decode(trim($request_charge));
                    if ($parse_request === null)
                    {
                        $result = 'Failed';
                        $msg    = $request_charge;
                    }
                    else
                    {
                        if (isset($parse_request->result) && $parse_request->result == 0)
                        {
                            $result = 'Success';
                            $msg    = 'Success!';
                        }
                        else
                        {
                            $result = 'Failed';
                            $msg    = 'Failed';
                        }
                    }
                    $msg_charge = '(' . $index . '/' . $countMsisdnToCharge . ') -> ' . $getEventName . ' -> ' . $item->msisdn . '|' . $item->packageId . '|' . $item->price . ' --> ' . $result . '|' . $msg;
                    if ($this->DEBUG === true)
                    {
                        $logger->info($msg_charge);
                    }
                    echo date('Y-m-d H:i:s') . '. ' . $msg_charge . "\n";
                    //
                    unset($index);
                    unset($item);
                }
                echo "Tong so thue bao da charge cuoc: " . $countMsisdnToCharge . "\n";
            }
            else
            {
                if ($this->DEBUG === true)
                {
                    $logger->info("Khong ton tai thue bao den thoi han charge cuoc.");
                }
                echo "Khong ton tai thue bao den thoi han charge cuoc.";
            }
            unset($countMsisdnToCharge);
            unset($listMsisdnToCharge);
            // End program
            if ($this->DEBUG === true)
            {
                $this->benchmark->mark('code_end');
                // elapsed_time
                $elapsed_time = $this->benchmark->elapsed_time('code_start', 'code_end');
                $logger->info('Thoi gian thuc thi script: ' . $elapsed_time);
            }
            echo "\n---> Ket thuc chuong trinh! <---\n";
            // Close DB
            exit();
        }
        else
        {
            $dataConnect = array(
                'method' => $this->input->method(true),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(true),
                'request_headers' => $this->input->request_headers(true)
            );
            $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers charge cước dịch vụ Phong Thủy Viêt - Vina - TMS - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
    /**
     * Renewal destructor.
     */
    public function __destruct()
    {
        $this->db_subscriber_model->close();
        log_message('debug', 'Modules Charging - Close DB Connection!');
    }
}
/* End of file Modules_charging.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Workers-daily-Charging/controllers/Modules_charging.php */
