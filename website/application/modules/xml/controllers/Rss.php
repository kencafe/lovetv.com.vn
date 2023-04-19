<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/23/18
 * Time: 14:01
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Rss
 *
 * @property object config
 * @property object output
 */
class Rss extends MX_Controller
{
    const CACHE_TTL  = 3600;
    const TPL_MASTER = 'index';
    const TPL_FOLDER = 'rss/';
    private $webBuilderSdk;

    /**
     * Rss constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'xml', 'assets'));
        $this->load->library(array('seo', 'Site/db_config'));
        $this->config->load('config_web_builder_sdk');
        $this->webBuilderSdk = config_item('web_builder_sdk_config');
    }

    /**
     * Rss feed Index
     *
     * @link /rss/index.rss
     * @link /xml/rss/rss_index
     */
    public function rss_index()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $rss = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlRss($this->webBuilderSdk['OPTIONS']);
        $rss->setSdkConfig($this->webBuilderSdk)->parseLatestPostsRss();
        $this->load->view('response', ['response' => $rss->toXml()]);
    }

    /**
     * Rss feed Video
     *
     * @link /rss/videos.rss
     * @link /xml/rss/rss_index
     */
    public function rss_video()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $rss = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlRss($this->webBuilderSdk['OPTIONS']);
        $rss->setSdkConfig($this->webBuilderSdk)->parseLatestVideoPostRss();
        $this->load->view('response', ['response' => $rss->toXml()]);
    }

    /**
     * Rss feed Category
     *
     * @param string $cat_slug
     *
     * @link  /rss/category/(:any).rss
     * @link  /xml/rss/rss_category/$cat_slug
     */
    public function rss_category($cat_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $rss = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlRss($this->webBuilderSdk['OPTIONS']);
        $rss->setSdkConfig($this->webBuilderSdk)->parseLatestImagePostsByCategoryRss($cat_slug);
        $this->load->view('response', ['response' => $rss->toXml()]);
    }

    /**
     * Rss feed Topic
     *
     * @param string $topic_slug
     *
     * @link  /rss/chu-de/(:any).rss
     * @link  /xml/rss/rss_topic/$topic_slug
     */
    public function rss_topic($topic_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $rss = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlRss($this->webBuilderSdk['OPTIONS']);
        $rss->setSdkConfig($this->webBuilderSdk)->parseLatestImagePostsByTopicRss($topic_slug);
        $this->load->view('response', ['response' => $rss->toXml()]);
    }

    /**
     * Rss feed Tags
     *
     * @param string $tag_slug
     *
     * @link  /rss/tags/(:any).rss
     * @link  /xml/rss/rss_tags/$tag_slug
     */
    public function rss_tags($tag_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $rss = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlRss($this->webBuilderSdk['OPTIONS']);
        $rss->setSdkConfig($this->webBuilderSdk)->parseLatestImagePostsByTagsRss($tag_slug);
        $this->load->view('response', ['response' => $rss->toXml()]);
    }
}
