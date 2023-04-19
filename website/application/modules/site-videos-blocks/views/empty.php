<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:47
 */
if (isset($sub)) {
    if (isset($data)) {
        $this->load->view($sub, $data);
    } else {
        $this->load->view($sub);
    }
}
