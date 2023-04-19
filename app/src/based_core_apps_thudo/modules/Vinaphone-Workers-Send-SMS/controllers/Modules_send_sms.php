<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/26/2017
 * Time: 1:40 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Modules_send_sms extends MX_Controller
{
    protected $syncTrans;
    protected $DEBUG;
    protected $logger_path;
    protected $logger_file;
    protected $serviceId;
    protected $subStatus;
    /**
     * Modules_send_sms.php constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('requests');
        // Load vũ khí
        $this->load->model(array(
            'Vina_Services/db_config_model',
            'Vina_Services/db_sms_queues_model'
        ));
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(44);
        $this->mantis->setUsername('hungna');
        $this->config->load('config_vinaphone_services');
        // Configures Send SMS
        $this->webservices = config_item('vinaphone_web_services');
        $this->syncSendsms = $this->webservices['sendSms'];
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_worker_services']['sendsms']['debug'];
        $this->logger_path = $this->mono['vina_worker_services']['sendsms']['logger_path'];
        $this->logger_file = $this->mono['vina_worker_services']['sendsms']['logger_file'];
        $this->logger_name = $this->mono['vina_worker_services']['sendsms']['logger_name'];
    }
    /**
     * Worker send sms
     *
     * @link    /workers/v1/sendSms
     * @command php index.php workers v1 sendSms
     */
    public function index()
    {
        $configKeyLastIdFromWorkers = "last_id_queues";
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
                $logger->info('|======== Begin Workers Push SMS ========|');
            }
            // Welcome
            echo "|===============================================|\n";
            echo "---> He thong gui sms cho doi tac. <---\n";
            echo "o0o\n";
            echo "Powered by hungna@gviet.vn\n\n";
            $lastIdFromWorkers = $this->db_config_model->get_value($configKeyLastIdFromWorkers, 'id', 'value');
            /**
             * Check Workers
             */
            if ($lastIdFromWorkers === null)
            {
                $response = array(
                    'Result' => 2,
                    'Desc' => 'Worker LastId is NULL.'
                );
                echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
            }
            else
            {
                $lastFromQueues = $this->db_sms_queues_model->getLastId();
                /**
                 * Check Queues
                 */
                if ($lastFromQueues === null)
                {
                    $response = array(
                        'Result' => 3,
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
                            'Result' => 4,
                            'Desc' => 'Worker is Update.'
                        );
                        echo date('Y-m-d H:i:s') . " -> " . $response['Desc'] . "\n";
                    }
                    else
                    {
                        $listDatafromQueues = $this->db_sms_queues_model->getQueues($lastIdFromWorkers, $lastIdFromQueues);
                        /**
                         * Check Data from Queues
                         */
                        if (empty($listDatafromQueues))
                        {
                            $response = array(
                                'Result' => 5,
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
                             * Tiến hành chạy vòng lặp và đẩy dữ liệu về Queues
                             */
                            $msg              = '';
                            $syncUrl          = private_api_url($this->syncSendsms['url']);
                            $syncPrivateToken = $this->syncSendsms['token'];
                            $syncPrefixSignal = $this->syncSendsms['prefix'];
                            foreach ($listDatafromQueues as $key => $item)
                            {
                                $itemData = json_decode($item->data);
                                if ($itemData === null)
                                {
                                    $msg .= $item->id . ' -> Data is Null.';
                                }
                                else
                                {
                                    $syncSignature   = md5($itemData->msisdn . $syncPrefixSignal . $itemData->mt . $syncPrefixSignal . $syncPrivateToken);
                                    $syncDataRequest = array(
                                        'msisdn' => $itemData->msisdn,
                                        'mo' => $itemData->mo,
                                        'mt' => $itemData->mt,
                                        'note' => $itemData->note,
                                        'sub_code' => $itemData->sub_code,
                                        'send_method' => '',
                                        'signature' => $syncSignature
                                    );
                                    if ($this->DEBUG === true)
                                    {
                                        $logger->info('syncSendsms Config: ', $this->syncSendsms);
                                        $logger->info('Data Requests Sync: ', $syncDataRequest);
                                    }
                                    $syncRequest = $this->requests->sendRequest($syncUrl, $syncDataRequest);
                                    if ($this->DEBUG === true)
                                    {
                                        $logger->info('Response from Requests ' . $syncRequest);
                                    }
                                    $decodeRequest = json_decode(trim($syncRequest));
                                    if ($decodeRequest !== null && isset($decodeRequest->ec))
                                    {
                                        if ($decodeRequest->ec == 0)
                                        {
                                            // Xử lý thành công
                                            $reqStatus = 1;
                                        }
                                        else
                                        {
                                            $reqStatus = 2;
                                        }
                                        // Update dữ liệu lại cho queues
                                        $updateData              = array(
                                            'status' => $reqStatus
                                        );
                                        $update_sms_queue_result = $this->db_sms_queues_model->update($item->id, $updateData);
                                        if ($this->DEBUG === true)
                                        {
                                            $logger->info('Update SMS ID: ' . $item->id);
                                            $logger->info('Update SMS Data: ', $updateData);
                                            $logger->info('Update SMS Result: ' . $update_sms_queue_result);
                                        }
                                    }
                                    $msg .= $syncRequest;
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
            $push        = $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers send sms dịch vụ PhongThuyViet - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
    /**
     * Modules_send_sms destructor.
     */
    public function __destruct()
    {
        $this->db_config_model->close();
        $this->db_sms_queues_model->close();
        log_message('debug', 'Modules Worker Send SMS - Close DB Connection!');
    }
}
/* End of file Modules_send_sms.php.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Workers-Send-SMS/controllers/Modules_send_sms.php.php */
