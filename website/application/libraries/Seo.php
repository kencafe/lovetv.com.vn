<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/2/18
 * Time: 15:54
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use Cocur\Slugify\Slugify;
use Hashids\Hashids;

interface SeoInterface
{
    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:48
     *
     * @return string
     */
    public function getVersion();

    /**
     * Function slugify - SEO Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:49
     *
     * @param string $str
     *
     * @return string
     */
    public function slugify($str = '');

    /**
     * Function search_slugify - SEO Search Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:50
     *
     * @param string $str
     *
     * @return string
     */
    public function search_slugify($str = '');

    /**
     * Function str_to_en - Str To English
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:50
     *
     * @param string $str
     *
     * @return string
     */
    public function str_to_en($str = '');

    /**
     * Function encodeId - Encode ID to String
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param $id
     *
     * @return null|string
     */
    public function encodeId($id);

    /**
     * Function decodeId - Decode String to ID
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param $string
     *
     * @return array|null
     */
    public function decodeId($string);

    /**
     * Function url_post - Get URL Post
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param string $category_slug
     * @param string $post_slug
     * @param string $post_id
     * @param string $post_type
     *
     * @return string http://domain.com/Category-name/Post-name-postID.html
     */
    public function url_post($category_slug = '', $post_slug = '', $post_id = '', $post_type = '');

    /**
     * Function url_page - Get URL Page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:53
     *
     * @param string $page_slug
     * @param string $page_id
     *
     * @return string http://domain.com/pages/page-slug-pageID.html
     */
    public function url_page($page_slug = '', $page_id = '');
}

class Seo implements SeoInterface
{
    const VERSION = '1.0.0';
    protected $CI;
    protected $hashids;

    /**
     * Seo constructor.
     *
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->config('config_seo');
        $this->hashids = config_item('hashids');
    }

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:48
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Function slugify - SEO Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:49
     *
     * @param string $str
     *
     * @return string
     */
    public function slugify($str = '')
    {
        try {
            $slugify = new Slugify();

            return $slugify->slugify($str);
        }
        catch (Exception $e) {
            return trim($str);
        }

    }

    /**
     * Function search_slugify - SEO Search Slugify
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:50
     *
     * @param string $str
     *
     * @return string
     */
    public function search_slugify($str = '')
    {
        try {
            $options = array(
                'separator' => '+'
            );
            $slugify = new Slugify($options);

            return $slugify->slugify($str);
        }
        catch (Exception $e) {
            return trim($str);
        }
    }

    /**
     * Function str_to_en - Str To English
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:50
     *
     * @param string $str
     *
     * @return string
     */
    public function str_to_en($str = '')
    {
        try {
            $options = array(
                'separator' => ' '
            );
            $slugify = new Slugify($options);

            return $slugify->slugify($str);
        }
        catch (Exception $e) {
            return trim($str);
        }
    }

    /**
     * Function encodeId - Encode ID to String
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param $id
     *
     * @return null|string
     */
    public function encodeId($id)
    {
        try {
            $hash = new Hashids($this->hashids['salt'], $this->hashids['minHashLength'], $this->hashids['alphabet']);

            return $hash->encode($id);
        }
        catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Function decodeId - Decode String to ID
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param $string
     *
     * @return array|null
     */
    public function decodeId($string)
    {
        try {
            $hash   = new Hashids($this->hashids['salt'], $this->hashids['minHashLength'], $this->hashids['alphabet']);
            $decode = $hash->decode($string);
            if (count($decode) > 1) {
                return $decode;
            }

            return $decode[0];
        }
        catch (Exception $e) {
            return NULL;
        }
    }

    /**
     * Function url_post - Get URL Post
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:52
     *
     * @param string $category_slug
     * @param string $post_slug
     * @param string $post_id
     * @param string $post_type
     *
     * @return string http://domain.com/Category-name/Post-name-postID.html
     */
    public function url_post($category_slug = '', $post_slug = '', $post_id = '', $post_type = '')
    {
        $url = site_url(trim($category_slug) . '/' . trim($post_slug) . '-post' . $this->encodeId(trim($post_id)));

        return $url;
    }

    /**
     * Function url_page - Get URL Page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/21/18 02:53
     *
     * @param string $page_slug
     * @param string $page_id
     *
     * @return string http://domain.com/pages/page-slug-pageID.html
     */
    public function url_page($page_slug = '', $page_id = '')
    {
        $url = site_url('pages/' . trim($page_slug) . '-page' . $this->encodeId(trim($page_id)));

        return $url;
    }
}
