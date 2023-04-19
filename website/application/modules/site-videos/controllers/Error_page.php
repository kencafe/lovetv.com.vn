<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/10/18
 * Time: 13:53
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Error_page
 *
 * @property object config
 * @property object parser
 */
class Error_page extends MX_Controller
{
    /**
     * Error_page constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'assets'));
        $this->load->library(array('seo', 'parser', 'Site/db_config', 'Site/db_option'));
        $this->config->load('config_template');
        $this->load->library('parser');
    }

    /**
     * Function error_404
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 23:32
     *
     */
    public function error_404()
    {
        $data = [
            'name'          => 'Error 404',
            'title'         => 'PAGE NOT FOUND',
            'heading'       => 'The page you requested was not found.',
            'site_name'     => config_item('cms_site_name'),
            'site_author'   => config_item('cms_author_name') . ' - ' . config_item('cms_author_email'),
            'site_link'     => config_item('base_url'),
            'url_assets'    => assets_themes('Sailors'),
            'url_facebook'  => site_url(),
            'url_twitter'   => site_url(),
            'url_briefcase' => site_url(),
            'url_transit'   => site_url()
        ];
        $this->parser->parse('Custom/Sailor_error', $data);
    }
}
