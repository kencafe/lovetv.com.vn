<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Project project-vina-giai-tri-tong-hop-website.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/28/18
 * Time: 16:21
 */
?>
<!-- notification login -->
<div class="notification-block">
    <div class="notice-wrapper">
        <p class="notice-login">Bạn chưa đăng nhập hoặc đăng ký gói dịch vụ của <?= config_item('cms_site_name'); ?>.</p>
        <p class="link-login">Hãy
            <span><a href="<?= site_url('users/login'); ?>" title="đăng nhập">đăng nhập</a> </span>
            <span> hoặc </span>
            <span><a href="<?= site_url('users/sign-up'); ?>" title="đăng ký">đăng ký</a> để có thể xem toàn bộ nội dung bài viết nhé !</span>
        </p>
    </div>
</div>
<!-- end notification login -->
<style>
    /*style for notification login*/
    .notification-block {
        -moz-box-shadow: 1px 2px 4px rgba(0, 0, 0, 0.5);
        -webkit-box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
        box-shadow: 1px 2px 4px rgba(0, 0, 0, .5);
        padding: 10px;
        background: #fafafa;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .notice-wrapper {
        border: 2px solid #e8dfdf;
        padding: 12px;
        text-align: center;
    }

    .notification-block p.notice-login {
        font-size: 17px;
        color: red;
        font-weight: bold;
        margin: 0;
        margin-bottom: 10px;
    }

    .link-login span a {
        color: #3faf47;
        text-decoration: none;
    }

    .notification-block p {
        margin-bottom: 0;
        line-height: 22px;
        margin-top: 5px;
    }

    .link-login span a:hover {
        color: #ad1818;
    }
</style>
