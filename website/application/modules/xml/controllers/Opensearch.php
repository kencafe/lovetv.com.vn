<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/23/18
 * Time: 10:28
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Opensearch
 *
 * @property object config
 * @property object output
 */
class Opensearch extends MX_Controller
{
    const CACHE_TTL = 31536000;
    private $webBuilderSdk;

    /**
     * Opensearch constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'assets'));
        $this->load->library('Site/db_config');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Open Search
     *
     * @link /opensearch.xml
     * @link /xml/opensearch
     */
    public function index()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlOpenSearch($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parse();
        $this->load->view('response', ['response' => $module->toXml()]);
    }
}
