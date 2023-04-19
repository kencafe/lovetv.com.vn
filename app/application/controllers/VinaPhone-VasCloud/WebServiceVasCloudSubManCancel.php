<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\VasCloudSubscriberCancelForSubman;

/**
 * Class WebServiceVasCloudSubManCancel
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasCloudSubManCancel extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasCloudSubManCancel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService xử lý gửi API Subman Cancel VasCloud
     *
     * Phương thức: HTTP/XML
     *
     * Được xây dựng trên chuẩn Subman Api Vascloud mới của Vina
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-cloud
     *
     * @param string $msisdn
     * @param string $packageId
     *
     * @link  /vascloud/v1/subman/cancel/$1/$2
     *
     * Function cancel
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function cancel($msisdn = '', $packageId = '')
    {
        $sendMethod  = $this->input->get_post('send_method', TRUE);
        $inputParams = array(
            'msisdn'    => $msisdn,
            'packageId' => $packageId,
            'isTest'    => $sendMethod
        );
        $request     = new VasCloudSubscriberCancelForSubman($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
