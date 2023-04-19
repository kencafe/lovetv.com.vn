<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/9/18
 * Time: 14:05
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Search
 *
 * @property object config
 * @property object input
 */
class Search extends MX_Controller
{
    const CONTENT_REFRESH = 1800;
    const URL_404         = 'notify/error';
    const TPL_MASTER      = 'index';
    const TPL_MODULE      = 'news_search';
    const ITEM_PER_PAGE   = 17;
    const PAGE_PREFIX     = '&page=';
    const PAGE_SUFFIX     = '';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets', 'pagination'));
        $this->load->library(array('seo', 'Site/db_config'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->theme_name    = config_item('template_name');
    }

    /**
     * Hàm tìm kiếm tin bài - Sử dụng Web Builder SDK
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-07 15:55
     */
    public function index()
    {
        try {
            // Cấu hình các tham số đầu vào cho module
            $keyword      = $this->input->get('q', TRUE);
            $type         = $this->input->get('post_type', TRUE);
            $page_number  = intval($this->input->get('page', TRUE));
            $current_page = $page_number <= 1 ? 1 : $page_number;
            $pageMeta     = [
                'inputQueryKeyword' => 'q',
                'inputQueryType'    => 'post_type',
                'keyword'           => $keyword,
                'type'              => $type,
                'page_number'       => $page_number,
                'current_page'      => $current_page,
                'item_per_page'     => self::ITEM_PER_PAGE,
                'page_prefix'       => self::PAGE_PREFIX,
                'page_suffix'       => self::PAGE_SUFFIX
            ];
            // Call module
            $searchPost = new \nguyenanhung\WebBuilderSDK\Module\SearchPostPage($this->webBuilderSdk['OPTIONS']);
            $searchPost->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $searchPost->getResponse();
            // Load views
            $this->load->view(self::TPL_MASTER, array(
                'sub'  => self::TPL_MODULE,
                'data' => $data
            ));
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
