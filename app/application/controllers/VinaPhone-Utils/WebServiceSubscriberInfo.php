<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-01-04
 * Time: 23:21
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\UtilsUserForWeb;

/**
 * Class WebServiceSubscriberInfo
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceSubscriberInfo extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceSubscriberInfo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * Module WebService cấp cho client đăng nhập vào hệ thống
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-05 00:08
     * @link  : /api/v2/users/signIn
     */
    public function subscriberSignIn()
    {
        $inputParams = array(
            'username'  => $this->input->get_post('username', TRUE),
            'password'  => $this->input->get_post('password', TRUE),
            'sessionId' => $this->input->get_post('sessionId', TRUE),
            'signature' => $this->input->get_post('signature', TRUE)
        );
        $module      = new UtilsUserForWeb($this->sdkConfig['OPTIONS']);
        $module->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->signIn();
        $response = $module->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }

    /**
     * Module WebService cấp cho client lấy thông tin người dùng
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-05 00:21
     * @link  : /api/v2/users/getInfo
     */
    public function subscriberGetInfo()
    {
        $inputParams = array(
            'msisdn'    => $this->input->get_post('msisdn', TRUE),
            'signature' => $this->input->get_post('signature', TRUE)
        );
        $module      = new UtilsUserForWeb($this->sdkConfig['OPTIONS']);
        $module->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->getInfo();
        $response = $module->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
