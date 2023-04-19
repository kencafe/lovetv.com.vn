<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Commands\VasGateDailyChargingAsyncProcess;

/**
 * Class CommandsVasGateProcessCharging
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class CommandsVasGateProcessCharging extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * CommandsVasGateProcessCharging constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * Hàm charge cước hàng ngày
     *
     * @param string $eventName
     * @param string $packageId
     * @param string $status
     *
     * @command : php index.php VasGate Commands Charging "eventName" "packageId" "status"
     * @command : php index.php VasGate Commands Charging "eventName" "packageId"
     * @command : php index.php VasGate Commands Charging "eventName"
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 2019-01-10 15:37
     *
     */
    public function run($eventName = '', $packageId = '', $status = '')
    {
        if (is_cli()) {
            if (empty($eventName)) {
                $message = "Vui lòng nhập thông tin sự kiện gọi charge cước để tiếp tục!\n";
                $this->output->set_status_header(200)->set_content_type('plain/text', 'utf-8')->set_output($message)->_display();
                exit;
            } else {
                $module = new VasGateDailyChargingAsyncProcess($this->sdkConfig['OPTIONS']);
                $module->setSdkConfig($this->sdkConfig);
                if (!empty($eventName)) {
                    $module->setEventName(strtoupper($eventName));
                }
                if (!empty($packageId)) {
                    $module->setPackageId(strtoupper($packageId));
                }
                if (!empty($status)) {
                    $module->setSubscriberStatus($status);
                }
                $module->run();
            }
        } else {
            $data = array('status' => EXIT_ERROR, 'desc' => '404 Not Found');
            $this->output->set_status_header(200)->set_content_type('application/json', 'utf-8')->set_output(json_encode($data))->_display();
            exit;
        }
    }
}
