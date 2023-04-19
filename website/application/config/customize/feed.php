<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/23/18
 * Time: 15:21
 */
// XML
$route['opensearch.xml']              = 'xml/opensearch/index';
// Sitemap
$route['sitemap/index.xml']           = 'xml/sitemap/sitemap_index';
$route['sitemap/category.xml']        = 'xml/sitemap/sitemap_list_category';
$route['sitemap/topic.xml']           = 'xml/sitemap/sitemap_list_topic';
$route['sitemap/tags.xml']            = 'xml/sitemap/sitemap_list_tags';
$route['sitemap/latest-post.xml']     = 'xml/sitemap/sitemap_latest_post';
$route['sitemap/latest-video.xml']    = 'xml/sitemap/sitemap_latest_video';
$route['sitemap/category/(:any).xml'] = 'xml/sitemap/sitemap_latest_post_by_category/$1';
$route['sitemap/chu-de/(:any).xml']   = 'xml/sitemap/sitemap_latest_post_by_topic/$1';
$route['sitemap/tags/(:any).xml']     = 'xml/sitemap/sitemap_latest_post_by_tags/$1';
// RSS
$route['rss/index.rss']               = 'xml/rss/rss_index';
$route['rss/latest.rss']              = 'xml/rss/rss_index';
$route['rss/hot-news.rss']            = 'xml/rss/rss_index';
$route['rss/videos.rss']              = 'xml/rss/rss_video';
$route['rss/category/(:any).rss']     = 'xml/rss/rss_category/$1';
$route['rss/chu-de/(:any).rss']       = 'xml/rss/rss_topic/$1';
$route['rss/tags/(:any).rss']         = 'xml/rss/rss_tags/$1';
