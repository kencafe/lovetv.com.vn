<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/31/2017
 * Time: 4:17 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/../../../libraries/Monitor/Mantis.php';

use nguyenanhung\MyDebug\Manager\File as FileManager;

/**
 * Class Cronjobs
 *
 * @property object $config
 * @property object $input
 * @property object $output
 */
class Cronjobs extends MX_Controller
{
    const TIME_LOG = 3; // chỉ lưu trữ log trong 7 ngày gần nhất
    public    $mono;
    public    $DEBUG;
    public    $logger_path;
    public    $logger_file;
    public    $logger_name;
    protected $auth;
    protected $mantis;

    /**
     * Cronjobs constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'string']);
        $this->load->library('phone_number');
        $this->load->config('admin_config');
        $this->auth = config_item('authentication');
        // load Library mantis
        $this->mantis = new Mantis();
        // Log Configures
        $this->load->config('config_monolog');
        $this->mono        = config_item('monologServicesConfigures');
        $this->DEBUG       = $this->mono['Utilities']['Cronjob']['debug'];
        $this->logger_path = $this->mono['Utilities']['Cronjob']['logger_path'];
        $this->logger_file = $this->mono['Utilities']['Cronjob']['logger_file'];
        $this->logger_name = $this->mono['Utilities']['Cronjob']['logger_name'];
    }

    /**
     * Function clean_log
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 10/4/18 11:37
     *
     * @command php /web/www/html/vnm.lovetv.com.vn/public_html/index.php admin cronjob v1 clean-log
     * @command 0 23 * * * php /web/www/html/vnm.lovetv.com.vn/public_html/index.php admin cronjob v1 clean-log >/dev/null 2>&1
     */
    public function clean_log()
    {
        if (is_cli()) {
            $file = new FileManager();
            $file->setInclude(['*.log', '*.txt', 'log-*.php']);
            $response = array(
                'status' => 'OK',
                'time'   => date('Y-m-d H:i:s'),
                'data'   => array(
                    'logs'      => $file->cleanLog(APPPATH . 'logs', self::TIME_LOG),
                    'logs-data' => $file->cleanLog(APPPATH . 'logs-data', self::TIME_LOG)
                )
            );
            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL)
                ->_display();
            exit;
        } else {
            $dataConnect = array(
                'method'          => $this->input->method(TRUE),
                'ip_address'      => $this->input->ip_address(),
                'user_agent'      => $this->input->user_agent(TRUE),
                'request_headers' => $this->input->request_headers(TRUE)
            );
            $this->mantis->push('[Warning] - Phát hiện truy cập trái phép!', 'Phát hiện truy cập trái phép vào workers clean_log - URL: ' . current_url() . ' - Data: ' . json_encode($dataConnect));
            show_404();
        }
    }
}
