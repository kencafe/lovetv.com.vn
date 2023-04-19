<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/15/2017
 * Time: 11:38 AM
 */
class Ip_libs
{
    protected $CI;
    /**
     * Ip_tools constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * GET IP Address from HA Proxy
     *
     * @return string
     */
    public function ip_proxy()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
    /**
     * GET IP Address
     * @param bool $convertToInteger
     * @return array|false|string
     */
    public function ip_address($convertToInteger = false)
    {
        $ip = '';
        if ($_SERVER)
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $ip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $ip = getenv('REMOTE_ADDR');
            }
        }
        // Convert IP string to Integer
        // Example, IP: 127.0.0.1 --> 2130706433
        if ($convertToInteger)
        {
            $ip = ip2long($ip);
        }
        return $ip;
    }
    /**
     * Get API Infomation
     *
     * @param string $ip
     * @return string
     */
    public function ip_infomation($ip = '')
    {
        if (empty($ip))
        {
            $ip = self::ip_address();
        }
        $curl = new Curl\Curl();
        $curl->get('http://ip-api.com/json/' . $ip);
        $response = $curl->error ? "cURL Error: " . $curl->error_message : $curl->response;
        return $response;
    }
}
/* End of file Ip_libs.php */
/* Location: ./based_core_apps_thudo/libraries/Ip_libs.php */
