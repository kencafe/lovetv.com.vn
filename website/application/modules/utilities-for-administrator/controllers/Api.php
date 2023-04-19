<?php
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 8/30/2017
 * Time: 10:47 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Api
 *
 * @property object $config
 * @property object $cache
 * @property object $input
 * @property object $output
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
        $this->load->helper(array('url', 'string'));
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
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        $type     = $this->input->get_post('type', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => 2,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => 3,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->driver('cache', array(
                'adapter' => 'apc',
                'backup'  => 'file'
            ));
            if ($type === 'info') {
                $response = array(
                    'result'       => 0,
                    'desc'         => 'Lấy thông tin Cache',
                    'serviceName'  => config_item('cms_site_name'),
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details'      => array(
                        'info' => $this->cache->cache_info()
                    )
                );
            } else {
                $response = array(
                    'result'       => 0,
                    'desc'         => 'Xóa Cache',
                    'serviceName'  => config_item('cms_site_name'),
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details'      => array(
                        'info'  => $this->cache->cache_info(),
                        'clean' => $this->cache->clean()
                    )
                );
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }

    /**
     * API lấy thông tin Cache
     *
     * @link /admin/api/v1/cache-info.html
     */
    public function cache_info()
    {
        $username = $this->input->get_post('username', TRUE);
        $password = $this->input->get_post('password', TRUE);
        $cache_id = $this->input->get_post('cache_id', TRUE);
        $delete   = $this->input->get_post('delete', TRUE);
        if ($username === NULL || $password === NULL) {
            $response = array(
                'result' => 2,
                'desc'   => 'Sai hoặc thiếu tham số'
            );
        } elseif ($username != $this->auth['username'] || $password != $this->auth['password']) {
            $response = array(
                'result' => 3,
                'desc'   => 'Sai chữ ký xác thực'
            );
        } else {
            $this->load->driver('cache', array(
                'adapter' => 'apc',
                'backup'  => 'file'
            ));
            if (strtoupper($delete) === 'YES') {
                $response = array(
                    'result'       => 0,
                    'desc'         => 'Lấy thông tin & Xóa Cache',
                    'serviceName'  => config_item('cms_site_name'),
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details'      => array(
                        'info'   => $this->cache->get_metadata(GLOBAL_CACHE_PREFIX . $cache_id),
                        'delete' => $this->cache->delete(GLOBAL_CACHE_PREFIX . $cache_id)
                    )
                );
            } else {
                $response = array(
                    'result'       => 0,
                    'desc'         => 'Lấy thông tin Cache',
                    'serviceName'  => config_item('cms_site_name'),
                    'cache_prefix' => GLOBAL_CACHE_PREFIX,
                    'details'      => array(
                        'info' => $this->cache->get_metadata(GLOBAL_CACHE_PREFIX . $cache_id)
                    )
                );
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response))->_display();
        exit();
    }
}
