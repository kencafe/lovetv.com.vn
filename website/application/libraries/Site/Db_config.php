<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 10:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Db_config
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 * @property object CI
 */
class Db_config
{
    protected $CI;
    protected $provider_database;

    /**
     * Db_config constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->config->load('config_vas_telcos'); // File config mới
        $this->provider_database = config_item('provider_database');
    }

    /**
     * Function get_data
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/27/18 13:55
     *
     * @param string $configId
     *
     * @return mixed
     */
    public function get_data($configId = '')
    {
        $configId = $this->provider_database['tableConfigPrefix'] . trim($configId);
        $this->CI->load->driver('cache', config_item('main_cache_adapter'));
        $cache_file = GLOBAL_CACHE_PREFIX . '-' . get_class($this) . '-' . __FUNCTION__ . 'Site-Library-DB-Config-Get-Data-Config-' . md5($configId);
        $cache_ttl  = 86400;
        if (!$result = $this->CI->cache->get($cache_file)) {
            $this->CI->load->model('Site/config_model');
            $result = $this->CI->config_model->get_value($configId, 'id', 'value');
            if ($result !== NULL) {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }

        return $result;
    }
}
