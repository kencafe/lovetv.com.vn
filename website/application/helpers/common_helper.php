<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 20:34
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('isEmpty')) {
    /**
     * Function isEmpty
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 16:51
     *
     * @param string $input
     *
     * @return bool
     */
    function isEmpty($input = '')
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->isEmpty($input);

        return $result;
    }
}
if (!function_exists('vn_phone_number_format')) {
    /**
     * Function vn_phone_number_format
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/5/18 15:48
     *
     * @param string $my_number
     *
     * @return mixed|null|string
     */
    function vn_phone_number_format($my_number = '')
    {
        $my_number = trim($my_number);
        try {
            $phone = new \nguyenanhung\ThuDoMultimediaVasServices\BasePhoneNumber();
            if ($phone->isValid($my_number) === TRUE) {
                return $phone->format($my_number, 'vn_human');
            }
        }
        catch (Exception $e) {
            log_message('error', 'Error File: ' . $e->getFile() . ' - Error Code: ' . $e->getCode() . ' - Error Line: ' . $e->getLine() . ' - Error Message: ' . $e->getMessage());

            return $my_number;
        }

        return $my_number;
    }
}
if (!function_exists('getSessionItem')) {
    /**
     * Function getSessionItem
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-11-29 13:36
     *
     * @param string $session_id
     *
     * @return mixed
     */
    function getSessionItem($session_id = '')
    {
        /** @var object $CI */
        $CI =& get_instance();
        $CI->load->library('msisdn');

        return $CI->msisdn->getSessionData($session_id);
    }
}
if (!function_exists('getUserPackageId')) {
    /**
     * Function getUserPackageId
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-11-29 13:38
     *
     * @return string
     */
    function getUserPackageId()
    {
        /** @var object $CI */
        $CI =& get_instance();
        $CI->load->library('msisdn');
        $packageInfo = $CI->msisdn->getListUserPackageId();
        if (is_array($packageInfo)) {
            $packageId = implode(', ', $packageInfo);
            $packageId = trim($packageId, ',');
        } else {
            $packageId = $packageInfo;
        }

        return $packageId;
    }
}
