<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="wrapper-body" class="container-fluid pb-0">
    <?php
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Chuyện Yêu',
            'category_slug' => 'lovetv-chuyen-yeu',
            'url'           => site_url('lovetv-chuyen-yeu'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-heart',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-chuyen-yeu' => array(
                'name'      => 'Chuyện Yêu',
                'slug'      => 'lovetv-chuyen-yeu',
                'url'       => site_url('lovetv-chuyen-yeu'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Gia Đình',
            'category_slug' => 'lovetv-gia-dinh',
            'url'           => site_url('lovetv-gia-dinh'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-home',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-gia-dinh' => array(
                'name'      => 'Gia Đình',
                'slug'      => 'lovetv-gia-dinh',
                'url'       => site_url('lovetv-gia-dinh'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Thiếu Nhi',
            'category_slug' => 'lovetv-thieu-nhi',
            'url'           => site_url('lovetv-thieu-nhi'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-users',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-chuyen-yeu' => array(
                'name'      => 'Thiếu Nhi',
                'slug'      => 'lovetv-thieu-nhi',
                'url'       => site_url('lovetv-thieu-nhi'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Giải Trí',
            'category_slug' => 'lovetv-giai-tri',
            'url'           => site_url('lovetv-giai-tri'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-diamond',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-giai-tri' => array(
                'name'      => 'Giải Trí',
                'slug'      => 'lovetv-giai-tri',
                'url'       => site_url('lovetv-giai-tri'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Camera Cận Cảnh',
            'category_slug' => 'lovetv-camera-can-canh',
            'url'           => site_url('lovetv-camera-can-canh'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-video-camera',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-camera-can-canh' => array(
                'name'      => 'Camera Cận Cảnh',
                'slug'      => 'lovetv-camera-can-canh',
                'url'       => site_url('lovetv-camera-can-canh'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Du Lịch - Khám Phá',
            'category_slug' => 'lovetv-du-lich-kham-pha',
            'url'           => site_url('lovetv-du-lich-kham-pha'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-snowflake-o',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-du-lich-kham-pha' => array(
                'name'      => 'Du Lịch - Khám Phá',
                'slug'      => 'lovetv-du-lich-kham-pha',
                'url'       => site_url('lovetv-du-lich-kham-pha'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Nghìn Lẻ Một Chuyện',
            'category_slug' => 'lovetv-nghin-le-mot-chuyen',
            'url'           => site_url('lovetv-nghin-le-mot-chuyen'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-newspaper-o',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-nghin-le-mot-chuyen' => array(
                'name'      => 'Nghìn Lẻ Một Chuyện',
                'slug'      => 'lovetv-nghin-le-mot-chuyen',
                'url'       => site_url('lovetv-nghin-le-mot-chuyen'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    echo modules::run('site-videos-blocks/site_boxed/post_boxed_main_block_category', array(
        'boxed_header' => array(
            'title'         => 'Làm Đẹp',
            'category_slug' => 'lovetv-lam-dep',
            'url'           => site_url('lovetv-lam-dep'),
            'color'         => 'color_1',
            'icon'          => 'fa fa-eye',
            'template'      => 'post_boxed_video_category',
            'size'          => 5,
            'page'          => 1
        ),
        'boxed_data'   => array(
            'lovetv-lam-dep' => array(
                'name'      => 'Làm Đẹp',
                'slug'      => 'lovetv-lam-dep',
                'url'       => site_url('lovetv-lam-dep'),
                '_blank'    => TRUE,
                'recursive' => TRUE
            ),
        )
    ));
    ?>
</div>