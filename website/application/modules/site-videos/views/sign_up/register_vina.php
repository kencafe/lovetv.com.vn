<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 9/6/18
 * Time: 13:40
 */
//var_dump($this->web_sign_up);
?>
<div class="tab-pane fade in active register_vina" id="tab1danger">
    <div class="row">
        <?php if (($this->web_sign_up['Vinaphone'] !== NULL) && (count($this->web_sign_up['Vinaphone']['list_service']) > 0)): ?>
            <?php foreach ($this->web_sign_up['Vinaphone']['list_service'] as $key_service => $service): ?>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-newspaper-o" aria-hidden="true"></i><span>
                        <h2><?= $service['name']; ?></h2>
                    </span>
                        </div><!--/ End .panel-heading -->
                        <div class="panel-body">
                            <div style="text-align: center;">
                                <ul class="list-link-register">
                                    <?php foreach ($service['list_package'] as $key_package => $package): ?>
                                        <li>
                                            <a href="<?php echo site_url($this->web_sign_up['Vinaphone']['link_sign_up'] . strtoupper($key_package)); ?>" class="btn btn-success" title="Đăng ký <?= $package['name']; ?> dịch vụ <?= $service['name']; ?>"><?= $package['name']; ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul><!--/ End .list-link-register -->
                            </div>
                            <p class="description-panel"><?= $service['description']; ?></p><!--/ End .description-panel -->
                            <ol>
                                <?php foreach ($service['list_package'] as $key_package => $package): ?>
                                    <li><?= $package['name']; ?>: soạn <strong><?= $package['mo']; ?></strong> gửi
                                        <strong><?= $this->web_sign_up['Vinaphone']['shortcode']; ?></strong>. Giá cước:
                                        <strong><?php echo number_format($package['price']); ?>vnđ</strong>/<?= $package['time']; ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </div><!--/ End .panel-body -->
                    </div><!--/ End .panel panel-default -->
                </div><!--/ End .col-sm-6 col-md-6 col-lg-6 -->
            <?php
            endforeach;
        endif; ?>
    </div><!--/ End .row -->
</div><!--/ End .tab-pane fade in active register_vina -->