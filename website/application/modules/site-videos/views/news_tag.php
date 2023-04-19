<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="wrapper-body">
    <div class="container-fluid container-banner">
        <!-- banner single channel -->
        <div class="single-channel-image">
            <img class="img-fluid" alt="<?= config_item('cms_site_name'); ?> banner" src="<?= assets_themes('VideoTV'); ?>images/upload/channel-banner.png">
            <div class="channel-profile">
                <img class="channel-profile-img" alt="<?= config_item('cms_site_name'); ?> profile image" src="<?= assets_url('site-logo.jpg'); ?>">
                <div class="social hidden-xs">Follow
                    <a class="fb" href="#"><i class="fa fa-facebook"></i>Facebook</a>
                    <a class="tw" href="#"><i class="fa fa-twitter"></i>Twitter</a>
                    <a class="gp" href="#"><i class="fa fa-google-plus"></i>Google+</a>
                </div>
            </div>
        </div>
        <!-- nav single channel -->
        <div class="single-channel-nav">
            <nav class="navbar navbar-expand-lg navbar-light">
                <h1 class="channel-brand" href="<?= site_url($page_info->slugs); ?>"><?= $page_info->name; ?>
                    <span title="" data-placement="top" data-toggle="tooltip" data-original-title="Verified"><i class="fa fa-check-circle text-success"></i></span>
                </h1>
                <div class="panel-heading collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav mr-auto nav-channel">
                        <li class="nav-tabs active">
                            <a href="#tab1danger" data-toggle="tab">Video</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="widget_space tabs-pannel-channel panel with-nav-tabs panel-danger">
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1danger">
                        <div class="video-block section-padding">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-title ">
                                        <h4 class="heading-title">Trang <?= $current_page; ?></h4>
                                    </div>
                                </div>
                                <?= modules::run('site-videos-blocks/site_list/section_list_video', $list_item); ?>
                                <?= modules::run('site-videos-blocks/site_list/generate_site_list_pagination', $pagination); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>