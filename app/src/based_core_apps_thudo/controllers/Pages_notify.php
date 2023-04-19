<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pages_notify extends CI_Controller
{
    protected $template;
    /**
     * Pages_notify constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array(
            'url',
            'assets',
            'html'
        ));
        $this->config->load('config_pages_custom');
        $this->load->library('parser');
        $this->template = 'Custom/';
    }
    /**
     * Maintenance Services
     *
     * @link /notify/index.html
     */
    public function index()
    {
        $this->load->view('welcome');
    }
    /**
     * Maintenance Services
     *
     * @link /notify/maintenance.html
     */
    public function maintenance()
    {
        $this->load->view($this->template . 'Maintenance');
    }
    /**
     * Coming soon template
     *
     * @link /notify/under-construction.html
     */
    public function under_construction()
    {
        $data = array(
            'title' => 'Coming Soon',
            'heading' => 'I\'ll be back',
            'site_name' => config_item('cms_site_name'),
            'site_author' => config_item('cms_author_name') . ' - ' . config_item('cms_author_email'),
            'url_assets' => assets_themes('Clouds'),
            'url_facebook' => 'http://thudomultimedia.vn',
            'url_twitter' => 'http://thudomultimedia.vn',
            'url_briefcase' => 'http://thudomultimedia.vn',
            'url_transit' => 'http://thudomultimedia.vn'
        );
        $this->parser->parse($this->template . 'Clouds_under_construction', $data);
    }
    /**
     * Error Template
     *
     * @link /notify/error.html
     */
    public function error404()
    {
        $data = array(
            'name' => '404',
            'title' => 'PAGE NOT FOUND',
            'heading' => 'The page you requested was not found.',
            'site_name' => config_item('cms_site_name'),
            'site_author' => config_item('cms_author_name') . ' - ' . config_item('cms_author_email'),
            'site_link' => config_item('base_url'),
            'url_assets' => assets_themes('Sailors'),
            'url_facebook' => 'http://thudomultimedia.vn',
            'url_twitter' => 'http://thudomultimedia.vn',
            'url_briefcase' => 'http://thudomultimedia.vn',
            'url_transit' => 'http://thudomultimedia.vn'
        );
        $this->parser->parse($this->template . 'Sailor_error', $data);
    }
}
/* End of file Pages_notify.php */
/* Location: ./based_core_apps_thudo/controllers/Pages_notify.php */
