<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pb-0">
    <div class="top-category section-padding mb-4">
        <div class="login modal-content">
            <div class="modal-header login-head">
                <h3 class="tilte-login"><?= $header_title; ?></h3>
            </div>
            <div class="modal-body login-body">
                <div class="form-group" style="text-align: left">
                    <div class="col-sm-10">
                        <div class="error-sign-in-message">
                            <p>Cảm ơn quý khách đã sử dụng dịch vụ của Vietnamobile. Quý khách vui lòng xác nhận đồng ý đăng ký và gia hạn
                               dịch vụ với các thông tin sau đây:</p>
                            <p>1. <strong><?= $packageInfo['service_name'] . ' - ' . $packageInfo['name']; ?></strong> – trên trang web:
                                <a href="<?= base_url(); ?>"><?= base_url(); ?></a></p>
                            <p>2. Giá dịch vụ: <strong><?= number_format($packageInfo['price']); ?></strong>
                               VND/<strong><?= $packageInfo['circle']; ?></strong>. Dịch vụ tự động gia hạn.</p>
                        </div>
                    </div>
                </div>
                <?= form_open_multipart($site_link, array('id' => 'login-form')); ?>
                <p><?php
                    if (isset($mess_error) && $mess_error != NULL) {
                        echo $mess_error;
                    } ?></p>
                <div class="form-group">
                    <label class="control-label">Số điện thoại</label>
                    <?php if ((isset($hasDetectMsisdn) && $hasDetectMsisdn === TRUE) && (isset($hasDetectIsMsisdn) && $hasDetectIsMsisdn !== NULL)) {
                        echo $hasDetectIsMsisdn;
                    } else {
                        echo NULL;
                    } ?>
                    <div class="input">
                        <input
                                autocomplete="off"
                                class="form-control"
                                type="<?php if ((isset($hasDetectMsisdn) && $hasDetectMsisdn === TRUE) && (isset($hasDetectIsMsisdn) && $hasDetectIsMsisdn !== NULL)) {
                                    echo 'hidden';
                                } else {
                                    echo 'text';
                                } ?>"
                                id="username_popup_login"
                                name="<?= $input_name_user_msisdn; ?>"
                                value="<?= $current_user_msisdn; ?>"
                                placeholder="Nhập số điện thoại...VD:012345678XX"
                                required="">
                    </div>
                </div>
                <div class="submit">
                    <button class="btn btn-light button-login" type="submit" value="Register">Đăng ký</button>
                    <a class="signup play-icon popup-with-zoom-anim" href="<?= site_url(); ?>">Quay về trang chủ</a>
                </div>
                <?= form_close(); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>