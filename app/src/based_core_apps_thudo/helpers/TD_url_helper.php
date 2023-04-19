<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/15/2017
 * Time: 5:39 PM
 */
if (!function_exists('assets_url'))
{
    /**
     * Assets URL
     *
     * @param string $uri
     * @param null $protocol
     * @return string
     */
    function assets_url($uri = '', $protocol = NULL)
    {
        $uri = 'assets/' . $uri;
        return base_url($uri, $protocol);
    }
}
if (!function_exists('private_url'))
{
    /**
     * Private Url
     *
     * @param string $uri
     * @return string
     */
    function private_url($uri = '')
    {
        return config_item('private_url') . $uri;
    }
}
if (!function_exists('private_api_url'))
{
    /**
     * Private API Url
     *
     * @param string $uri
     * @return string
     */
    function private_api_url($uri = '')
    {
        return config_item('private_api_url') . $uri;
    }
}
/* End of file TD_url_helper.php */
/* Location: ./based_core_apps_thudo/helpers/TD_url_helper.php */
