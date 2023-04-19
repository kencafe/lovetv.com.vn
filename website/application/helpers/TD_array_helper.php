<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/29/2017
 * Time: 1:54 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('arrayToObject')) {
    /**
     * Function arrayToObject
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:10
     *
     * @param array $array
     *
     * @return array|bool|\stdClass
     */
    function arrayToObject($array = [])
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->arrayToObject($array);

        return $result;
    }
}
