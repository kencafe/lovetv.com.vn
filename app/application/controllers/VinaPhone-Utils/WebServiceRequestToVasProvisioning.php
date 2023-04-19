<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\UtilsVasProvisioningForMegaView360;

/**
 * Class WebServiceRequestToVasProvisioning
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceRequestToVasProvisioning extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceRequestToVasProvisioning constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService gửi yêu cầu đăng nhập tới VinaPhone VasProvisioning
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/vasProvisioning/loginProcess
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function loginProcess()
    {
        $inputParams = array(
            'type'  => $this->input->get_post('type', TRUE),
            'token' => $this->input->get_post('token', TRUE),
        );
        $request     = new UtilsVasProvisioningForMegaView360($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->login();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }

    /**
     * WebService gửi yêu cầu đăng xuất tới VinaPhone VasProvisioning
     *
     * Method: HTTP POST
     *
     * Được xây dựng trên chuẩn API của VasGate
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-gate
     *
     * @link  /api/v1/vasProvisioning/logoutProcess
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 14:44
     */
    public function logoutProcess()
    {
        $request = new UtilsVasProvisioningForMegaView360($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->logout();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
