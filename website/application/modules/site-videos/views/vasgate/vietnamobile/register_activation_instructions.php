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
                            <p><?php
                                if (isset($notifyMessage) && $notifyMessage != NULL) {
                                    echo $notifyMessage;
                                } ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>