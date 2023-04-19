<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 16:52
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sign_up
 *
 * @property object $config
 * @property object $db_config
 */
class Sign_up extends MX_Controller
{
    const MODULE_NAME     = 'Đăng ký sử dụng dịch vụ';
    const CONTENT_REFRESH = 1800;
    const TPL_MASTER      = 'index';
    const TPL_MODULE      = 'sign_up';

    /** @var mixed|array Web SignUp Config */
    public $web_sign_up;
    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Sign_up constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'text', 'assets', 'pagination']);
        $this->load->library(['seo', 'auth', 'Site/db_config']);
        $this->config->load('config_vas_telcos');
        $this->web_sign_up = config_item('telco_web_sign_up');
        $this->theme_name  = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Module chào mừng đăng ký dịch vụ
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-10 00:14
     *
     * @link  /users/sign-up.html
     */
    public function index()
    {
        try {
            $callback = ['telco_status' => config_item('provider_telcos')];
            $module   = new \nguyenanhung\WebBuilderSDK\Module\DefaultSignUpPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setCallbackData($callback)->parse();
            $data = $module->getResponse();
            $this->load->view(self::TPL_MASTER, [
                'sub'  => self::TPL_MODULE,
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
