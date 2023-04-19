<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSubscriberRegister;

/**
 * Class WebServiceVasGateSubscriberRegister
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSubscriberRegister extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSubscriberRegister constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý đăng ký dịch vụ
     *
     * Nghiệp vụ: Đăng ký gói dịch vụ cho thuê bao
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/register
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        if (isset($this->sdkConfig['SERVICES']['isTestRequestId']) && $this->sdkConfig['SERVICES']['isTestRequestId'] === TRUE) {
            $requestId = date('YmdHis') . random_string('numeric', 10);
        } else {
            $requestId = $this->input->get_post('requestid', TRUE);
        }
        $inputParams = array(
            'requestid'   => $requestId, // Mã ngẫu nhiên
            'msisdn'      => $this->input->get_post('msisdn', TRUE), // Số thuê bao
            'packagename' => $this->input->get_post('packagename', TRUE), // Mã gói dịch vụ
            'promotion'   => $this->input->get_post('promotion', TRUE), // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi.
            'trial'       => $this->input->get_post('trial', TRUE), // Số chu kỳ, ngày, tuần hay tháng dùng thử. Sẽ gửi tin nhắn thông báo khi hết thời gian dùng thử, nếu khách hàng không hủy thì sẽ bị gia hạn.
            'bundle'      => $this->input->get_post('bundle', TRUE), // Xử lý nếu Kịch bản kinh doanh có đề cập: 0: đăng ký gói bình thường 1: đăng ký gói kiểu bundle (không trừ cước đăng ký, không gia hạn)
            'note'        => $this->input->get_post('note', TRUE), // Chú thích về đăng ký/khuyến mãi/dùng thử/tên gói bundle/MO đến
            // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
            'application' => $this->input->get_post('application', TRUE),
            'channel'     => $this->input->get_post('channel', TRUE), // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
            'username'    => $this->input->get_post('username', TRUE), // Tên của người dùng thao tác
            'userip'      => $this->input->get_post('userip', TRUE), // IP của người dùng thao tác
        );
        $request     = new VasGateSubscriberRegister($this->sdkConfig['OPTIONS']);
        $request->setResponseIsObject()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $output   = array(
            'errorid'   => isset($response->errorid) ? $response->errorid : 101,
            'errordesc' => isset($response->errordesc) ? $response->errordesc : 'Error'
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output))->_display();
        exit();
    }
}
