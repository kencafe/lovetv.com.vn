<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pb-0">
    <div class="top-category section-padding mb-4">
        <div class="login modal-content">
            <div class="modal-header login-head">
                <h3 class="tilte-login">Đăng nhập</h3>
            </div>
            <div class="modal-body login-body">
                <?= form_open_multipart($site_link, array('id' => 'login-form')); ?>
                <p><?php
                    if (isset($mess_error) && $mess_error != NULL) {
                        echo $mess_error;
                    } ?></p>
                <div class="form-group">
                    <label class="control-label">Số điện thoại</label>
                    <div class="input">
                        <input autocomplete="off" class="form-control" type="text" id="username_popup_login" name="input_phone_number" placeholder="Nhập số điện thoại...VD:012345678XX" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Mật Khẩu</label>
                    <div class="input">
                        <input autocomplete="off" class="form-control" type="password" id="password_popup_login" name="input_password" placeholder="Nhập mật khẩu..." required="">
                    </div>
                </div>
                <div class="p-container">
                    <label class="checkbox"><input type="checkbox" name="checkbox" checked=""><i></i>Ghi nhớ</label>
                    <a href="#">Quên mật khẩu ?</a>
                    <div class="clear"></div>
                </div>
                <div class="submit">
                    <button class="btn btn-light button-login" type="submit" value="Login">Đăng nhập</button>
                    <a class="signup play-icon popup-with-zoom-anim" href="<?= site_url('users/sign-up'); ?>">Đăng ký</a>
                </div>
                <?= form_close(); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>