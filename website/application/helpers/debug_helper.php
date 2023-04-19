<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 20:34
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('dump')) {
    /**
     * Function dump
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 16:54
     *
     * @param string $str
     */
    function dump($str = '')
    {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
    }
}
