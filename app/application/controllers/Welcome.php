<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Welcome
 */
class Welcome extends CI_Controller
{

    /**
     * Function index
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-14 09:59
     *
     */
    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function test()
    {
        echo "<pre>";
        print_r(APPPATH);
        echo "</pre>";
        echo "<pre>";
        print_r(FCPATH);
        echo "</pre>";
    }
}
