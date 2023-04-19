<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateReceivedMoBusiness;

/**
 * Class WebServiceVasGateReceivedMO
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateReceivedMO extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateReceivedMO constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý MO Business từ ThuDo SMS Gateway
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/receivedMo
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        $inputParams = array(
            'shortcode'   => $this->input->get_post('shortcode', TRUE), // Đầu số dịch vụ
            'msisdn'      => $this->input->get_post('msisdn', TRUE), // SĐT
            'mo'          => $this->input->get_post('mo'), // Message
            'signature'   => $this->input->get_post('signature', TRUE), // Chữ ký xác thực
            'send_method' => $this->input->get_post('send_method', TRUE) // tham số phụ
        );
        $request     = new VasGateReceivedMoBusiness($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
