<?php
/**
 * Project project-vnm-on-play.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-01-15
 * Time: 11:25
 */
if (!function_exists('private_url')) {
    /**
     * Function private_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-15 11:26
     *
     * @param string $uri
     *
     * @return string
     */
    function private_url($uri = '')
    {
        return config_item('private_url') . $uri;
    }
}
if (!function_exists('private_api_url')) {
    /**
     * Function private_api_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-15 11:26
     *
     * @param string $uri
     *
     * @return string
     */
    function private_api_url($uri = '')
    {
        return config_item('private_api_url') . $uri;
    }
}
