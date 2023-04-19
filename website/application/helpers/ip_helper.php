<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 20:34
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('get_ip_by_ha_proxy')) {
    /**
     * Function get_ip_by_ha_proxy
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:12
     *
     * @return bool|int|string
     */
    function get_ip_by_ha_proxy()
    {
        $ip     = new \nguyenanhung\VnTelcoPhoneNumberDetect\Ip();
        $result = $ip->getIpByHaProxy();

        return $result;
    }
}
if (!function_exists('get_ip_address_2017')) {
    /**
     * Function get_ip_address_2017
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:12
     *
     * @return bool|int|string
     */
    function get_ip_address_2017()
    {
        $ip     = new \nguyenanhung\VnTelcoPhoneNumberDetect\Ip();
        $result = $ip->getRawIpAddress();

        return $result;
    }
}
if (!function_exists('validate_ip')) {
    /**
     * Function validate_ip
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:12
     *
     * @param string $ip_input
     *
     * @return bool
     */
    function validate_ip($ip_input = '')
    {
        $ip     = new \nguyenanhung\VnTelcoPhoneNumberDetect\Ip();
        $result = $ip->ipValidate($ip_input);

        return $result;
    }
}
if (!function_exists('get_ip_address')) {
    /**
     * Function get_ip_address
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:12
     *
     * @param bool $convertToInteger
     *
     * @return bool|int|mixed|string
     */
    function get_ip_address($convertToInteger = FALSE)
    {
        $ip = new \nguyenanhung\VnTelcoPhoneNumberDetect\Ip();

        return $ip->getIpAddress($convertToInteger);
    }
}
if (!function_exists('getUserIP')) {
    /**
     * Function getUserIP
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:12
     *
     * @return bool|int|mixed|string
     */
    function getUserIP()
    {
        $ip = new \nguyenanhung\VnTelcoPhoneNumberDetect\Ip();

        return $ip->getIpAddress();
    }
}
