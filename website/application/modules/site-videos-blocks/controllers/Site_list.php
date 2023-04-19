<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/8/18
 * Time: 11:59
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Site_list
 *
 * @property object config
 */
class Site_list extends MX_Controller
{
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleSiteLists */
    private $module;

    /**
     * Site_list constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets'));
        $this->load->library(array('seo', 'Site/db_config'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleSiteLists($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Function generate_site_list_pagination
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 10:27
     *
     * @param array $dataPagination
     */
    public function generate_site_list_pagination($dataPagination = array())
    {
        $this->load->view('response', ['response' => $this->module->generateSiteListPagination($dataPagination)->toHtml()]);
    }

    /**
     * Function section_list_video
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 11:23
     *
     * @param array $list_item
     */
    public function section_list_video($list_item = array())
    {
        $this->load->view('response', ['response' => $this->module->parseSiteListSectionListVideo($list_item)->toHtml()]);
    }
}
