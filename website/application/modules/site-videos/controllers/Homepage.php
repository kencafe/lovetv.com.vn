<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 09:57
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Homepage
 *
 * @property object config
 */
class Homepage extends MX_Controller
{
    const TPL_MASTER = 'index';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Homepage constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'assets'));
        $this->load->library(array('seo', 'Site/db_config', 'Site/db_option'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Homepage - Sử dụng Web Builder SDK
     *
     * @link    /sites/homepage/index.html
     * @see     https://github.com/nguyenanhung/web-builder-sdk/blob/master/src/Module/DefaultHomePage.php
     */
    public function index()
    {
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\DefaultHomePage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->parse();
            $data = $module->getResponse();
            $this->load->view(self::TPL_MASTER, array(
                'sub'  => 'homepage',
                'data' => $data
            ));
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            show_error('Website is Maintenance! Please contact webmaster!');
        }
    }
}
