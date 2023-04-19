<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Các Router nghiệp vụ WEB/WAP
 */
// Users
$route['users/sign-up']                 = 'site-videos/sign_up/index';
$route['users/login']                   = 'site-videos/login/index';
$route['users/logout']                  = 'site-videos/login/logout';
$route['dang-nhap-1']                   = 'site-videos/login/index';
$route['dang-ky-1']                     = 'site-videos/sign_up/index';
$route['dang-xuat']                     = 'site-videos/login/logout';
$route['vinaphone/un-subscribe/(:any)'] = 'vasgate/vinaphone/unsubscribe/$1';
// Router Category
$route['help/(:any)']      = 'site-videos/help/markdown/$1';
$route['thong-bao/(:any)'] = 'site-videos/thong_bao/markdown/$1';
//Router Xổ số
$route['xoso'] = 'site-videos/lottery/index';
// Error Page
$route['error/e404'] = 'site-videos/error_page/error_404';
// Pages
$route['pages/thong-tin-toa-soan'] = 'site-videos/pages/about_us';
$route['pages/lien-he-toa-soan']   = 'site-videos/pages/feedback';
$route['pages/(:any)-page(:any)']  = 'site-videos/pages/info_page/$1/$2';
$route['p/(:any)']                 = 'site-videos/pages/redirect_info_page/$1';
// Video
$route['videos/trang-(:num)'] = 'site-videos/video/index/$1';
$route['videos']              = 'site-videos/video/index';
// Search News
$route['search'] = 'site-videos/search/index';
// Topics
$route['chu-de/(:any)/trang-(:num)'] = 'site-videos/news_list/topic/$1/$2';
$route['chu-de/(:any)']              = 'site-videos/news_list/topic/$1';
// Tags
$route['tags/(:any)/trang-(:num)'] = 'site-videos/news_list/tags/$1/$2';
$route['tags/(:any)']              = 'site-videos/news_list/tags/$1';
// Latest Page
$route['tin-moi/(:any)-trang-(:num)'] = 'site-videos/news_list/latest/new/$1';
$route['tin-moi']                     = 'site-videos/news_list/latest/new';
$route['tin-moi/(:any)']              = 'site-videos/news_list/latest/new/$1';
$route['tin-hot/trang-(:num)']        = 'site-videos/news_list/latest/hot/$1';
$route['tin-hot']                     = 'site-videos/news_list/latest/hot';
// Posts
$route['(:any)/(:any)-post(:any)'] = 'site-videos/posts/index/$1/$2/$3';
$route['post/(:any)']              = 'site-videos/posts/redirect/$1';
// Router Category
$route['(:any)/trang-(:num)'] = 'site-videos/news_category/index/$1/$2';
$route['(:any)']              = 'site-videos/news_category/index/$1';


