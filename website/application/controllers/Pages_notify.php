<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Pages_notify
 *
 * @property object config
 * @property object parser
 */
class Pages_notify extends CI_Controller
{
    protected $template;

    /**
     * Pages_notify constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('assets');
        $this->load->helper('html');
        $this->config->load('config_template');
        $this->load->library('parser');
        $this->template = 'Custom/';
    }

    /**
     * Maintenance Services
     */
    public function index()
    {
        $this->load->view('welcome');
    }

    /**
     * Maintenance Services
     */
    public function maintenance()
    {
        $this->load->view($this->template . 'Maintenance');
    }

    /**
     * Coming soon template
     */
    public function under_construction()
    {
        $data = [
            'title'         => 'Coming Soon',
            'heading'       => 'I\'ll be back',
            'site_name'     => config_item('cms_site_name'),
            'site_author'   => config_item('cms_author_name') . ' - ' . config_item('cms_author_email'),
            'url_assets'    => assets_themes('Clouds'),
            'url_facebook'  => site_url(),
            'url_twitter'   => site_url(),
            'url_briefcase' => site_url(),
            'url_transit'   => site_url()
        ];
        $this->parser->parse($this->template . 'Clouds_under_construction', $data);
    }

    /**
     * Error Template
     */
    public function error404()
    {
        $data = [
            'name'          => '404',
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
        $this->parser->parse($this->template . 'Sailor_error', $data);
    }
}
