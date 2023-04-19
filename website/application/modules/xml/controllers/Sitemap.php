<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/23/18
 * Time: 14:01
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Sitemap
 *
 * @property object config
 * @property object output
 */
class Sitemap extends MX_Controller
{
    const CACHE_TTL  = 3600;
    const TPL_MASTER = 'index';
    const TPL_FOLDER = 'sitemap/';
    private $webBuilderSdk;

    /**
     * Sitemap constructor.
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
     * Site map Index
     *
     * @link /sitemap.xml
     * @link /xml/sitemap/sitemap_index
     */
    public function sitemap_index()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseIndexSiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Category
     *
     * @link /sitemap/category.xml
     * @link /xml/sitemap/sitemap_list_category
     */
    public function sitemap_list_category()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseListCategorySiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Topic
     *
     * @link /sitemap/topic.xml
     * @link /xml/sitemap/sitemap_list_topic
     */
    public function sitemap_list_topic()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseListTopicSiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Tags
     *
     * @link /sitemap/tags.xml
     * @link /xml/sitemap/sitemap_list_tags
     */
    public function sitemap_list_tags()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseListTagsSiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Latest Post
     *
     * @link /sitemap/latest-post.xml
     * @link /xml/sitemap/sitemap_latest_post
     */
    public function sitemap_latest_post()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseLatestImagePostsSiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Latest Video
     *
     * @link /sitemap/latest-video.xml
     * @link /xml/sitemap/sitemap_latest_video
     */
    public function sitemap_latest_video()
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseLatestVideoPostsSiteMap();
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Post by Category
     *
     * @link  /sitemap/category/$cat_slug
     * @link  /xml/sitemap/sitemap_latest_post_by_category/$cat_slug
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-08 00:01
     *
     * @param string $cat_slug
     */
    public function sitemap_latest_post_by_category($cat_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseLatestPostsByCategorySiteMap($cat_slug);
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Post by Topic
     *
     * @link  /sitemap/chu-de/$topic_slug
     * @link  /xml/sitemap/sitemap_latest_post_by_topic/$topic_slug
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-08 00:01
     *
     * @param string $topic_slug
     */
    public function sitemap_latest_post_by_topic($topic_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseLatestPostsByTopicSiteMap($topic_slug);
        $this->load->view('response', ['response' => $module->toXml()]);
    }

    /**
     * Site map List Post by Tags
     *
     * @link  /sitemap/tags/$tag_slug
     * @link  /xml/sitemap/sitemap_latest_post_by_tags/$tag_slug
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-08 00:02
     *
     * @param string $tag_slug
     */
    public function sitemap_latest_post_by_tags($tag_slug = '')
    {
        $this->output->set_status_header(200)->set_content_type('application/xml', 'utf-8')->cache(self::CACHE_TTL);
        $module = new \nguyenanhung\WebBuilderSDK\ModuleXML\XmlSiteMap($this->webBuilderSdk['OPTIONS']);
        $module->setSdkConfig($this->webBuilderSdk)->parseLatestPostsByTagsSiteMap($tag_slug);
        $this->load->view('response', ['response' => $module->toXml()]);
    }
}
