<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/23/18
 * Time: 14:06
 */
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('parse_sitemap')) {
    /**
     * Function parse_sitemap
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/29/18 17:03
     *
     * @param string $loc
     * @param string $lastmod
     * @param string $type
     * @param string $newline
     *
     * @return string
     */
    function parse_sitemap($loc = '', $lastmod = '', $type = 'property', $newline = "\n")
    {
        $common = new \nguyenanhung\Classes\Helper\Common();
        $result = $common->sitemapParse($loc, $lastmod, $type, $newline);

        return $result;
    }
}
