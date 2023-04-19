<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/10/18
 * Time: 14:22
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Posts
 *
 * @property object config
 * @property object seo
 * @property object cache
 * @property object input
 * @property object db_config
 * @property object db_postmeta
 * @property object msisdn
 * @property object auth
 */
class Posts extends MX_Controller
{
    const CONTENT_REFRESH      = 86400;
    const URL_404              = 'notify/error';
    const TPL_MASTER           = 'index';
    const TPL_FOLDER_POST      = 'post/';
    const TPL_FOLDER_VIDEO     = 'video/';
    const TPL_FOLDER_IMAGE     = 'image/';
    const IMAGE_SMALL_META_KEY = 'image_small';
    const TPL_PRINT_MASTER     = 'print/index';
    const TPL_PRINT_VIEWED     = 'print/print_view';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Posts constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets', 'pagination'));
        $this->load->library(array('seo', 'Site/db_config', 'Site/db_postmeta', 'msisdn', 'auth'));
        $this->config->load('config_vas_telcos');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Trang chi tiết tin bài - Sử dụng Web Builder SDK
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 21:44
     *
     * @param string $categorySlug Đường dẫn danh mục
     * @param string $postSlug     Đường dẫn đến bài viết
     * @param string $postUid      Uid encode postId
     *
     * @link  /Danh-muc/ten-bai-viet-postUid.html
     */
    public function index($categorySlug = '', $postSlug = '', $postUid = '')
    {
        if (empty($categorySlug) || empty($postSlug) || empty($postUid)) {
            redirect();
        }
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\PostsDetailsPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setBaseMsisdn($this->msisdn)->setBaseAuth($this->auth)->parse($categorySlug, $postSlug, $postUid);
            $data = $module->getResponse();
            // Lưu log lịch sử người dùng
            $historyStatus       = TRUE;
            $historyUsername     = $this->msisdn->getMsisdnInSession();
            $historyData         = [];
            $historyUserData     = [
                'userInfo'       => $this->msisdn->getSessionData('CURRENT_USER_GET_INFO'),
                'categoryConfig' => config_item('category_config'),
            ];
            $historyTemplateData = [
                'site_name'           => config_item('cms_site_name'),
                'config_sign_in_link' => site_url('users/login'),
                'config_sign_up_link' => site_url('users/sign-up'),
            ];
            $module->setHistoryStatus($historyStatus)->setHistoryTemplateData($historyTemplateData)->setHistoryUsername($historyUsername)->setHistoryUserData($historyUserData)->setHistoryData($historyData)->parseContentTemplate();
            $contentTemplate = $module->getContentTemplateResult();
            if ($historyStatus === TRUE) {
                $data['fullContent']     = $contentTemplate['fullContent'];
                $data['countHistory']    = $contentTemplate['countHistory'];
                $data['templateFile']    = $contentTemplate['templateFile'];
                $data['contentTemplate'] = $contentTemplate['contentTemplate'];
            }
            $historyTemplate = isset($data['templateFile']) && !empty($data['templateFile']) ? $data['templateFile'] : NULL;
            $module->setHistoryTemplate($historyTemplate)->createDataHistory($data['content']);
            // Load views
            $printer = $this->input->get('printer', TRUE);
            if (isset($printer) && ($printer == TRUE || strtoupper($printer) == 'true')) {
                $this->load->view(self::TPL_PRINT_MASTER, array(
                    'sub'  => self::TPL_PRINT_VIEWED,
                    'data' => $data
                ));
            } else {
                $this->load->view(self::TPL_MASTER, array(
                    'sub'  => $data['view_template'],
                    'data' => $data
                ));
            }
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }

    /**
     * Function redirect
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 20:19
     *
     * @param string $postUid
     */
    public function redirect($postUid = '')
    {
        try {
            $module = new \nguyenanhung\WebBuilderSDK\Module\PostsDetailsPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->redirectPost($postUid);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
