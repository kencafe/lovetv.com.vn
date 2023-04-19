<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Commands\CommandQueueClean;

/**
 * Class CommandsQueueClean
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class CommandsQueueClean extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * CommandsQueueClean constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * Function run
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-10 13:42
     *
     */
    public function run()
    {
        if (is_cli()) {
            try {
                $process = new CommandQueueClean($this->sdkConfig);
                $process->setSdkConfig($this->sdkConfig)->run();
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
