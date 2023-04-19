<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/20/18
 * Time: 15:33
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Welcome
 *
 * @property object msisdn
 */
class Welcome extends MX_Controller
{
    /**
     * Welcome constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('msisdn');
    }

    /**
     * Function msisdn_detect
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 9/21/18 01:23
     *
     * @link    : /vasgate/welcome/msisdn_detect
     */
    public function msisdn_detect()
    {
        $this->msisdn->msisdnDetect();
    }

    /**
     * Function msisdn_check_info
     *
     * @author  : 713uk13m <dev@nguyenanhung.com>
     * @time    : 9/21/18 03:02
     *
     * @link    : /vasgate/welcome/msisdn_check_info
     */
    public function msisdn_check_info()
    {
        $this->msisdn->msisdnGetInfo();
    }
}
