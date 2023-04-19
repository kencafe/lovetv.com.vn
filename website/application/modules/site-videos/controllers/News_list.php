<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/9/18
 * Time: 11:19
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class News_list
 *
 * @property object cache
 * @property object config
 * @property object seo
 * @property object db_config
 */
class News_list extends MX_Controller
{
    const CONTENT_REFRESH   = 1800;
    const URL_404           = 'notify/error';
    const TPL_MASTER        = 'index';
    const ITEM_PER_PAGE     = 10;
    const HOT_ITEM_PER_PAGE = 1;
    const PAGE_PREFIX       = '/trang-';
    const PAGE_SUFFIX       = '.html';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * News_list constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'text', 'assets', 'pagination']);
        $this->load->library(['seo', 'Site/db_config', 'Site/db_option', 'Site/db_category']);
        $this->theme_name = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Function _prepare
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 21:56
     *
     * @param string $method
     * @param string $page_id
     *
     * @return array|null
     */
    private function _prepare($method = '', $page_id = '')
    {
        if ($method == 'latest') {
            if ($page_id == 'new') {
                return [
                    'name'        => 'Tin mới',
                    'title'       => 'Tin mới nhất',
                    'description' => 'Hàng ngàn tin bài mới nhất về Sao showbiz được cập nhật từng phút',
                    'keywords'    => 'tin mới, tin nóng, new daily, tin tức, tin cập nhật hàng ngày',
                    'photo'       => assets_url('logo/logo-default.jpg'),
                    'slugs'       => 'tin-moi',
                    'created_at'  => $this->db_config->get_data('site_created_at'),
                    'feed'        => [
                        'url'   => base_url('rss/latest.rss'),
                        'title' => 'Tin mới nhất'
                    ],
                    'db_filter'   => [
                        'is_hot' => FALSE
                    ]
                ];
            }
            if ($page_id == 'hot') {
                return [
                    'name'        => 'Tin HOT',
                    'title'       => 'Tin HOT nhất',
                    'description' => 'Hàng ngài tìn bai HOT về Sao showbiz được cập nhật từng phút',
                    'keywords'    => 'tin hot, hot news, tin nóng, showbiz nóng bỏng, showbiz Việt, showbiz Quốc tế, thị trường SAO',
                    'photo'       => assets_url('logo/logo-default.jpg'),
                    'slugs'       => 'tin-hot',
                    'feed'        => [
                        'url'   => base_url('rss/hot-news.rss'),
                        'title' => 'Tin hot nhất'
                    ],
                    'created_at'  => date('Y-m-d'),
                    'db_filter'   => [
                        'is_hot' => TRUE
                    ]
                ];
            }
        }

        return NULL;
    }

    /**
     * Latest News
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/17/18 12:45
     *
     * @param string $page_id
     * @param int    $page_number
     */
    public function latest($page_id = '', $page_number = 1)
    {
        $page_content = self::_prepare(__FUNCTION__, $page_id);
        if ($page_content === NULL) {
            redirect();
        }
        try {
            $pageMeta = [
                'page_id'       => $page_id,
                'page_content'  => $page_content,
                'page_number'   => $page_number,
                'item_per_page' => 17
            ];
            $module   = new \nguyenanhung\WebBuilderSDK\Module\LatestPostByPostTypePage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $module->getResponse();
            // Load views
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'news_list',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }

    /**
     * Topics News
     *
     * @link  /chu-de/topic-slug/trang-1.html
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/17/18 12:45
     *
     * @param string $topic_slug
     * @param int    $page_number
     */
    public function topic($topic_slug = '', $page_number = 1)
    {
        try {
            $pageMeta = [
                'topic_slug'    => $topic_slug,
                'page_number'   => $page_number,
                'item_per_page' => 17
            ];
            $module   = new \nguyenanhung\WebBuilderSDK\Module\ListPostByTopicPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $module->getResponse();
            // Load views
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'news_topic',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }

    /**
     * Tags News
     *
     * @link  /tags/tag-slug/trang-1.html
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/17/18 12:45
     *
     * @param string $tag_slugs
     * @param int    $page_number
     */
    public function tags($tag_slugs = '', $page_number = 1)
    {
        try {
            $pageMeta = [
                'tag_slug'      => $tag_slugs,
                'page_number'   => $page_number,
                'item_per_page' => 17
            ];
            $module   = new \nguyenanhung\WebBuilderSDK\Module\ListPostByTagsPage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $module->getResponse();
            // Load views
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'news_tag',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
