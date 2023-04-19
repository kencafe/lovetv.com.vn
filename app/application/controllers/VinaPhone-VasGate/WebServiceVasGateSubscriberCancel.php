<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSubscriberCancel;

/**
 * Class WebServiceVasGateSubscriberCancel
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSubscriberCancel extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSubscriberCancel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý hủy dịch vụ
     *
     * Nghiệp vụ: Hủy gói dịch vụ của thuê bao
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/cancel
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
            'packagename' => $this->input->get_post('packagename', TRUE), // Mã gói dịch vụ, Chú ý: với mã gói “ALL”, hệ thống hủy tất cả các gói đang kích hoạt
            // Chính sách khi hủy gói, sẽ có định nghĩa đối với từng kịch bản sử dụng. Ví dụ: 0: hủy bình thường, 1: hủy gói bundle và thiết lập lại trạng thái gói trước khi đăng ký bundle
            'policy'      => $this->input->get_post('policy', TRUE),
            // Số chu kỳ, ngày, tuần hay tháng miễn phí. Sẽ tự động gia hạn sau khi hết khuyến mãi. 0: hủy bình thường. Nc: miễn cước N chu kỳ. Nd: miễn cước dùng N ngày. Nw: miễn cước dùng N tuần. Nm: miễn cước dùng N tháng. Sẽ có hiệu lực nếu policy = 1
            'promotion'   => $this->input->get_post('promotion', TRUE),
            'note'        => $this->input->get_post('note', TRUE), // Chú thích thêm cho trường thông tin policy hoặc lý do hủy
            // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
            'application' => $this->input->get_post('application', TRUE),
            'channel'     => $this->input->get_post('channel', TRUE), // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
            'username'    => $this->input->get_post('username', TRUE), // Tên của người dùng thao tác
            'userip'      => $this->input->get_post('userip', TRUE), // IP của người dùng thao tác
        );
        $request     = new VasGateSubscriberCancel($this->sdkConfig['OPTIONS']);
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
