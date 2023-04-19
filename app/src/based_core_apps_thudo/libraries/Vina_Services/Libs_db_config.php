<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/13/2017
 * Time: 10:39 AM
 */
class Libs_db_config
{
    protected $CI;
    /**
     * Libs_db_config constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * Get Data
     * @param string $configId
     * @return mixed
     */
    public function get_data($configId = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-Services-Config-' . md5($configId);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$result = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_config_model');
            $result = $this->CI->db_config_model->get_value($configId, 'id', 'value');
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
        if (isset($this->CI->db_config_model) && is_object($this->CI->db_config_model)) {
            $this->CI->db_config_model->close();
        }
    }
}
