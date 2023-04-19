<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/15/18
 * Time: 15:37
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Pages
 *
 * @property object config
 * @property object seo
 * @property object cache
 * @property object input
 * @property object db_config
 */
class Pages extends MX_Controller
{
    const CONTENT_REFRESH = 86400;
    const URL_404         = 'notify/error';
    const TPL_MASTER      = 'index';
    const TPL_FOLDER_PAGE = 'page/';

    public $ReCaptchaStatus;
    public $ReCaptcha;
    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Pages constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form', 'text', 'assets', 'array'));
        $this->load->library(array('seo', 'ip_libs', 'Site/db_config'));
        // Init Google reCAPTCHA
        $this->config->load('config_recaptcha');
        $this->ReCaptchaStatus = config_item('ReCaptchaStatus');
        $this->ReCaptcha       = config_item('ReCaptcha');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Function info_page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 20:27
     *
     * @param string $page_slug
     * @param string $page_encode_id
     *
     * @link  /pages/slugs-pageId.html
     * @link  /pages/gioi-thieu-ve-chung-toi-page4XGpEe.html
     */
    public function info_page($page_slug = '', $page_encode_id = '')
    {
        if (empty($page_slug) || empty($page_encode_id)) {
            redirect();
        }
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\InfoPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->parse($page_slug, $page_encode_id);
            $data = $module->getResponse();
            $this->load->view(self::TPL_MASTER, array(
                'sub'  => self::TPL_FOLDER_PAGE . 'info_page',
                'data' => $data
            ));
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }

    /**
     * Function redirect_info_page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 20:28
     *
     * @param string $page_uid
     *
     * @link  /p/uid.html
     */
    public function redirect_info_page($page_uid = '')
    {
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\InfoPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->redirectInfoPage($page_uid);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
