<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:39
 */
?>
<div class="col-lg-12 col-md-12 widget-info">
    <div class="footer-logo mb-4">
        <img alt="<?= base_url(); ?>" src="<?= base_url('assets/logo.png'); ?>" class="img-fluid">
    </div>
    <h2>Công ty chủ quản: <?= $data['site_company']; ?></h2>
    <p class="mb-0 info-company text-dark">
        <i class="fa fa-map-marker"></i> Địa chỉ: <?= $data['contact_company_address']; ?>
    </p>
    <p class="mb-0 info-company">
        <a href="#" class="text-dark"><i class="fa fa-id-card-o"></i> Đăng ký kinh doanh: <?= $data['dang_ky_kinh_doanh']; ?></a>
    </p>
    <p class="mb-0 info-company">
        <a href="#" class="text-dark"><i class="fa fa-phone"></i> SDT: <?= $data['company_phone_number']; ?> - CSKH: 1900585868</a>
    </p>
    <p class="mb-0 info-company">
        <a href="#" class="text-dark"><i class="fa fa-phone"></i> Hotline: 1900585868 (2000vnd/phút)</a>
    </p>
    <p class="mb-0 info-company">
        <a href="#" class="text-dark"><i class="fa fa-envelope"></i> Email: <?= $data['company_email']; ?></a>
    </p>
    <p class="mb-0 info-company">
        <a href="#" class="text-dark"><i class="fa fa-user"></i> Chịu trách nhiệm nội dung: <?= $data['chiu_trach_nhiem_noi_dung']; ?></a>
    </p>
    <p class="mb-0 info-company">
        <i class="fa fa-drivers-license"></i>
        Giấy chứng nhận đăng kí cung cấp dịch vụ nội dung thông tin trên mạng viễn thông di động số <b>351/GCN-DĐ</b> do Cục phát thanh, truyền hình và thông tin điện tử Hà Nội cấp ngày 28 tháng 11
        năm 2017.
    </p>
    <p class="mb-0 info-company">
        <i class="fa fa-drivers-license"></i>
        <a href='http://online.gov.vn/Home/WebDetails/60001'><img width="150px" alt='Da dang ky voi bo cong thuong' title='' src='http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png' /></a>
    </p>
</div>