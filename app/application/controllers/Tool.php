<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-01-05
 * Time: 00:23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\MyDebug\Manager\File;
use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Commands\CommandCreateTable;

/**
 * Class Tool
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 * @property mixed cache
 */
class Tool extends CI_Controller
{
    const TIME_LOG = 2; // chỉ lưu trữ log trong 7 ngày gần nhất
    /** @var mixed Auth config */
    private $auth;

    /**
     * Tool constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_admin');
        $this->auth = config_item('authentication');
    }

    /**
     * Function sdk
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-14 14:13
     * @link  : /tool/sdk
     *
     */
    public function sdk()
    {
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->config('config_vina_sdk');
            $config   = config_item('vina_sdk_config');
            $response = array(
                'serviceId'        => $this->auth['serviceId'],
                'VinaPhoneSDKData' => $config
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }

    /**
     * Function serviceInfo
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-05-31 12:01
     *
     */
    public function serviceInfo()
    {
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->config('config_vina_sdk');
            $config   = config_item('vina_sdk_config');
            $response = array(
                'serviceId'     => $this->auth['serviceId'],
                'serviceConfig' => $config['SERVICES']
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }

    /**
     * Function optionsInfo
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 11:06
     *
     */
    public function optionsInfo()
    {
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->config('config_vina_sdk');
            $config   = config_item('vina_sdk_config');
            $response = array(
                'serviceId'     => $this->auth['serviceId'],
                'optionsConfig' => $config['OPTIONS']
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }

    /**
     * Function cleanCache
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-05 00:28
     * @link  : /tool/cleanCache
     */
    public function cleanCache()
    {
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        $type     = $this->input->get_post('type', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => EXIT_USER_INPUT,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->driver('cache', array(
                'adapter' => 'apc',
                'backup'  => 'file'
            ));
            if ($type === 'info') {
                $response = array(
                    'result'    => EXIT_SUCCESS,
                    'serviceId' => $this->auth['serviceId'],
                    'desc'      => 'Lấy thông tin Cache',
                    'details'   => array(
                        'info' => $this->cache->cache_info()
                    )
                );
            } else {
                $response = array(
                    'result'    => EXIT_SUCCESS,
                    'serviceId' => $this->auth['serviceId'],
                    'desc'      => 'Xóa Cache',
                    'details'   => array(
                        'info'  => $this->cache->cache_info(),
                        'clean' => $this->cache->clean()
                    )
                );
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }

    /**
     * Function cleanLog
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-05 00:25
     * @link  : /tool/cleanLog
     */
    public function cleanLog()
    {
        if (is_cli()) {
            try {
                $file = new File();
                $file->setInclude(['*.log', '*.txt', 'log-*.php']);
                $response = array(
                    'status'    => 'OK',
                    'serviceId' => $this->auth['serviceId'],
                    'time'      => date('Y-m-d H:i:s'),
                    'data'      => array(
                        'SctvPlusAPI-logs'      => $file->cleanLog(realpath(__DIR__ . '/../logs'), self::TIME_LOG),
                        'SctvPlusAPI-logs-data' => $file->cleanLog(realpath(__DIR__ . '/../logs-data'), self::TIME_LOG),
                    )
                );
                log_message('debug', 'Clean Log Result: ' . json_encode($response));
                $output = defined('JSON_PRETTY_PRINT') ? json_encode($response, JSON_PRETTY_PRINT) : json_encode($response);
                $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output($output . PHP_EOL)->_display();
                exit;
            }
            catch (Exception $e) {
                $message = 'Code: ' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
                log_message('error', $message);
            }
        } else {
            $dataConnect = array(
                'method'          => $this->input->method(TRUE),
                'ip_address'      => $this->input->ip_address(),
                'user_agent'      => $this->input->user_agent(TRUE),
                'request_headers' => $this->input->request_headers(TRUE)
            );
            log_message('error', json_encode($dataConnect));
            show_404();
        }
    }

    /**
     * Function createTable
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:31
     *
     */
    public function createTable()
    {
        if (is_cli()) {
            try {
                $this->load->config('config_vina_sdk');
                $sdkConfig = config_item('vina_sdk_config');
                $command   = new CommandCreateTable($sdkConfig['OPTIONS']);
                $command->setSdkConfig($sdkConfig)->run();
            }
            catch (Exception $e) {
                $message = 'Code: ' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
                log_message('error', $message);
            }
        } else {
            $dataConnect = array(
                'method'          => $this->input->method(TRUE),
                'ip_address'      => $this->input->ip_address(),
                'user_agent'      => $this->input->user_agent(TRUE),
                'request_headers' => $this->input->request_headers(TRUE)
            );
            log_message('error', json_encode($dataConnect));
            show_404();
        }
    }
}
