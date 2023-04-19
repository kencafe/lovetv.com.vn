<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/25/2018
 * Time: 11:20 AM
 */
class Libs_db_cdr_logs
{
    protected $CI;
    /**
     * Libs_db_cdr_logs constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function array_diff($list_cdr_viettel = array(), $list_cdr_server = array())
    {
        $list_file_success = [];
        foreach ($list_cdr_server as $key => $value)
        {
            $list_file_success[] = $value->file_part;
        }
        return array_diff($list_cdr_viettel, $list_file_success);
    }

    /**
     * Get Data ConfigId
     * @param string $configId
     * @return mixed
     */
    public function get_data($date = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-CDR_logs-' . md5($date);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$result = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_cdr_log_model');
            $result = $this->CI->db_cdr_log_model->get_value($date, 'id', 'status');
            if ($result !== null)
            {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
            $this->CI->db_cdr_log_model->close();
        }
        return $result;
    }

    /**
     * Close DB
     */
    public function close()
    {
        if (isset($this->CI->db_cdr_log_model) && is_object($this->CI->db_cdr_log_model)) {
            $this->CI->db_cdr_log_model->close();
        }
    }
}
