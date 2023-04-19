<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/5/2017
 * Time: 9:41 AM
 */
class Libs_db_mt_config
{
    protected $CI;
    /**
     * Libs_db_mt_config constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * Get Mt Data
     * @param string $command
     * @param string $state
     * @param string $type
     * @return mixed|null|string
     */
    public function get_data($command = '', $state = '', $type = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-MT-Config-' . md5($command . $state . $type);
        $cache_ttl  = 60 * 60 * 2; // luu cache 1 ngay
        if (!$msg = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_mt_config_model');
            $mt  = $this->CI->db_mt_config_model->get_data($command, $state, $type);
            $msg = (isset($mt->msg)) ? trim($mt->msg) : null;
            if ($msg !== null)
            {
                $this->CI->cache->save($cache_file, $msg, $cache_ttl);
            }
        }
        return $msg;
    }

    public function close()
    {
        if (isset($this->CI->db_mt_config_model) && is_object($this->CI->db_mt_config_model)) {
            $this->CI->db_mt_config_model->close();
        }
    }
}
