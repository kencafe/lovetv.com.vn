<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 10:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Db_postmeta
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Db_postmeta
{
    protected $CI;

    /**
     * Db_postmeta constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Get Data Post Meta
     *
     * @param string $post_id
     * @param string $meta_key
     *
     * @return mixed
     */
    public function get_data($post_id = '', $meta_key = '')
    {
        $this->CI->load->driver('cache', config_item('main_cache_adapter'));
        $cache_file = GLOBAL_CACHE_PREFIX . '-' . get_class($this) . '-' . __FUNCTION__ . 'Site-Library-DB-PostMETA-Get-Data-Post-Meta-' . md5($post_id . '-' . $meta_key);
        $cache_ttl  = 86400;
        if (!$result = $this->CI->cache->get($cache_file)) {
            $this->CI->load->model('Site/postmeta_model');
            $result = $this->CI->postmeta_model->get_metadata($post_id, $meta_key);
            if ($result !== NULL) {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }

        return $result;
    }

    /**
     * Function get_array_data
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-28 00:21
     *
     * @param string $post_id
     * @param string $meta_key
     *
     * @return mixed
     */
    public function get_array_data($post_id = '', $meta_key = '')
    {
        $this->CI->load->driver('cache', config_item('main_cache_adapter'));
        $cache_file = GLOBAL_CACHE_PREFIX . '-' . get_class($this) . '-' . __FUNCTION__ . 'Site-Library-DB-PostMETA-Get-Data-Post-Meta-' . md5($post_id . '-' . $meta_key);
        $cache_ttl  = 86400;
        if (!$result = $this->CI->cache->get($cache_file)) {
            $this->CI->load->model('Site/postmeta_model');
            $result = $this->CI->postmeta_model->get_array_metadata($post_id, $meta_key);
            if ($result !== NULL) {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }

        return $result;
    }
}
