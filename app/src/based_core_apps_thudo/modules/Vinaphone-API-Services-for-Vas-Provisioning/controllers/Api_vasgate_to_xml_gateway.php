<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 9/12/2017
 * Time: 9:46 AM
 */
class Api_vasgate_to_xml_gateway extends MX_Controller
{
    /**
     * Api_vasgate_to_xml_gateway constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('vinaphone_xmlgw');
    }
    /**
     * API Đăng ký dịch vụ
     *
     * @access      public
     * @author 		Hung Nguyen <dev@nguyenanhung.com>
     * @link        /vasprov/api/v1/subscribe/msisdn/package
     * @version     1.0.1
     * @since       11/10/2017
     *
     * @param $msisdn
     * @param $package
     */
    public function subscribe($msisdn, $package)
    {
        $response = $this->vinaphone_xmlgw->subscribe($msisdn, $package);
        $this->output->set_status_header(200)->set_content_type('text/plain', 'utf-8')->set_output($response)->_display();
        exit();
    }
    /**
     * API Hủy dịch vụ
     *
     * @access      public
     * @author 		Hung Nguyen <dev@nguyenanhung.com>
     * @link        /vasprov/api/v1/unsubscribe/msisdn/package
     * @version     1.0.1
     * @since       11/10/2017
     *
     * @param $msisdn
     * @param $package
     */
    public function unsubscribe($msisdn, $package)
    {
        $response = $this->vinaphone_xmlgw->unsubscribe($msisdn, $package);
        $this->output->set_status_header(200)->set_content_type('text/plain', 'utf-8')->set_output($response)->_display();
        exit();
    }
}
/* End of file Api_vasgate_to_xml_gateway.php */
/* Location: ./based_core_apps_thudo/modules/Vinaphone-API-Services-for-Vas-Provisioning/controllers/Api_vasgate_to_xml_gateway.php */
