<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Notification
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Notification extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * Notification constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->config('config_web_builder_sdk');
        $this->sdkConfig = config_item('web_builder_sdk_config');
    }

    /**
     * Function CI_CD_Stages_Success
     *
     * @command  php index.php Notification CI_CD_Stages_Success "Test"
     *
     * @param string $stages
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 09/19/2020 09:11
     */
    public function CI_CD_Stages_Success($stages = 'Test')
    {
        $handle   = $this->sdkConfig['CONFIG_HANDLE'];
        $siteName = $handle['siteName'];
        $stages   = strtoupper($stages);
        $message  = $stages . ' -> SUCCESS | On time ' . date('Y-m-d H:i:s');
        system_notification_to_telegram($this->sdkConfig, $siteName . ' | CI/CD', $message);
    }
}
