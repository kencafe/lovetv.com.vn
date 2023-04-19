<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasGateSubscriberGetInfoByPackage;

/**
 * Class WebServiceVasGateSubscriberGetInfo
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasGateSubscriberGetInfo extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasGateSubscriberGetInfo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý trả thông tin về gói dịch vụ của thuê bao.
     *
     * Nghiệp vụ: Lấy thông tin về gói dịch vụ của thuê bao.
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/getInfo
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
            // Tên hệ thống gọi API (sẽ có xử lý logic tùy giá trị). Logic xử lý đối với trường application sẽ phụ thuộc và kịch bản kinh doanh quy định. Ví dụ application là CCOS, VASPORTAL, VASDEALER, …
            'application' => $this->input->get_post('application', TRUE),
            'channel'     => $this->input->get_post('channel', TRUE), // Kênh xuất phát lệnh (SMS, WEB, WAP, USSD…)
            'username'    => $this->input->get_post('username', TRUE), // Tên của người dùng thao tác
            'userip'      => $this->input->get_post('userip', TRUE), // IP của người dùng thao tác
        );
        $request     = new VasGateSubscriberGetInfoByPackage($this->sdkConfig['OPTIONS']);
        $request->setResponseIsObject()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $output   = array(
            'errorid'               => isset($response->errorid) ? $response->errorid : 101,
            'errordesc'             => isset($response->errordesc) ? $response->errordesc : 'Error',
            'last_time_subscribe'   => isset($response->last_time_subscribe) ? $response->last_time_subscribe : NULL,
            'last_time_unsubscribe' => isset($response->last_time_unsubscribe) ? $response->last_time_unsubscribe : NULL,
            'last_time_renew'       => isset($response->last_time_renew) ? $response->last_time_renew : NULL,
            'last_time_retry'       => isset($response->last_time_retry) ? $response->last_time_retry : NULL,
            'expire_time'           => isset($response->expire_time) ? $response->expire_time : NULL
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($output))->_display();
        exit();
    }
}
