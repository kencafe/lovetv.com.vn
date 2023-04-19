<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 2019-01-10
 * Time: 15:45
 */
if (!function_exists('assets_url')) {
    /**
     * Function assets_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-01-10 15:51
     *
     * @param string $uri
     *
     * @return string
     */
    function assets_url($uri = '')
    {
        return base_url('assets/' . $uri);
    }
}
