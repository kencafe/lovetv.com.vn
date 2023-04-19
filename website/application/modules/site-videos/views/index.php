<?php defined('BASEPATH') OR exit('No direct script access allowed');
// Base Class
$this->load->library('msisdn');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <base href="<?= base_url(); ?>">
    <?= $data['meta_equiv']; ?>
    <title><?= trim($data['site_title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <?= $data['meta_content']; ?>
    <?= $data['meta_property']; ?>
    <link rel="alternate" href="<?php echo $data['canonical_url']; ?>" hreflang="vi-VN" />
    <link rel="author" href="<?= $data['site_author']; ?>" />
    <link rel="bookmark" href="<?= current_url(); ?>" />
    <link rel="canonical" href="<?= $data['canonical_url']; ?>" />
    <link rel="image_src" href="<?= $data['site_images']; ?>" />
    <link rel="alternate" href="<?= $data['feeds_url']; ?>" title="<?php echo $data['feeds_title']; ?>" type="application/rss+xml" />
    <link rel="search" href="<?= base_url('xml/opensearch'); ?>" type="application/opensearchdescription+xml" title="<?php echo $data['site_name'] . ' - ' . $data['site_slogan'] ?>" />
    <?php $this->load->view('libraries/stylesheet'); ?>
    <!-- Google SEO -->
    <?php
    if (isset($data['google_search_meta'])) {
        echo "<script type=\"application/ld+json\">\n";
        echo json_encode($data['google_search_meta'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        echo "</script>\n";
    }
    if (isset($data['google_search_BreadcrumbList'])) {
        echo "<script type=\"application/ld+json\">\n";
        echo trim($data['google_search_BreadcrumbList']) . "\n";
        echo "</script>\n";
    }
    ?>
</head>
<body>
<?php
// Nhận diện thuê bao
echo modules::run('vasgate/welcome/msisdn_detect');
// Check thông tin thuê bao
echo modules::run('vasgate/welcome/msisdn_check_info');
?>
<div class="home">
    <?= modules::run('site-videos-blocks/header/master_header'); ?>
    <div id="wrapper">
        <?= modules::run('site-videos-blocks/menu/master_menu'); ?>
        <div id="content-wrapper">
            <?php
            if (isset($sub)) {
                if (isset($data)) {
                    $this->load->view($sub, $data);
                } else {
                    $this->load->view($sub);
                }
            }
            ?>
            <?= modules::run('site-videos-blocks/footer/index'); ?>
        </div>
    </div><!-- /#wrapper -->
    <a class="scroll-to-top rounded" href="#"></a>
    <?= modules::run('site-videos/account/header_notification'); ?>
</div>
<?php $this->load->view('libraries/script'); ?>
</body>
</html>
<!--
Page generation time: {elapsed_time} - Memory usage: {memory_usage}
Current IP Connect: <?= getIPAddress()."\n"; ?>
(c) Powered by Hung Nguyen - dev@nguyenanhung.com - https://nguyenanhung.com/
-->