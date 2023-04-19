<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasCloudNotifySubscriberCrossSale;

/**
 * Class WebServiceVasCloudNotifyCrossSale
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasCloudNotifyCrossSale extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasCloudNotifyCrossSale constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService nhận và xử lý Request Notify CrossSale từ VasCloud Event Notifier
     *
     * Mục đích: đồng bộ sự kiện nạp thẻ của khách hàng thông qua vascloud.
     * Phương thức: HTTP POST
     * URL: CP cung cấp URL để Module Event Notifier đẩy sự kiện nạp thẻ
     *
     * Được xây dựng trên chuẩn API VasCloud mới của VinaPhone
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-cloud
     *
     * @link  /vascloud/v1/notify_crosssale
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function index()
    {
        $inputParams = array('xmlData' => $this->input->raw_input_stream);
        $request     = new VasCloudNotifySubscriberCrossSale($this->sdkConfig['OPTIONS']);
        $request->setResponseIsObject()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response    = $request->getResponse();
        $xmlResponse = isset($response->response_string) ? $response->response_string : $request->errorResponse();
        $this->output->set_content_type('text/xml')->set_output($xmlResponse)->_display();
        exit();
    }
}
