<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/31/2017
 * Time: 4:17 PM
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class Cronjobs extends MX_Controller
{
    public $mono;
    public $DEBUG;
    public $logger_path;
    public $logger_file;
    public $logger_name;
    protected $auth;
    /**
     * Cronjobs constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string'
        ));
        $this->load->library(array(
            'phone_number'
        ));
        // load Libaries mantis
        $this->load->library('Monitor/catch_send_exception', null, 'mantis');
        $this->mantis->setProjectId(44);
        $this->mantis->setUsername('hungna');
        $this->config->load('admin_config');
        $this->auth = config_item('authentication');
        // Log Configures
        $this->config->load('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = true;
        $this->logger_path = APPPATH . 'logs-data/Utilities-for-Administrator/Cronjobs/';
        $this->logger_file = 'Log-' . date('Y-m-d') . '.log';
        $this->logger_name = 'cronjob';
    }
    /**
     * Auto create tables
     *
     * Tự động chạy vào ngày 27 hàng tháng
     *
     * @command php index.php admin cronjob v1 create-tables
     * @command cd /usr/share/nginx/html/private.giaitrivn.net/private_html && php index.php admin cronjob v1 create-tables
     * @command 0 23 27 * * cd /usr/share/nginx/html/private.giaitrivn.net/private_html && php index.php admin cronjob v1 create-tables >/dev/null 2>&1
     */
    public function create_tables()
    {
        if (is_cli())
        {
            // Create Log
            $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
            $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
            $stream->setFormatter($formatter);
            $logger = new Logger($this->logger_name);
            $logger->pushHandler($stream);
            // Begin Module
            $this->load->model('cronjobs_model');
            // Add to month
            $datetime   = new DateTime("+5 days");
            $is_month   = $datetime->format('Y_m');
            // Create table Charge Log
            $charge_log = $this->cronjobs_model->create_table_charge_log($is_month);
            $logger->info('Create tables Charge Log: ' . $charge_log);
            // $charge_log->free_result();
            // Create table SMS History
            $sms_history = $this->cronjobs_model->create_table_sms_history($is_month);
            $logger->info('Create tables SMS History: ' . $sms_history);
            // $sms_history->free_result();
            // Create table Transaction
            $transaction = $this->cronjobs_model->create_table_transaction($is_month);
            $logger->info('Create tables Transaction: ' . $transaction);
            // $transaction->free_result();
            /**
             * Close Database
             */
            $this->cronjobs_model->close();
        }
        else
        {
            $dataConnect = array(
                'method' => $this->input->method(true),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(true),
                'request_headers' => $this->input->request_headers(true)
            );
            $push        = $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers tạo bảng giao dịch - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
}
