<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 6/5/2017
 * Time: 10:35 AM
 */
class Libs_db_commands
{
    protected $CI;
    /**
     * Libs_db_commands constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    /**
     * Get Data
     * @param string $inputCommand
     * @param string $output_field
     * @return mixed
     */
    public function get_data($inputCommand = '', $output_field = '')
    {
        $this->CI->load->driver('cache', array(
            'adapter' => 'apc',
            'backup' => 'file'
        ));
        $command    = strtoupper($inputCommand);
        $cache_file = GLOBAL_CACHE_PREFIX . 'Get-Info-Data-Commands-' . md5($command . $output_field);
        $cache_ttl  = 60 * 60 * 24; // luu cache 1 ngay
        if (!$packageId = $this->CI->cache->get($cache_file))
        {
            $this->CI->load->model('Vina_Services/db_commands_model');
            $result = $this->CI->db_commands_model->get_value($command, 'commandId', $output_field);
            if ($result !== null)
            {
                $this->CI->cache->save($cache_file, $result, $cache_ttl);
            }
            $this->CI->db_commands_model->close();
        }
        return $result;
    }

    /**
     * Close DB
     */
    public function close()
    {
        if (isset($this->CI->db_commands_model) && is_object($this->CI->db_commands_model)) {
            $this->CI->db_commands_model->close();
        }
    }
}
