<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasCloudNotifyCheck;

/**
 * Class WebServiceVasCloudNotifyCheck
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasCloudNotifyCheck extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasCloudNotifyCheck constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý Request Notify Check từ VasCloud Event Notifier
     *
     * Mục đích: xác nhận thông tin đăng ký gói cước của thuê bao, CP có quyền quyết định
     * dịch vụ và gói cước của thuê bao có được phép đăng ký hay không trong những trường
     * hợp đặc biệt. Hàm này chỉ áp dụng khi gói dịch vụ có tính chất đặc biệt, ví dụ như đăng
     * ký gói ngày thì không được đăng ký gói tuần. Các trường hợp cho phép đăng ký, hủy mọi
     * thuê bao thì không cần thực hiện.
     *
     * Method: HTTP POST
     * Yêu cầu: CP cung cấp URL để Subman API gọi trong trường hợp đăng ký gói cước dịch vụ cho thuê bao.
     *
     * Được xây dựng trên chuẩn API VasCloud mới của VinaPhone
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-cloud
     *
     * @link  /vascloud/v1/notifyCheckSubscriber
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        $inputParams = array('xmlData' => $this->input->raw_input_stream);
        $request     = new VasCloudNotifyCheck($this->sdkConfig['OPTIONS']);
        $request->setResponseIsObject()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response    = $request->getResponse();
        $xmlResponse = isset($response->response_string) ? $response->response_string : $request->errorResponse();
        $this->output->set_content_type('text/xml')->set_output($xmlResponse)->_display();
        exit();
    }
}
