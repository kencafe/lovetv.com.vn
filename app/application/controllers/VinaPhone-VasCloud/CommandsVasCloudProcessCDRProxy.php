<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Commands\VasCloudCommandSyncCDRProxy;

/**
 * Class CommandsVasCloudProcessCDRProxy
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class CommandsVasCloudProcessCDRProxy extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * CommandsVasCloudProcessCDRProxy constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * Worker đồng bộ lịch giao dịch đăng ký, hủy, gia hạn
     *
     * Được xây dựng trên chuẩn của Thủ Đô
     * Chi tiết tham khảo file: TÀI LIỆU TRIỂN KHAI VASCLOUD.doc
     *
     * @param null $timeFormat
     *
     * @link    /VasCloud/Commands/CDRProxy/new
     * @link    /VasCloud/Commands/CDRProxy/old
     *
     * @command 10 0 1 * * php index.php VasCloud Commands CDRProxy old
     * @command *\/30 * * * * php index.php VasCloud Commands CDRProxy old
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 2019-07-08 11:40
     */
    public function run($timeFormat = NULL)
    {
        if (is_cli()) {
            try {
                $process = new VasCloudCommandSyncCDRProxy($this->sdkConfig['OPTIONS']);
                $process->setSdkConfig($this->sdkConfig)->setInputTime($timeFormat)->run();
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
