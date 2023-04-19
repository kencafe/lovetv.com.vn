<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/6/18
 * Time: 02:51
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Site_boxed
 *
 * @property object config
 * @property object cache
 * @property object db_category
 */
class Site_boxed extends MX_Controller
{
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleSiteBoxed */
    private $module;

    /**
     * Site_boxed constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets'));
        $this->load->library(array('seo', 'Site/db_config'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleSiteBoxed($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Function post_boxed_main_block_category
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-16 15:37
     *
     * @param array $data_boxed
     */
    public function post_boxed_main_block_category($data_boxed = array())
    {
        $this->load->view('response', ['response' => $this->module->parsePostBoxedMainBlockByCategory($data_boxed)->toHtml()]);
    }

}
