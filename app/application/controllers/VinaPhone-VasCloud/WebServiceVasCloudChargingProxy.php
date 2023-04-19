<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasCloudCharging;

/**
 * Class WebServiceVasCloudChargingProxy
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasCloudChargingProxy extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasCloudChargingProxy constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý charge cước người dùng thông qua Charging Gateway
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API VasCloud mới của VinaPhone
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-cloud
     *
     * @link  /vascloud/v1/charging
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        $inputParams = array(
            'msisdn'        => $this->input->get_post('msisdn', TRUE), // Số điện thoại cần trừ cước
            'packageName'   => $this->input->get_post('packageName', TRUE), // Gói cước cần trừ
            'eventName'     => $this->input->get_post('eventName', TRUE), // renew, retry, register, cancel, buy
            'price'         => $this->input->get_post('price', TRUE), // Giá cươc
            'originalPrice' => $this->input->get_post('originalPrice', TRUE), // Giá cước gốc
            'promotion'     => $this->input->get_post('promotion', TRUE), // Khuyến mại hay không
            'channel'       => $this->input->get_post('channel', TRUE),// Kênh gọi charge cước
            'signature'     => $this->input->get_post('signature', TRUE), // Chữ ký xác thực
            'send_method'   => $this->input->get_post('send_method', TRUE) // tham số phụ
        );
        $request     = new VasCloudCharging($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
