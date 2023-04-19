<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/26/2017
 * Time: 11:21 AM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Modules_sync_transaction extends MX_Controller
{
    protected $syncTrans;
    protected $DEBUG;
    protected $logger_path;
    protected $logger_file;
    protected $serviceId;
    protected $subStatus;
    /**
     * Modules_sync_transaction constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url'
        ));
        $this->load->library(array(
            'requests'
        ));
        // Load vũ khí
        $this->load->model(array(
            'Vina_Services/db_config_model',
            'Vina_Services/db_queues_model'
        ));
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(44);
        $this->mantis->setUsername('hungna');
        $this->config->load('config_vinaphone_services');
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_worker_services']['transaction']['debug'];
        $this->logger_path = $this->mono['vina_worker_services']['transaction']['logger_path'];
        $this->logger_file = $this->mono['vina_worker_services']['transaction']['logger_file'];
        $this->logger_name = $this->mono['vina_worker_services']['transaction']['logger_name'];
        // domain kết nối API Business
        $this->domain_api  = "http://123.30.235.188:1381/";
    }
    /**
     * Worker đồng bộ transactions
     *
     * @link    /workers/v1/sync-transaction
     * @command php index.php workers v1 sync-transaction
     */
    public function index()
    {
        $configKeyLastIdFromWorkers = "last_id_sync_transaction";
        if (is_cli())
        {
            $this->output->set_status_header(200)->set_content_type('text/plain');
            // create a log channel
            $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
            $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
            $stream->setFormatter($formatter);
            $logger = new Logger($this->logger_name);
            $logger->pushHandler($stream);
            if ($this->DEBUG === true)
            {
                $this->benchmark->mark('code_start');
                $logger->info('|======== Begin Workers Push Transactions ========|');
            }
            // Welcome
            echo "|===============================================|\n";
            echo "---> He thong dong bo transaction cho doi tac. <---\n";
            echo "o0o\n";
            echo "Powered by hungna@gviet.vn\n\n";
            $lastIdFromWorkers = $this->db_config_model->get_value($configKeyLastIdFromWorkers, 'id', 'value');
            /**
             * Check Workers
             */
            if ($lastIdFromWorkers === null)
            {
                $response = array(
                    'Result' => 1,
                    'Desc' => 'Worker is Null.'
                );
                echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
            }
            else
            {
                $lastFromQueues = $this->db_queues_model->getLastId();
                /**
                 * Check Queues
                 */
                if ($lastFromQueues === null)
                {
                    $response = array(
                        'Result' => 2,
                        'Desc' => 'Queues is Empty.'
                    );
                    echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
                }
                else
                {
                    $lastIdFromQueues = ($lastFromQueues === null) ? 0 : $lastFromQueues;
                    if ($this->DEBUG === true)
                    {
                        $logger->info('Last ID from Worker: ' . $lastIdFromWorkers);
                        $logger->info('Last ID from Queues: ' . $lastIdFromQueues);
                    }
                    echo date('Y-m-d H:i:s') . " -> LastID from Worker: " . $lastIdFromWorkers . "\n";
                    echo date('Y-m-d H:i:s') . " -> LastID from Queues: " . $lastIdFromQueues . "\n";
                    /**
                     * Check ID
                     */
                    if ($lastIdFromQueues <= $lastIdFromWorkers)
                    {
                        $response = array(
                            'Result' => 3,
                            'Desc' => 'Worker is Update.'
                        );
                        echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
                    }
                    else
                    {
                        $listDatafromQueues = $this->db_queues_model->getQueues($lastIdFromWorkers, $lastIdFromQueues);
                        /**
                         * Check Data from Queues
                         */
                        if (empty($listDatafromQueues))
                        {
                            $response = array(
                                'Result' => 4,
                                'Desc' => 'Queue Result is Empty.'
                            );
                            echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
                        }
                        else
                        {
                            $countQueues = count($listDatafromQueues);
                            if ($this->DEBUG === true)
                            {
                                $logger->info('Total Log in Queues: ' . $countQueues);
                            }
                            echo date('Y-m-d H:i:s') . " -> Total Log in Queues: " . $countQueues . "\n";
                            /**
                             * Update Last ID Worker to Config
                             */
                            $dataWorkerUpdateConfig         = array(
                                'value' => $lastIdFromQueues
                            );
                            $updateLastIdfromQueuestoWorker = $this->db_config_model->update($configKeyLastIdFromWorkers, $dataWorkerUpdateConfig);
                            if ($this->DEBUG === true)
                            {
                                $logger->info('Update Worker Last ID: ' . $updateLastIdfromQueuestoWorker, $dataWorkerUpdateConfig);
                            }
                            /**
                             * Phân tích queue và đẩy dữ liệu về luồng thích hợp
                             */
                            $msg = '';
                            foreach ($listDatafromQueues as $key => $queue)
                            {
                                $router    = strtolower($queue->route);
                                $queueData = json_decode($queue->data);
                                if ($queueData === null)
                                {
                                    // Không có dữ liệu
                                    if ($this->DEBUG === true)
                                    {
                                        $logger->info('Queue ID ' . $queue->id . ' khong co du lieu Data.');
                                    }
                                    $msg .= 'Queue ID ' . $queue->id . ' khong co du lieu Data.';
                                }
                                else
                                {
                                    switch ($router)
                                    {
                                        case "moregister":
                                            $subscribeApi   = $this->domain_api . 'api/v1/logs-register/received';
                                            $subscribeToken = 'f59W5Uya7uQCUC794rwYbe96';
                                            if (isset($queueData->status))
                                            {
                                                $useStatus = $queueData->status;
                                            }
                                            else
                                            {
                                                $useStatus = 1;
                                            }
                                            $subscribeParams = array(
                                                'service' => $queue->service_id,
                                                'msisdn' => $queueData->phone,
                                                'package' => $queueData->package,
                                                'event' => $queueData->event,
                                                'message' => $queueData->package,
                                                'note' => $queueData->note,
                                                'password' => $queueData->password,
                                                'type' => $queueData->type,
                                                'application' => $queueData->application,
                                                'channel' => $queueData->channel,
                                                'status' => $useStatus,
                                                'status_charge' => $queueData->status_charge,
                                                'price' => $queueData->price,
                                                'queues_day' => $queue->day,
                                                'queues_created_at' => $queue->created_at,
                                                'signature' => md5($queue->service_id . '|' . $queueData->phone . '|' . $queueData->package . '|' . $queueData->event . '|' . $queueData->type . '|' . $subscribeToken . '|' . $useStatus . '|' . $queueData->note)
                                            );
                                            $request         = $this->requests->sendRequest($subscribeApi, $subscribeParams);
                                            if ($this->DEBUG === true)
                                            {
                                                $logger->info('|===== Request Subscribe =====|');
                                                $logger->info('Send Request ' . $subscribeApi, $subscribeParams);
                                                $logger->info('Response: ' . $request);
                                            }
                                            echo 'Send Request ' . $subscribeApi . '?' . http_build_query($subscribeParams) . "\n";
                                            echo 'Response ' . $request . "\n";
                                            break;
                                        case "mocancel":
                                            $unsubscribeApi   = $this->domain_api . 'api/v1/logs-cancel/received';
                                            $unsubscribeToken = 'nWMAM9DmN45LJueYdMFmyV4R';
                                            if (isset($queueData->status))
                                            {
                                                $useStatus = $queueData->status;
                                            }
                                            else
                                            {
                                                $useStatus = 1;
                                            }
                                            $unsubscribeParams = array(
                                                'service' => $queue->service_id,
                                                'msisdn' => $queueData->msisdn,
                                                'package' => $queueData->package,
                                                'event' => $queueData->event,
                                                'message' => $queueData->message,
                                                'note' => $queueData->note,
                                                'type' => $queueData->type,
                                                'application' => $queueData->application,
                                                'channel' => $queueData->channel,
                                                'status' => $useStatus,
                                                'status_charge' => $queueData->status_charge,
                                                'price' => $queueData->price,
                                                'queues_day' => $queue->day,
                                                'queues_created_at' => $queue->created_at,
                                                'signature' => md5($queue->service_id . '|' . $queueData->msisdn . '|' . $queueData->package . '|' . $queueData->event . '|' . $queueData->type . '|' . $unsubscribeToken . '|' . $useStatus . '|' . $queueData->note)
                                            );
                                            $request           = $this->requests->sendRequest($unsubscribeApi, $unsubscribeParams);
                                            if ($this->DEBUG === true)
                                            {
                                                $logger->info('|===== Request Unsubscribe =====|');
                                                $logger->info('Send Request ' . $unsubscribeApi, $unsubscribeParams);
                                                $logger->info('Response: ' . $request);
                                            }
                                            echo 'Send Request ' . $unsubscribeApi . '?' . http_build_query($unsubscribeParams) . "\n";
                                            echo 'Response ' . $request . "\n";
                                            break;
                                        case "mocharge":
                                            $chargeApi   = $this->domain_api . 'api/v1/logs-charge/received';
                                            $chargeToken = 'kmxmz6UCZHT4ZWB9H2zxN7pV';
                                            if (isset($queueData->status))
                                            {
                                                $useStatus = $queueData->status;
                                            }
                                            else
                                            {
                                                // Không có status anh Kiên mặc định là thành công.
                                                $useStatus = 0;
                                            }
                                            $chargeParams = array(
                                                'service' => $queue->service_id,
                                                'msisdn' => $queueData->phone,
                                                'package' => $queueData->package,
                                                'event' => $queueData->event,
                                                'price' => $queueData->price,
                                                'status' => $useStatus,
                                                'status_charge' => $queueData->status_charge,
                                                'note' => $queueData->note,
                                                'queues_day' => $queue->day,
                                                'queues_created_at' => $queue->created_at,
                                                'signature' => md5($queue->service_id . '|' . $queueData->phone . '|' . $queueData->package . '|' . $queueData->event . '|' . $queueData->price . '|' . $chargeToken . '|' . $useStatus . '|' . $queueData->note)
                                            );
                                            $request      = $this->requests->sendRequest($chargeApi, $chargeParams);
                                            if ($this->DEBUG === true)
                                            {
                                                $logger->info('|===== Request Charge =====|');
                                                $logger->info('Send Request ' . $chargeApi, $chargeParams);
                                                $logger->info('Response: ' . $request);
                                            }
                                            echo 'Send Request ' . $chargeApi . '?' . http_build_query($chargeParams) . "\n";
                                            echo 'Response ' . $request . "\n";
                                            break;
                                        default:
                                            $msg .= 'Exit with Default - QueueID: ' . $queue->id . ', ';
                                    }
                                }
                            }
                            $response = array(
                                'Result' => 0,
                                'Desc' => 'Queue is Update.',
                                'Details' => array(
                                    'msg' => $msg
                                )
                            );
                            echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n" . $response['Details']['msg'] . "\n";
                        }
                    }
                }
            }
            /**
             * Logger
             */
            if ($this->DEBUG === true && isset($response))
            {
                if (is_array($response))
                {
                    $logger->info('Queues Response ', $response);
                }
                else
                {
                    $logger->info('Queues Response ' . json_encode($response));
                }
            }
            // End program
            if ($this->DEBUG === true)
            {
                $this->benchmark->mark('code_end');
                // elapsed_time
                $elapsed_time = $this->benchmark->elapsed_time('code_start', 'code_end');
                $logger->info('Thoi gian thuc thi script: ' . $elapsed_time);
            }
            echo "\n---> Ket thuc chuong trinh! <---\n";
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
            $push        = $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers đồng bộ giao dịch dịch vụ PhongThuyViet - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
    /**
     * Modules_sync_transaction destructor.
     */
    public function __destruct()
    {
        $this->db_config_model->close();
        $this->db_queues_model->close();
        log_message('debug', 'Modules Worker Sync Transaction - Close DB Connection!');
    }
}
/* End of file Modules_sync_transaction.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Workers-Sync-Transactions/controllers/Modules_sync_transaction.php */
