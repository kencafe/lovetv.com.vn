<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSmsSender;

/**
 * Class WebServiceVasGateSMS
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSMS extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSMS constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý gửi SMS tới chủ thuê bao
     *
     * Phương thức: HTTP GET/POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/sendSms
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function sendSms()
    {
        $inputParams = array(
            'msisdn'      => $this->input->get_post('msisdn', TRUE), // Số thuê bao
            'mo'          => $this->input->get_post('mo', TRUE),// Mo
            'mt'          => $this->input->get_post('mt'), // MT
            'note'        => $this->input->get_post('note', TRUE), // Chú thích
            'sub_code'    => $this->input->get_post('sub_code', TRUE), // Mã gói con
            'signature'   => $this->input->get_post('signature', TRUE), // Chữ ký bí mật
            'mtIsTT08'    => $this->input->get_post('mtIsTT08', TRUE),
            'send_method' => $this->input->get_post('send_method', TRUE) // Phương thức gửi Msg_Log: test
        );
        $request     = new VasGateSmsSender($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->sendSms();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }

    /**
     * WebService nhận và xử lý gửi SMS Daily tới chủ thuê bao
     *
     * Phương thức: HTTP GET/POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/sendDailySms
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function sendDailySms()
    {
        $inputParams = array(
            'msisdn'      => $this->input->get_post('msisdn', TRUE), // Số thuê bao
            'mo'          => $this->input->get_post('mo', TRUE),// Mo
            'mt'          => $this->input->get_post('mt'), // MT
            'note'        => $this->input->get_post('note', TRUE), // Chú thích
            'sub_code'    => $this->input->get_post('sub_code', TRUE), // Mã gói con
            'signature'   => $this->input->get_post('signature', TRUE), // Chữ ký bí mật
            'mtIsTT08'    => $this->input->get_post('mtIsTT08', TRUE),
            'send_method' => $this->input->get_post('send_method', TRUE) // Phương thức gửi Msg_Log: test
        );
        $request     = new VasGateSmsSender($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->sendDailySms();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }

    /**
     * WebService nhận và xử lý Push SMS tới chủ thuê bao
     *
     * Phương thức: HTTP GET/POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/pushSms
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function pushSms()
    {
        $inputParams = array(
            'msisdn'      => $this->input->get_post('msisdn', TRUE), // Số thuê bao
            'mo'          => $this->input->get_post('mo', TRUE),// Mo
            'mt'          => $this->input->get_post('mt'), // MT
            'note'        => $this->input->get_post('note', TRUE), // Chú thích
            'sub_code'    => $this->input->get_post('sub_code', TRUE), // Mã gói con
            'signature'   => $this->input->get_post('signature', TRUE), // Chữ ký bí mật
            'mtIsTT08'    => $this->input->get_post('mtIsTT08', TRUE),
            'send_method' => $this->input->get_post('send_method', TRUE) // Phương thức gửi Msg_Log: test
        );
        $request     = new VasGateSmsSender($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->pushSms();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
