<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 3/15/2017
 * Time: 5:39 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('assets_url')) {
    /**
     * Function assets_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 13:59
     *
     * @param string $uri
     * @param null   $protocol
     *
     * @return string
     */
    function assets_url($uri = '', $protocol = NULL)
    {
        $uri = 'assets/' . $uri;

        return base_url($uri, $protocol);
    }
}
if (!function_exists('private_url')) {
    /**
     * Function private_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 14:01
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
     * @time  : 9/21/18 14:01
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
if (!function_exists('images_url')) {
    /**
     * Function images_url
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 14:01
     *
     * @param string $input
     * @param string $server
     * @param string $base
     *
     * @return string
     */
    function images_url($input = '', $server = '', $base = 'live')
    {
        $images_url = trim($input);
        if (!empty($images_url)) {
            $no_thumb = [
                'images/system/no_avatar.jpg',
                'images/system/no_avatar_100x100.jpg',
                'images/system/no_video_available.jpg',
                'images/system/no_video_available_thumb.jpg',
                'images/system/no-image-available.jpg',
                'images/system/no-image-available_60.jpg',
                'images/system/no-image-available_330.jpg'
            ];
            if (in_array($images_url, $no_thumb)) {
                return assets_url($images_url);
            } else {
                $parse_input = parse_url($images_url);
                if (isset($parse_input['host'])) {
                    return $images_url;
                }

                return config_item('static_url') . $images_url;
            }
        }

        return $images_url;
    }
}
if (!function_exists('resize_image')) {
    /**
     * Function resize_image
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/6/18 11:35
     *
     * @param string $url
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    function resize_image($url = '', $width = 100, $height = 100)
    {
        try {
            $cache = new \nguyenanhung\MyImage\ImageCache();
            $cache->setTmpPath(__DIR__ . '/../../public_html/storage/tmp/');
            $cache->setUrlPath(base_url('storage/tmp/'));
            $cache->setDefaultImage();
            $thumbnail = $cache->thumbnail($url, $width, $height);
            if (!empty($thumbnail)) {
                return $thumbnail;
            }

            return $cache->thumbnail(config_item('image_path_tmp_default'), $width, $height);
        }
        catch (Exception $e) {
            return $url;
        }
    }
}
