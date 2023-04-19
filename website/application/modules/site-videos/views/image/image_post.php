<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="single-post page-single template-page page-categories page-details type-gallery">
    <div class="site-content-contain site-cover">
        <div id="content" class="site-content mainshad">
            <div class="container ftc-breadcrumbs">
                <div class="ftc-breadcrumb-title">
                    <div class="ftc-breadcrumbs-content">
                        <a href="<?php echo site_url(); ?>">Trang chủ</a> <span class="brn_arrow">/</span>
                        <a href="<?php echo site_url($category->slugs) ?>"><?php echo $category->name ?></a>
                        <span class="brn_arrow">/</span>
                        <span class="current"><?php echo $content->name; ?></span>
                    </div><!--/ End .ftc-breadcrumbs-content -->
                </div><!--/ End .container ftc-breadcrumbs -->
            </div><!--/ End .container ftc-breadcrumbs -->
            <div class="container main">
                <div class="row">
                    <div class="col-md-8 main-content">
                        <div class="wrapper-col">
                            <article>
                                <header class="entry-title">
                                    <h1 class="heading-title"><?php echo $content->name; ?></h1>
                                    <div class="link-info">
                                        <span>
                                            <h2 class="title-cat">
                                                <a href="<?php echo site_url($category->slugs) ?>">
                                                    <?php echo $category->name; ?>
                                                </a>
                                            </h2>
                                        </span>
                                        <span>
                                            <div class="date-time"><i class="fa fa-calendar"></i>
                                                <?php echo date('d/m/Y', strtotime($content->release_time)); ?>
                                            </div>
                                        </span>
                                    </div>
                                </header><!--/ End header .entry-title -->
                                <div class="post-info">
                                    <?php
                                    $this->load->library('auth');
                                    $checkPostRoles = $this->auth->checkPostRoles($category->slugs);
                                    if ($checkPostRoles === TRUE) {
                                        ?>
                                        <div class="content-summary">
                                            <div class="gallery-images-slider owl-carousel owl-loaded owl-drag">
                                                <div class="owl-stage-outer">
                                                    <div class="owl-stage">
                                                        <?php
                                                        if (empty($list_image_url)) {
                                                            $data_image_content = NULL;
                                                        } else {
                                                            $data_image_content = json_decode($list_image_url);
                                                        }
                                                        if (!empty($data_image_content) && is_array($data_image_content) && count($data_image_content) > 0) {
                                                            foreach ($data_image_content as $item) { ?>
                                                                <div class="owl-item">
                                                                    <div class="item">
                                                                        <div class="post-img">
                                                                            <img src="<?= resize_image(images_url($item->path_img), 640, 333); ?>" alt="<?= $this->seo->str_to_en($item->title); ?>">
                                                                        </div>
                                                                        <div class="caption post-info">
                                                                            <div class="full-content">
                                                                                <p>
                                                                                    <?php echo $item->description; ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!--/ End .content-summary -->
                                    <?php } else {
                                        echo $this->load->view('please_sign_up');
                                    }; ?>
                                    <div class="info-plus">
                                        <ul id="vce_social_menu" class="soc-nav-menu">
                                            <li class="item-social">
                                                <a target="_blank" title="Share bài viết <?php echo clean_title($content->name); ?> lên Facebook" href="https://www.facebook.com/dialog/share?app_id=<?php echo html_escape(trim(get_json_item($this->db_config->get_data('facebook_profile'), 'app_id'))); ?>&amp;redirect_uri=<?php echo urlencode(base_url()); ?>&amp;href=<?php echo urlencode($share_url); ?>" class="social_icon facebook"><span class="social-name">Facebook</span><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="item-social">
                                                <a target="_blank" title="Share bài viết <?php echo clean_title($content->name); ?> lên Twitter" href="https://twitter.com/home?status=<?php echo urlencode($share_url); ?>" class="social_icon twitter"><span class="social-name">Twitter</span><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                            </li>
                                            <li class="item-social">
                                                <a target="_blank" title="Share bài viết <?php echo clean_title($content->name); ?> lên Twitter" href="https://twitter.com/home?status=<?php echo urlencode($share_url); ?>" class="social_icon twitter"><span class="social-name">Google Plus</span><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                        <div class="tags-link">
                                            <span>Tags: </span>
                                            <span class="tag-links">
                                                <?php
                                                $tag_list = explode(',', $content->tags);
                                                if (count($tag_list) > 0) {
                                                    foreach ($tag_list as $tag) { ?>
                                                        <a href="<?php echo site_url('tags/' . $this->seo->slugify(trim($tag))) ?>" title="<?php echo trim($tag) ?>" rel="tag">
                                                    <?php echo trim($tag); ?></a>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div><!--/ End .info-plus -->
                                </div><!--/ End .post-info -->
                            </article>
                            <!-- Comment nhúng facebook -->
                            <div class="fb-comments" data-href="<?php echo trim($share_url); ?>" data-width="100%" data-numposts="5"></div>
                            <?php echo modules::run('site-blocks/post_boxed/similar_content', [
                                'boxed_header' => [
                                    'color' => 'red',
                                    'url'   => site_url($category->slugs),
                                    'title' => 'Tin cùng chuyên mục ' . trim($category->name),
                                    'name'  => 'Có thể bạn quan tâm'
                                ],
                                'list_item'    => $same_category_item
                            ]); ?>
                        </div><!--/ End .wrapper-col -->
                    </div><!--/ End .col-md-8 main-content -->
                    <div class="col-md-4">
                        <?php echo modules::run('site-blocks/site_boxed/post_boxed_right_small', [
                            'boxed_header' => [
                                'name'  => 'Xem nhiều',
                                'title' => 'Xem nhiều',
                                'color' => 'red'
                            ],
                            'boxed_data'   => [
                                'size'            => 5,
                                'page'            => 1,
                                'recursive'       => TRUE, // Lấy cả tin trong danh mục con
                                'is_hot'          => FALSE, // chỉ lấy tin HOT = true
                                'show_top'        => FALSE,
                                'order_by_viewed' => FALSE,
                            ]
                        ]); ?>
                        <?php echo modules::run('site-blocks/site_boxed/post_boxed_right_small', [
                            'boxed_header' => [
                                'name'  => 'Tin nổi bật',
                                'title' => 'Tin HOT nhất, nóng nhất',
                                'url'   => site_url('tin-hot'),
                                'color' => 'red'
                            ],
                            'boxed_data'   => [
                                'size'            => 5,
                                'page'            => 1,
                                'is_hot'          => TRUE,
                                'show_top'        => FALSE,
                                'order_by_viewed' => [
                                    'view_total' => [
                                        'field_name'  => 'view_total',
                                        'order_value' => 'DESC'
                                    ]
                                ]
                            ]
                        ]); ?>
<!--                        --><?php //echo modules::run('site-blocks/site_boxed/post_boxed_right_photo', [
//                            'boxed_header' => [
//                                'name'  => 'Tin nổi bật',
//                                'title' => 'Tin HOT nhất, nóng nhất',
//                                'url'   => site_url('tin-hot'),
//                                'color' => 'red'
//                            ],
//                            'boxed_data'   => [
//                                'size'            => 5,
//                                'page'            => 1,
//                                'is_hot'          => TRUE,
//                                'show_top'        => TRUE,
//                                'order_by_viewed' => [
//                                    'view_total' => [
//                                        'field_name'  => 'view_total',
//                                        'order_value' => 'DESC'
//                                    ]
//                                ]
//                            ]
//                        ]); ?>
                    </div><!--/ End .col-md-4 -->
                </div><!--/ End .row -->
            </div><!--/ End .container main -->
        </div><!--/ End .site-content mainshad -->
    </div><!--/ End .site-content-contain site-cover -->
</div><!--/ End .single-post page-single template-page page-categories page-details type-gallery -->