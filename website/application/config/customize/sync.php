<?php
/**
 * Project project-vina-giai-tri-tong-hop-website.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/1/18
 * Time: 16:26
 */
// Sync
$route['sync/v1/category'] = 'sync/sync_db/category';
$route['sync/v1/topic']    = 'sync/sync_db/topic';
$route['sync/v1/tag']      = 'sync/sync_db/tag';
$route['sync/v1/post']     = 'sync/sync_db/post';
$route['sync/v1/pages']    = 'sync/sync_db/pages';
$route['sync/v1/youtube']  = 'sync/youtube/index';