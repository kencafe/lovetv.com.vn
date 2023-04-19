<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 11:52
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('meta_property')) {
    /**
     * Function meta_property
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 17:00
     *
     * @param string $property
     * @param string $content
     * @param string $type
     * @param string $newline
     *
     * @return string
     */
    function meta_property($property = '', $content = '', $type = 'property', $newline = "\n")
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->metaProperty($property, $content, $type, $newline);

        return $result;
    }
}
if (!function_exists('get_json_item')) {
    /**
     * Function get_json_item
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 17:01
     *
     * @param string $json_string
     * @param string $item_output
     *
     * @return null|string
     */
    function get_json_item($json_string = '', $item_output = '')
    {
        $result = jsonItem($json_string, $item_output);

        return $result;
    }
}
if (!function_exists('placeholder_img')) {
    /**
     * Function placeholder_img
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 17:02
     *
     * @param string $size
     * @param string $background_color
     * @param string $text_color
     * @param string $text
     *
     * @return string
     */
    function placeholder_img($size = '300x250', $background_color = '', $text_color = '', $text = '')
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->placeholder($size, $background_color, $text_color, $text);

        return $result;
    }
}
if (!function_exists('clean_title')) {
    /**
     * Function clean_title
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 13:58
     *
     * @param string $str
     *
     * @return string
     */
    function clean_title($str = '')
    {
        $str = html_escape($str);
        $str = strip_tags($str);

        return trim($str);
    }
}
