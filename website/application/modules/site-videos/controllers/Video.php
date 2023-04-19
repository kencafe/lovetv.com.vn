<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/22/18
 * Time: 16:02
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Video
 *
 * @property object config
 * @property object cache
 * @property object db_config
 */
class Video extends MX_Controller
{
    const CONTENT_REFRESH   = 1800;
    const URL_404           = 'notify/error';
    const TPL_MASTER        = 'index';
    const ITEM_PER_PAGE     = 6;
    const HOT_ITEM_PER_PAGE = 1;
    const PAGE_PREFIX       = '/trang-';
    const PAGE_SUFFIX       = '.html';

    /** @var mixed|string Theme Name */
    public $theme_name;
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;

    /**
     * Video constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'html', 'text', 'assets', 'pagination']);
        $this->load->library(['seo', 'Site/db_config', 'Site/db_option', 'Site/db_comments']);
        $this->theme_name = config_item('template_name');
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Video Page
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-09 23:26
     *
     * @param int $page_number
     *
     * @link  /videos.html
     */
    public function index($page_number = 1)
    {
        try {
            $pageMeta = [
                'page_id'       => 'video',
                'page_content'  => [
                    'name'        => 'Video Clip',
                    'title'       => 'Video Clip mới nhất',
                    'description' => 'Video Clip mới nhất',
                    'keywords'    => 'Video Clip mới nhất',
                    'photo'       => assets_url('logo/logo-default.jpg'),
                    'slugs'       => 'videos',
                    'created_at'  => date('Y-m-d'),
                    'db_filter'   => [
                        'is_hot' => FALSE
                    ]
                ],
                'page_number'   => $page_number,
                'item_per_page' => 17
            ];
            $module   = new \nguyenanhung\WebBuilderSDK\Module\LatestVideoPostByPostTypePage($this->webBuilderSdk['OPTIONS']);
            $module->setSdkConfig($this->webBuilderSdk)->setPageMeta($pageMeta)->parse();
            $data = $module->getResponse();
            // Load views
            $this->load->view(self::TPL_MASTER, [
                'sub'  => 'video/video_list',
                'data' => $data
            ]);
        }
        catch (Exception $e) {
            log_message('error', 'File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage());
            redirect();
        }
    }
}
