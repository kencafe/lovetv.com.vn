<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSubscriberRenewal;

/**
 * Class WebServiceVasGateSubscriberRenewal
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSubscriberRenewal extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSubscriberRenewal constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý gia hạn dịch vụ cho thuê bao
     *
     * Nghiệp vụ: RENEW / RETRY dịch vụ cho thuê bao
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/renewal
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        $inputParams = array(
            'msisdn'      => $this->input->get_post('msisdn', TRUE),
            'packageName' => $this->input->get_post('packageName', TRUE),
            'eventName'   => $this->input->get_post('eventName', TRUE),
            'price'       => $this->input->get_post('price', TRUE),
            'channel'     => $this->input->get_post('channel', TRUE),
            'signature'   => $this->input->get_post('signature', TRUE),
        );
        $request     = new VasGateSubscriberRenewal($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
