<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 10/1/18
 * Time: 08:48
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="site-content-contain site-cover">
    <div id="content" class="site-content mainshad">
        <div class="container">
            <div style="margin-bottom:7px;"></div>
            <!-- Content -->
            <div class="page-login">
                <div class="modal-content">
                    <div class="modal-header choidau-bg" style=" padding: 6px 10px;">
                        <h5 class="modal-title" id="myModalLabel"
                            style="font-size:1.3em; margin: 5px 0px; text-align:center; font-weight: 600; color: white;">
                            ĐĂNG NHẬP</h5>
                    </div><!--/ .modal-content -->
                    <div class="modal-body">
                        <?php
                        echo form_open_multipart($site_link, array(
                            'id'    => 'login-form',
                            'class' => 'form-horizontal'
                        ));
                        ?>
                        <div class="form-group">
                            <label class="control-label">Số điện thoại</label>
                            <div class="input">
                                <input autocomplete="off" class="form-control" type="text" id="username_popup_login"
                                       name="input_phone_number" placeholder="Nhập số điện thoại...VD:012345678XX"
                                       required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Mật Khẩu</label>
                            <div class="input">
                                <input autocomplete="off" class="form-control" type="password" id="password_popup_login"
                                       name="input_password" placeholder="Nhập mật khẩu..." required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-10">
                                <div class="error-sign-in-message">
                                    <div style="text-align: center; color: red">
                                        <p>
                                            <?php
                                            if (isset($mess_error) && $mess_error != NULL) {
                                                echo $mess_error;
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="info-login">
                                <p>
                                    Bạn chưa có tài khoản, hãy <span><a class="font-weight-600 italic"
                                                                        href="<?php echo $site_link_sign_up; ?>">đăng ký!</a></span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group btn-login">
                            <button type="submit" name="login_btn"
                                    class="btn btn-sm green btn-login-popup margin-bottom-5">ĐĂNG NHẬP<i
                                        class="icon-login-2 white"></i></button>
                            <!-- redirect home page -->
                            <a class="btn btn-sm btn-default margin-bottom-5" href=" <?php echo base_url(); ?> ">QUAY VỀ
                                                                                                                 TRANG
                                                                                                                 CHỦ</a>
                        </div>
                        </form>
                    </div><!--/ .modal-body -->
                </div><!-- /.modal-content -->
            </div><!-- ./ content -->
        </div><!--/ .container -->
    </div><!--/ .site-content mainshad -->
</div><!--/ .site-content-contain site-cover -->
<style>
    .page-login {
        margin-top: 20px;
        margin-bottom: 40px
    }

    .page-login .modal-body {
        padding: 40px 0
    }

    .modal-body .form-group label.control-label, .modal-body .form-group .input {
        display: inline-block;
        text-align: left
    }

    .form-group a.btn.btn-sm.btn-default.margin-bottom-5 {
        text-shadow: none;
        margin-left: 20px;
        border-color: #b73237;
        background: #b73237;
        color: #fff
    }

    a.btn.btn-sm.btn-default.margin-bottom-5:hover, .form-group button.btn-login-popup:hover {
        background: #333;
        color: #fff;
        border-color: #333
    }

    .page-login form#login-form {
        width: 55%;
        margin: 0 auto
    }

    .modal-body .form-group label.control-label {
        width: 20%
    }

    .modal-body .form-group .input .form-control {
        min-width: 320px;
        padding: 8px 15px;
        box-shadow: none;
        height: auto
    }

    .info-login p {
        display: inline-block;
        width: 100%
    }

    .info-login a {
        color: #ad1818
    }

    .page-login .modal-content {
        box-shadow: none !important;
        border: 1px solid #e5e5e5
    }

    .page-login .modal-header.choidau-bg {
        background-color: #b73237 !important;
        color: #fff
    }

    .page-login #footer_login {
        border-top-color: #f7f7f7 !important
    }

    .page-login .close {
        display: none
    }

    .page-login .btn-cancel {
        display: none
    }

    .btn-search-hover:hover {
        transition: background-color .4s;
        background-color: green
    }

    .btn-search-hover:hover i {
        color: #fff
    }

    .modal-body {
        padding-left: 100px
    }

    #frm-forgot-pass {
        padding: 10px;
        border: 1px solid #eee;
        box-shadow: rgba(0, 0, 0, 0.14902) 0 1px 2px 0;
        -webkit-box-shadow: rgba(0, 0, 0, 0.14902) 0 1px 2px 0;
        -moz-box-shadow: rgba(0, 0, 0, 0.14902) 0 1px 2px 0;
        background-color: #f9f9f9
    }

    #frm-forgot-pass legend {
        font-weight: 600;
        color: #aaa;
        padding-bottom: 5px;
        border-bottom-color: #f7f7f7
    }

    #location_create .select2-container--default .select2-selection--single {
        border-color: #d8d8d8 !important
    }

    .green.btn {
        color: #fff;
        background-color: #333
    }

    .input input {
        border-radius: 5px
    }

    .margin-bottom-5 {
        background: #b73237;
        color: #fff
    }

    a {
        color: #0a7a05
    }

    a:hover {
        color: #0a7a05
    }

    i {
        color: #0a7a05
    }

    .pre-header a:hover {
        color: #0a7a05
    }

    .shop-currencies a.current {
        color: #0a7a05
    }

    @media (max-width: 768px) {
        .modal-body .form-group label.control-label {
            width: 25%
        }

        .page-login form#login-form {
            width: 80%
        }
    }

    @media (max-width: 734px) {
        .modal-body .form-group label.control-label {
            width: 30%
        }

        .page-login form#login-form {
            width: 80%
        }
    }

    @media (max-width: 480px) {
        .modal-body .form-group label.control-label {
            width: 100%;
            margin-bottom: 10px
        }

        .modal-body .form-group .input .form-control {
            min-width: 50px
        }

        .page-login form#login-form {
            width: 80%
        }

        .modal-body .form-group label.control-label, .modal-body .form-group .input {
            text-align: left;
            display: inline-block;
            width: 100%
        }

        .modal-body .form-group .input .form-control {
            min-width: 50px;
            width: 100%;
            display: inline-block
        }
    }
</style>
