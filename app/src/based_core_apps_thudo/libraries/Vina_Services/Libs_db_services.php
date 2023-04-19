<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 7/4/2017
 * Time: 10:14 AM
 */
class Libs_db_services
{
    protected $CI;
    protected $cachePrefix;
    /**
     * Libs_db_services constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->cachePrefix = 'Get-Info-Data-Services-';
    }
    /**
     * Get Data
     * @param string $serviceId
     * @return mixed
     */
    public function get_data($serviceId = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . $this->cachePrefix . md5($serviceId);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$result = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_services_model');
            $result = $this->CI->db_services_model->get_info($serviceId);
            if ($result !== null)
            {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
        }
        return $result;
    }

    /**
     * Close DB
     */
    public function close()
    {
        if (isset($this->CI->db_services_model) && is_object($this->CI->db_services_model)) {
            $this->CI->db_services_model->close();
        }
    }
    /**
     * Clean Cache Data
     * @param string $serviceId
     * @return bool
     */
    public function clean_cache_data($serviceId = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . $this->cachePrefix . md5($serviceId);
        $result     = $this->CI->cache->delete($cache_file);
        return $result;
    }
}
