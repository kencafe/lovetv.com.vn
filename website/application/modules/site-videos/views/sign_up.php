<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pb-0">
    <!-- Register -->
    <div class="top-category section-padding mb-4">
        <section id="container-wrapper" class="package-block">
            <div class="wrapper-col">
                <!-- Content -->
                <div class="content panel with-nav-tabs panel-danger">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <?php if ($telco_status['Vinaphone']) { ?>
                                <li class="active register_vina">
                                    <a href="#tab1danger" data-toggle="tab"><img src="<?php echo assets_url('images/logo_vn.png'); ?>"><span class="link_connect">Vinaphone</span></a>
                                </li>
                            <?php } ?>
                            <?php if ($telco_status['Viettel Mobile']) { ?>
                                <li class="register_viettel">
                                    <a href="#tab2danger" data-toggle="tab"><img src="<?php echo assets_url('images/logo_vt.png'); ?>"><span class="link_connect">Viettel</span></a>
                                </li>
                            <?php } ?>
                            <?php if ($telco_status['Vietnamobile']) { ?>
                                <li class="register_vnmb">
                                    <a href="#tab3danger" data-toggle="tab"><img src="<?php echo assets_url('images/logo_vnmb.png'); ?>"><span class="link_connect">Vietnammobile</span></a>
                                </li>
                            <?php } ?>
                            <?php if ($telco_status['MobiFone']) { ?>
                                <li class="register_mobi">
                                    <a href="#tab4danger" data-toggle="tab"><img src="<?php echo assets_url('images/logo_mb.png'); ?>"><span class="link_connect">Mobiphone</span></a>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="bg-primary">
                            <div class="page-header padding-top-10 padding-left-20">
                                <span>Hãy đăng ký tham gia cộng đồng <strong><?php echo config_item('cms_site_name'); ?></strong> để cùng nhau tận hưởng cuộc sống nhé bạn!</span>
                            </div>
                        </div><!--/ End .bg-primary -->
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <?php
                            if ($telco_status['Vinaphone']) {
                                $this->load->view('sign_up/register_vina');
                            }
                            if ($telco_status['Viettel Mobile']) {
                                $this->load->view('sign_up/register_viettel');
                            }
                            if ($telco_status['Vietnamobile']) {
                                $this->load->view('sign_up/register_vnmb');
                            }
                            if ($telco_status['MobiFone']) {
                                $this->load->view('sign_up/register_mobi');
                            }
                            ?>
                        </div><!--/ End .tab-content -->
                    </div><!--/ End .panel-body -->
                </div>
            </div>
        </section>
    </div>
</div>