<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/6/18
 * Time: 04:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Db_category
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Db_category
{
    protected $CI;

    /**
     * Db_category constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Get ID Category by Slug
     *
     * @param string $slugs
     *
     * @return mixed
     */
    public function get_id_by_slugs($slugs = '')
    {
        $this->CI->load->driver('cache', config_item('main_cache_adapter'));
        $cache_file = GLOBAL_CACHE_PREFIX . '-' . get_class($this) . '-' . __FUNCTION__ . 'Site-Library-DB-Library-Get-Data-Category-ID-by-Slugs' . md5($slugs);
        $cache_ttl  = 86400;
        if (!$result = $this->CI->cache->get($cache_file)) {
            $this->CI->load->model('Site/category_model');
            $result = $this->CI->category_model->get_value($slugs, 'slugs', 'id');
            if ($result !== NULL) {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }

        return $result;
    }
}
