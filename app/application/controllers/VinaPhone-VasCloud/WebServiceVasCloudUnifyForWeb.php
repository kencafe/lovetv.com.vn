<?php
/**
 * Project project-base-service-connect-to-vinaphone.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-07-08
 * Time: 11:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use nguyenanhung\ThuDoMultimediaSDKServices\VinaPhone\Http\UtilsUnifyForWeb;

/**
 * Class WebServiceVasCloudUnifyForWeb
 *
 * @property mixed input
 * @property mixed output
 * @property mixed config
 */
class WebServiceVasCloudUnifyForWeb extends CI_Controller
{
    /** @var mixed SDK Config */
    private $sdkConfig;

    /**
     * WebServiceVasCloudUnifyForWeb constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('config_vina_sdk');
        $this->sdkConfig = config_item('vina_sdk_config');
    }

    /**
     * WebService xử lý thông tin và điều hướng link đăng ký trên WEB / WAP
     *
     * Phương thức: HTTP/JSON
     *
     * Chi tiết tham khảo file: https://bit.ly/vina-vas-cloud
     *
     * @link  /vascloud/v1/unify_wap.html
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-07-09 15:00
     */
    public function index()
    {
        $action      = $this->input->get_post('action', TRUE); // Trạng thái 0: Đăng ký 1: Hủy
        $serviceId   = $this->input->get_post('serviceid', TRUE); // Mã dịch vụ (Number)
        $packageId   = $this->input->get_post('packageid', TRUE); // Gói dịch vụ (Number)
        $returnUrl   = $this->input->get_post('returnurl', TRUE); // URL sẽ redirect sau khi mua gói thành công
        $backUrl     = $this->input->get_post('backurl', TRUE); // URL sẽ redirect khi người dùng muốn quay lại trang cung cấp gói
        $channel     = $this->input->get_post('channel', TRUE); // Kênh thực hiện: wap/web/client
        $signature   = $this->input->get_post('signature', TRUE); // Chữ kí xác thực
        $inputParams = array(
            'action'    => $action,
            'serviceid' => $serviceId,
            'packageid' => $packageId,
            'returnurl' => $returnUrl,
            'backurl'   => $backUrl,
            'channel'   => $channel,
            'signature' => $signature
        );
        $request     = new UtilsUnifyForWeb($this->sdkConfig['OPTIONS']);
        $request->setResponseIsJson()->setSdkConfig($this->sdkConfig)->setInputData($inputParams)->parse();
        $response = $request->getResponse();
        $this->output->set_content_type('application/json')->set_output($response)->_display();
        exit();
    }
}
