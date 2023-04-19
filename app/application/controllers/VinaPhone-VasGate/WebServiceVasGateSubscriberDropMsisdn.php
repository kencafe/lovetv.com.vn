<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSubscriberDropNumber;

/**
 * Class WebServiceVasGateSubscriberDropMsisdn
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSubscriberDropMsisdn extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSubscriberDropMsisdn constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý hủy thuê bao
     *
     * Nghiệp vụ: Khi thuê bao không tiếp tục sử dụng hoặc thuê bao khóa 2 chiều quá thời gian quy định,
     * Vinaphone sẽ thu hồi số để đưa vào kho số, tái sử dụng cho khách hàng khác.
     * Để đảm bảo khách hàng mới sử dụng số thuê bao này thì cần hủy mọi dịch vụ đã đăng ký trước đó.
     * Để làm điều này hệ thống hủy thuê bao Vinaphone sẽ gọi API hủy thuê bao các hệ thống dịch vụ VAS
     * để hủy thuê bao khỏi hệ thống đó, trên hệ thống không lưu các thông tin liên quan đến thuê bao này.
     * Tuy nhiên phần lịch sử thuê bao thì vẫn giữ theo quy định chung.
     * Sau khi hủy thuê bao, thực hiện API lấy thông tin dịch vụ thì thuê bao này sẽ trở về trạng thái status = 2.
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/dropMsisdn
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
            'reason'      => $this->input->get_post('reason', TRUE), // Lý do hủy
            // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
            'application' => $this->input->get_post('application', TRUE),
            'channel'     => $this->input->get_post('channel', TRUE), // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
            'username'    => $this->input->get_post('username', TRUE), // Tên của người dùng thao tác
            'userip'      => $this->input->get_post('userip', TRUE), // IP của người dùng thao tác
        );
        $request     = new VasGateSubscriberDropNumber($this->sdkConfig['OPTIONS']);
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
