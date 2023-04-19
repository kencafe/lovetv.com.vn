<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Menu
 *
 * @property object config
 * @property object seo
 * @property object cache
 * @property object uri
 * @property object db_config
 * @property object category_model
 * @property object pages_model
 */
class Menu extends MX_Controller
{
    const CACHE_TTL           = 2592000; // Cache 30 ngày luôn
    const CACHE_MODULE_PREFIX = 'SITE_BLOCKS_MENU';
    const TPL_MASTER          = 'empty';
    const TPL_MENU_FOLDER     = 'menu/';
    private $configMenu;

    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleMenu */
    private $module;

    /**
     * Menu constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'assets']);
        $this->load->library('seo');
        $this->configMenu = config_item('config_menu_video_tv');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleMenu($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Function master_menu
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 13:53
     *
     */
    public function master_menu()
    {
        $data = array();
        $this->load->view(self::TPL_MENU_FOLDER . 'master_menu', $data);
    }

    /**
     * Function category_menu
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:49
     *
     */
    public function category_menu()
    {
        $data = $this->module->setMenuConfig($this->configMenu)->setSegment($this->uri->segment(1))->parserCategoryMenu()->toHtml();
        $this->load->view('response', ['response' => $data]);
    }

    /**
     * Function user_menu
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-20 14:47
     *
     */
    public function user_menu()
    {
        $data = $this->module->parserUserMenu()->getResponse();
        $this->load->view(self::TPL_MENU_FOLDER . 'user_menu', $data);
        // $this->load->view('response', ['response' => $this->module->parserUserMenu()->toHtml()]);
    }
}
