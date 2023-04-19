<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/14/18
 * Time: 15:38
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Post_boxed
 *
 * @property object config
 * @property object cache
 */
class Post_boxed extends MX_Controller
{
    /** @var mixed|array SDK Config */
    private $webBuilderSdk;
    /** @var object \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModulePostBoxed */
    private $module;

    /**
     * Post_boxed constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'text', 'assets'));
        $this->load->library(array('seo', 'Site/db_config', 'Site/db_category'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
        $this->module        = new \nguyenanhung\WebBuilderSDK\ModuleVideoBlocks\ModulePostBoxed($this->webBuilderSdk['OPTIONS']);
        $this->module->setSdkConfig($this->webBuilderSdk);
    }

    /**
     * Function no_player
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-19 11:07
     *
     */
    public function no_player()
    {
        $this->load->view('response', ['response' => $this->module->parseBoxedContentNoPlayer()->toHtml()]);
    }

    /**
     * Nội dung tương tự - đề xuất - mới nhất
     *
     * @link  /site-videos-blocks/post_boxed/right_latest_recommended_video_by_category/$data_boxed
     *
     * @param array $data_boxed
     */
    public function right_latest_recommended_video_by_category($data_boxed = array())
    {
        $this->load->view('response', ['response' => $this->module->parsePostBoxedLatestRecommendVideoByCategory($data_boxed)->toHtml()]);
    }

    /**
     * Nội dung tương tự - đề xuất - mới nhất
     *
     * @link  /site-videos-blocks/post_boxed/right_random_recommended_video_by_category/$data_boxed
     *
     * @param array $data_boxed
     */
    public function right_random_recommended_video_by_category($data_boxed = array())
    {
        $this->load->view('response', ['response' => $this->module->parsePostBoxedRandomRecommendVideoByCategory($data_boxed)->toHtml()]);
    }
}
