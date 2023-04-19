<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/30/2017
 * Time: 10:47 AM
 */
class Api extends MX_Controller
{
    protected $auth;
    /**
     * Api constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'string'
        ));
        $this->load->library(array(
            'phone_number'
        ));
        $this->config->load('admin_config');
        $this->auth = config_item('authentication');
    }
    /**
     * API Clean Cache
     *
     * @link /admin/api/v1/clean-cache.html
     */
    public function clean_cache()
    {
        $username = $this->input->get_post('username', true);
        $password = $this->input->get_post('password', true);
        $type     = $this->input->get_post('type', true);
        if ($username === null || $password === null)
        {
            $response = array(
                'result' => 2,
                'desc' => 'Sai hoặc thiếu tham số'
            );
        }
        elseif ($username != $this->auth['username'] || $password != $this->auth['password'])
        {
            $response = array(
                'result' => 3,
                'desc' => 'Sai chữ ký xác thực'
            );
        }
        else
        {
            $this->load->driver('cache', array(
                'adapter' => 'apc',
                'backup' => 'file'
            ));
            if ($type === 'info')
            {
                $response = array(
                    'result' => 0,
                    'desc' => 'Lấy thông tin Cache',
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details' => array(
                        'info' => $this->cache->cache_info()
                    )
                );
            }
            else
            {
                $response = array(
                    'result' => 0,
                    'desc' => 'Xóa Cache',
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details' => array(
                        'info' => $this->cache->cache_info(),
                        'clean' => $this->cache->clean()
                    )
                );
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response))
            ->_display();
        // Exit
        exit();
    }
}
