<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/10/18
 * Time: 15:14
 */
?>
<div class="single-post page-single template-page page-categories page-details">
    <div id="fb-root"></div>
    <div class="site-content-contain site-cover">
        <div id="content" class="site-content mainshad">
            <div class="container ftc-breadcrumbs">
                <div class="ftc-breadcrumb-title">
                    <div class="ftc-breadcrumbs-content">
                        <a href="<?php echo site_url(); ?>">Trang chủ</a> <span class="brn_arrow">/</span>
                        <a href="<?php echo site_url($category->slugs) ?>"><?php echo $category->name; ?></a>
                        <span class="brn_arrow">/</span>
                        <span style="color: #b73237;"><?php echo $content->name; ?></span>
                    </div><!--/ End .ftc-breadcrumbs-content -->
                </div><!--/ End .ftc-breadcrumb-title -->
            </div><!--/ End .container ftc-breadcrumbs -->
            <div class="container main">
                <div class="row">
                    <div class="col-md-8">
                        <div class="wrapper-col">
                            <article>
                                <header class="entry-title">
                                    <h1 class="heading-title">
                                        <?php echo $content->name; ?>
                                    </h1><!--/ End .heading-title -->
                                    <div class="link-info">
                                        <span>
                                            <h2 class="title-cat"><a href="<?php echo site_url($category->slugs) ?>"><?php echo $category->name; ?> </a></h2>
                                        </span>
                                        <span>
                                            <div class="date-time">
                                                <i class="fa fa-calendar"></i>
                                                <?php echo date('d/m/Y', strtotime($content->release_time)); ?>
                                            </div>
                                        </span>
                                    </div><!--/ End .link-info -->
                                    <a href="<?php echo images_url($content->photo); ?>" class="post_image page_margin_top prettyPhoto" title="<?php echo html_escape($content->name); ?>">
                                        <img src='<?php echo images_url(get_json_item($content->photo_data, '330x242')); ?>' alt='<?php echo html_escape($content->slug); ?>' />
                                    </a>
                                </header><!--/ End header .entry-title -->
                                <div class="post-info">
                                    <div class="content-summary">
                                        <?php echo $content->summary; ?>
                                    </div>
                                    <div class="full-content">
                                        <?php
                                        $this->load->library('auth');
                                        $checkPostRoles = $this->auth->checkPostRoles($category->slugs);
                                        if ($checkPostRoles === TRUE) {
                                            echo str_replace('http://tvnews.com.vn/data/', config_item('static_url') . 'data/', $content->content);
                                        } else {
                                            $this->load->view('please_sign_up');
                                        }
                                        ?>
                                    </div>
                                </div><!--/ End .post-info -->
                                <div class="info-plus">
                                    <div class="tags-link">
                                        <span>Tags: </span>
                                        <span class="tag-links">
                                            <?php
                                            $tag_list = explode(',', $content->tags);
                                            if (count($tag_list) > 0) {
                                                foreach ($tag_list as $tag) {
                                                    echo '<a itemprop="keywords" href="' . site_url('tags/' . $this->seo->slugify(trim($tag))) . '" title="' . trim($tag) . '">' . trim($tag) . '</a></li>';
                                                }
                                            }
                                            ?>
                                        </span>
                                    </div><!--/ End .tags-link -->
                                </div><!--/ End .info-plus -->
                            </article><!--/ End article -->
                        </div><!--/ End .wrapper-col -->
                              <!-- Comment nhúng facebook -->
                        <div class="fb-comments" data-href="<?php echo trim($share_url); ?>" data-width="100%" data-numposts="5"></div>
                              <!-- Tin liên quan -->
                        <?php echo modules::run('site-blocks/post_boxed/similar_content', [
                            'boxed_header' => [
                                'color' => 'red',
                                'url'   => site_url($category->slugs),
                                'title' => 'Tin cùng chuyên mục ' . trim($category->name),
                                'name'  => 'Có thể bạn quan tâm'
                            ],
                            'list_item'    => $same_category_item
                        ]); ?>
                    </div><!--/ End .col-md-8 -->
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
                        <!--                        --><?php //echo modules::run('site-blocks/site_boxed/post_boxed_right_photo', array(
                        //                            'boxed_header' => array(
                        //                                'name' => 'Tin nổi bật',
                        //                                'title' => 'Tin HOT nhất, nóng nhất',
                        //                                'url' => site_url('tin-hot'),
                        //                                'color' => 'red'
                        //                            ),
                        //                            'boxed_data' => array(
                        //                                'size' => 5,
                        //                                'page' => 1,
                        //                                'is_hot' => true,
                        //                                'show_top' => true,
                        //                                'order_by_viewed' => array(
                        //                                    'view_total' => array(
                        //                                        'field_name' => 'view_total',
                        //                                        'order_value' => 'DESC'
                        //                                    )
                        //                                )
                        //                            )
                        //                        )); ?>
                    </div><!--/ End .col-md-4 -->
                </div><!--/ End .row -->
            </div><!--/ End .container main -->
        </div><!--/ End .container main -->
    </div><!--/ End .site-content-contain site-cover -->
</div><!--/ End .single-post page-single template-page page-categories page-details -->
<style>
    .tags-link span.tag-links a {
        font-weight: 400;
        color: #888;
        font-style: italic;
        font-size: 13px;
        padding-left: 5px
    }

    ul.post_list_widget {
        padding: 0;
        height: 100%
    }

    .link-info span {
        display: inline-block;
        float: left
    }

    .link-info h2.title-cat {
        margin: 0;
        font-size: 14px;
        font-weight: 600
    }

    .link-info span:first-child {
        padding-right: 20px;
        position: relative
    }

    .link-info span:first-child:before {
        content: "";
        background-color: #ddd;
        height: 12px;
        width: 1px;
        position: absolute;
        right: 10px;
        top: 5px
    }
</style>