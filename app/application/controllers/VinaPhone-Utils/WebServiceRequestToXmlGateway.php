<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Support\VinaPhoneXmlGateway;

/**
 * Class WebServiceRequestToXmlGateway
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceRequestToXmlGateway extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceRequestToXmlGateway constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService gửi yêu cầu đăng ký tới VinaPhone VasProvisioning
     *
     * Method: HTTP POST/GET
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @param string $msisdn
     * @param string $package
     *
     * @link  /api/v1/vasProvisioning/subscribe/$1/$2
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-10 11:58
     */
    public function subscribe($msisdn = '', $package = '')
    {
        $xmlGateway = new VinaPhoneXmlGateway($this->sdkConfig['OPTIONS']);
        $response   = $xmlGateway->setSdkConfig($this->sdkConfig)->subscribe($msisdn, $package);
        $this->output->set_status_header(200)->set_content_type('text/plain', 'utf-8')->set_output($response)->_display();
        exit();
    }

    /**
     * WebService gửi yêu cầu hủy tới VinaPhone VasProvisioning
     *
     * Method: HTTP POST/GET
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @param string $msisdn
     * @param string $package
     *
     * @link  /api/v1/vasProvisioning/unsubscribe/$1/$2
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-10 11:58
     *
     */
    public function unsubscribe($msisdn = '', $package = '')
    {
        $xmlGateway = new VinaPhoneXmlGateway($this->sdkConfig['OPTIONS']);
        $response   = $xmlGateway->setSdkConfig($this->sdkConfig)->unsubscribe($msisdn, $package);
        $this->output->set_status_header(200)->set_content_type('text/plain', 'utf-8')->set_output($response)->_display();
        exit();
    }
}
