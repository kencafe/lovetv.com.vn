<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/7/18
 * Time: 16:52
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class News_category
 *
 * @property object cache
 * @property object config
 * @property object db_config
 * @property object db_option
 * @property object db_category
 */
class News_category extends MX_Controller
{
    const USE_TEMPLATE_MULTI_CATEGORY = TRUE; // true nếu load giao diện multi_category khi khả dụng
    const TPL_MASTER                  = 'index';
    const TPL_FOLDER_CATEGORY         = 'category/';
    const TPL_MULTI_CATEGORY          = 'multi_category';
    const TPL_ONE_CATEGORY            = 'category';
    const PAGE_PREFIX                 = '/trang-';
    const PAGE_SUFFIX                 = '.html';
    const RECURSIVE_CATEGORY          = TRUE; // Lấy tin ở sub-category

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * News_category constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets', 'pagination'));
        $this->load->library(array('seo', 'Site/db_config', 'Site/db_option', 'Site/db_category'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Trang Category - Sử dụng Web Builder SDK
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 21:26
     *
     * @param string $category_slug
     * @param int    $page_number
     */
    public function index($category_slug = '', $page_number = 1)
    {
        if (empty($category_slug)) {
            redirect();
        }
        try {
            $pageMeta = array('category_slug' => $category_slug, 'page_number' => $page_number, 'item_per_page' => 17);
            $module   = new \nguyenanhung\WebBuilderSDK\Module\ListPostByCategoryPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $module->getResponse();
            $this->load->view(self::TPL_MASTER, [
                'sub'  => self::TPL_FOLDER_CATEGORY . self::TPL_ONE_CATEGORY,
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
