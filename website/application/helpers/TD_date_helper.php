<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/9/2017
 * Time: 3:27 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('iso_8601_utc_time')) {
    /**
     * Function iso_8601_utc_time
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:10
     *
     * @return mixed|string
     */
    function iso_8601_utc_time()
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->zuluTime();

        return $result;
    }
}
