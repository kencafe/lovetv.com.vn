<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 9/29/2017
 * Time: 5:59 AM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Modules_clear_queues extends MX_Controller
{
    protected $syncTrans;
    protected $DEBUG;
    protected $logger_path;
    protected $logger_file;
    protected $serviceId;
    protected $subStatus;
    protected $day;
    /**
     * Modules_clear_queues.php constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('requests');
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
        $this->day = 7; // Số ngày trở về trước xóa queues
        // Monolog Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['vina_worker_services']['clearQueues']['debug'];
        $this->logger_path = $this->mono['vina_worker_services']['clearQueues']['logger_path'];
        $this->logger_file = $this->mono['vina_worker_services']['clearQueues']['logger_file'];
        $this->logger_name = $this->mono['vina_worker_services']['clearQueues']['logger_name'];
    }
    /**
     * Worker clear queues
     *
     * @link    /workers/v1/clear-queues
     * @command php index.php workers v1 clear-queues
     */
    public function index()
    {
        $week = strtotime(date("Y-m-d") . " -" . $this->day . " day");
        $week = strftime("%Y%m%d", $week);
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
                $logger->info('|======== Begin Workers clear Queues ========|');
            }
            // Welcome
            echo "|===============================================|\n";
            echo "---> Clean Queues Transaction System <---\n";
            echo "o0o\n";
            echo "Powered by hungna@gviet.vn\n\n";
            /**
             * Check Workers
             */
            $this->db_queues_model->clearQueuesByTime($week);
            $response = array(
                'Result' => 0,
                'Desc' => 'Clear queues.'
            );
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
            $push        = $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers clear queues dịch vụ PhongThuyViet - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
    /**
     * Modules_clear_queues destructor.
     */
    public function __destruct()
    {
        $this->db_config_model->close();
        $this->db_queues_model->close();
        log_message('debug', 'Modules Worker clear Queues - Close DB Connection!');
    }
}
/* End of file Modules_clear_queues.php.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-Workers-clear-Queues/controllers/Modules_clear_queues.php.php */
