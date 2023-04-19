<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('assets_url'))
{
    /**
     * Assets Url
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
if (!function_exists('assets_themes'))
{
    /**
     * assets themes
     *
     * @param string $themes
     * @param string $uri
     * @param string $asset_folder
     * @param null $protocol
     * @return string
     */
    function assets_themes($themes = '', $uri = '', $asset_folder = 'yes', $protocol = NULL)
    {
        $uri = $themes != '' ? ($asset_folder === 'no' ? 'assets/themes/' . $themes . '/' . $uri : 'assets/themes/' . $themes . '/assets/' . $uri) : ($asset_folder === 'no' ? 'assets/themes/' . $uri : 'assets/themes/assets/' . $uri);
        return base_url($uri, $protocol);
    }
}
if (!function_exists('favicon_url'))
{
    /**
     * Favicon Url
     *
     * @param string $uri
     * @param null $protocol
     * @return string
     */
    function favicon_url($uri = '', $protocol = NULL)
    {
        $uri = 'favicon/' . $uri;
        return assets_url($uri, $protocol);
    }
}
