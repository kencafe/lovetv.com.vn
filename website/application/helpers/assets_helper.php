<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 20:34
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('assets_url')) {
    /**
     * Function assets_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 13:54
     *
     * @param string $uri
     * @param null   $protocol
     *
     * @return string
     */
    function assets_url($uri = '', $protocol = NULL)
    {
        return base_url('assets/' . $uri, $protocol);
    }
}
if (!function_exists('assets_themes')) {
    /**
     * Function assets_themes
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 13:54
     *
     * @param string $themes
     * @param string $uri
     * @param string $asset_folder
     * @param null   $protocol
     *
     * @return string
     */
    function assets_themes($themes = '', $uri = '', $asset_folder = 'yes', $protocol = NULL)
    {
        $path_assets = 'assets/themes/';
        // Pattern
        $uri = $themes != '' ? ($asset_folder === 'no' ? $themes . '/' . $uri : $themes . '/assets/' . $uri) : ($asset_folder === 'no' ? $uri : 'assets/' . $uri);

        return base_url($path_assets . $uri, $protocol);
    }
}
if (!function_exists('favicon_url')) {
    /**
     * Function favicon_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 13:54
     *
     * @param string $uri
     * @param null   $protocol
     *
     * @return string
     */
    function favicon_url($uri = '', $protocol = NULL)
    {
        return assets_url('fav/' . $uri, $protocol);
    }
}
if (!function_exists('upload_url')) {
    /**
     * Function upload_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-16 08:53
     *
     * @param string $uri
     * @param null   $protocol
     *
     * @return string
     */
    function upload_url($uri = '', $protocol = NULL)
    {
        return base_url('uploads/' . $uri, $protocol);
    }
}
