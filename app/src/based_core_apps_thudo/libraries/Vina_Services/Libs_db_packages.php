<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/5/2017
 * Time: 11:13 AM
 */
class Libs_db_packages
{
    protected $CI;
    /**
     * Libs_db_packages constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * Get Data
     * @param string $packageId
     * @param string $serviceId
     * @return mixed
     */
    public function get_data($packageId = '', $serviceId = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-Packages-' . md5($packageId . $serviceId);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$result = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_packages_model');
            $result = $this->CI->db_packages_model->get_info($packageId);
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
        if (isset($this->CI->db_packages_model) && is_object($this->CI->db_packages_model)) {
            $this->CI->db_packages_model->close();
        }
    }

    public function get_data_code($packageCode = '', $serviceId = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-Packages-Code-' . md5($packageCode . $serviceId);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$result = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_packages_model');
            $result = $this->CI->db_packages_model->get_info($packageCode,'packageCode');
            if ($result !== null)
            {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
            $this->CI->db_packages_model->close();
        }
        return $result;
    }
}
