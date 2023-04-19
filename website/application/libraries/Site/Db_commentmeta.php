<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 10:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Db_commentmeta
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Db_commentmeta
{
    protected $CI;

    /**
     * Db_commentmeta constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Get Data Comment Meta
     *
     * @param string $comment_id
     * @param string $meta_key
     *
     * @return mixed
     */
    public function get_data($comment_id = '', $meta_key = '')
    {
        $this->CI->load->driver('cache', config_item('main_cache_adapter'));
        $cache_file = GLOBAL_CACHE_PREFIX . '-' . get_class($this) . '-' . __FUNCTION__ . 'Site-Library-DB-CommentMETA-Get-Data-Comment-Meta-' . md5($comment_id . '-' . $meta_key);
        $cache_ttl  = 86400;
        if (!$result = $this->CI->cache->get($cache_file)) {
            $this->CI->load->model('Site/commentmeta_model');
            $result = $this->CI->commentmeta_model->get_metadata($comment_id, $meta_key);
            if ($result !== NULL) {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }

        return $result;
    }
}
