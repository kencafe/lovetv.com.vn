<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller
{
    /**
     * Home page
     */
    public function index()
    {
        $this->load->view('welcome');
    }
}
