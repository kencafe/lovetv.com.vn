<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Footer
 *
 * @property object config
 * @property object db_config
 */
class Footer extends MX_Controller
{
    const TPL_MASTER        = 'empty';
    const TPL_FOOTER_FOLDER = 'footer/';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleFooter */
    private $module;

    /**
     * Footer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'text', 'assets']);
        $this->load->library(['seo', 'Site/db_config']);
        $this->theme_name = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModuleFooter($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Footer Site
     *
     * @link /site-blocks/footer/index.html
     */
    public function index()
    {
        $data = [];
        $this->load->view(self::TPL_FOOTER_FOLDER . 'footer', $data);
    }

    /**
     * Thông tin tòa soạn
     *
     * @link /site-blocks/footer/thong_tin_toa_soan.html
     */
    public function thong_tin_toa_soan()
    {
        $this->load->view('thong_tin_toa_soan', ['data' => $this->module->parseThongTinToaSoan()->getResponse()]);
    }

    /**
     * Thông tin liên hệ
     *
     * @link /site-blocks/footer/contact.html
     */
    public function contact()
    {
        $this->load->view('response', ['response' => $this->module->parseContact()->toHtml()]);
    }

    /**
     * Function connect_us
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-16 14:32
     *
     */
    public function connect_us()
    {
        $this->load->view('response', ['response' => $this->module->parseConnectUs()->toHtml()]);
    }

    /**
     * Thông tin bản quyền
     *
     * @link /site-blocks/footer/copyright_row.html
     */
    public function copyright_row()
    {
        $this->load->view('response', ['response' => $this->module->parseCopyrightRow()->toHtml()]);
    }
}
