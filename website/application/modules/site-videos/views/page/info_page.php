<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 9/7/18
 * Time: 10:47
 */
?>
<div class="template-page uses-page site-content-contain site-cover">
    <div id="content" class="site-content mainshad">
        <div class="container ftc-breadcrumbs">
            <div class="ftc-breadcrumb-title">
                <div class="ftc-breadcrumbs-content">
                    <a href="<?php echo site_url(); ?>">Trang chủ</a>
                    <span class="brn_arrow">/</span>
                    <span class="current"><?=$content->name;?></span>
                </div><!--/ End .ftc-breadcrumbs-content -->
            </div><!--/ End .ftc-breadcrumb-title -->
        </div><!--/ End .container ftc-breadcrumbs -->
        <div class="container main">
            <div class="wrapper">
                <h2 class="heading-title-page"><?=$content->name;?></h2>
                <p><?=$content->summary;?></p>
                <div class="content-uses">
                    <div class="how-use use1">
                        <?=$content->content;?>
                    </div>
                    <!--
                    <div class="how-use use2"></div>
                    <div class="how-use use3"></div>
                    -->
                </div><!--/ End .content-uses -->
            </div><!--/ End .wrapper -->
        </div><!--/ End .container main -->
    </div><!--/ End .site-content mainshad -->
</div><!--/ End .site-content-contain site-cover -->