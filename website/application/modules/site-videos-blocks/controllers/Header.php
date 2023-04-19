<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Header
 *
 * @property object config
 */
class Header extends MX_Controller
{
    const TPL_HEADER_FOLDER = 'header/';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleHeader */
    private $module;

    /**
     * Header constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'assets', 'ip'));
        $this->load->library(array('seo', 'session'));
        $this->theme_name = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleHeader($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Function master_header
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 13:41
     *
     */
    public function master_header()
    {
        $data = array();
        $this->load->view(self::TPL_HEADER_FOLDER . 'master_header', $data);
    }

    /**
     * Function header_mobile
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:21
     *
     */
    public function header_mobile()
    {
        $this->load->view('response', ['response' => $this->module->parseHeaderMobile()->toHtml()]);
    }

    /**
     * Function header_mobile_search_form
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:23
     *
     */
    public function header_mobile_search_form()
    {
        $this->load->view('response', ['response' => $this->module->parseHeaderMobileSearchForm()->toHtml()]);
    }

    /**
     * Function header_user_bar
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:19
     *
     */
    public function header_user_bar()
    {
        $data = array();
        $this->load->view(self::TPL_HEADER_FOLDER . 'header_user_bar', $data);
    }

    /**
     * Function header_search_form
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:20
     *
     */
    public function header_search_form()
    {
        $this->load->view('response', ['response' => $this->module->parseHeaderSiteSearchForm()->toHtml()]);
    }
}
