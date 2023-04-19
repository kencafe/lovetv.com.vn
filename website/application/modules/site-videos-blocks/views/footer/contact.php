<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Author: thaodt97
 * @Date:   2018-08-03 14:58:13
 * @Last Modified by:   thaodt97
 * @Last Modified time: 2018-08-03 15:07:08
 */
?>
<div class="col-lg-4 col-md-4 col-xs-6">
    <div class="widget widget_information">
        <h2 class="widget-title"><span>Liên hệ</span></h2>
        <ul>
            <li class="address clearfix">
                <span class="hl">Địa chỉ:</span>
                <span class="text"><?php echo html_escape($data['contact_company_address_1']); ?> - <?php echo html_escape($data['contact_company_address_2']); ?></span>
            </li>
            <li class="phone clearfix">
                <span class="hl">Điện thoại:</span>
                <span class="text"><a href="tel:<?php echo html_escape($data['contact_profile']->phone); ?>" style="color: #0AA540"><?php echo html_escape($data['contact_profile']->phone); ?></a></span>
            </li>
            <li class="email clearfix">
                <span class="hl">E-mail:</span>
                <span class="text"><?php echo mailto(html_escape($data['contact_profile']->email), html_escape($data['contact_profile']->email)); ?></span>
            </li>
        </ul>
    </div>
</div>
<style>
	li.email.clearfix a {
    color: white;
}
</style>