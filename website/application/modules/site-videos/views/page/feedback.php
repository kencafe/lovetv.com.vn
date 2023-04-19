<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/16/18
 * Time: 14:47
 */
?>
<div class="page">
	<div class="page_header clearfix page_margin_top">
		<div class="page_header_left">
			<h1 class="page_title"><?php echo $page_info->name; ?></h1>
		</div>
		<div class="page_header_right">
			<ul class="bread_crumb" vocab="http://schema.org/" typeof="BreadcrumbList">
				<li property="itemListElement" typeof="ListItem">
					<a property="item" typeof="WebPage" title="<?=$brand_title;?>" href="<?=$brand_url;?>">
						<span property="name"><?=$brand_name;?></span>
					</a>
					<meta property="position" content="1">
				</li>
				<li class="separator icon_small_arrow right_gray">&nbsp;</li>

				<li property="itemListElement" typeof="ListItem">
					<span property="name">Pages</span>
					<meta property="position" content="2">
				</li>
				<li class="separator icon_small_arrow right_gray">&nbsp;</li>

				<li property="itemListElement" typeof="ListItem">
					<a property="item" typeof="WebPage" title="<?php echo $page_info->title; ?>" href="<?php echo $page_info->url; ?>">
						<span property="name"><?php echo $page_info->name; ?></span>
					</a>
					<meta property="position" content="3">
				</li>
			</ul>
		</div>
	</div><!--/ end .page_header -->
	<div class="page_layout clearfix">
		<div class="divider_block clearfix">
			<hr class="divider first">
			<hr class="divider subheader_arrow">
			<hr class="divider last">
		</div><!--/ end .divider_block -->
		<div class="row page_margin_top">
			<div class="column column_2_3">
				<div class="row">
					<div class="contact_map" id="map"></div>
				</div><!--/ end .contact_map -->
				<div class="row page_margin_top_section">
					<div class="column column_1_2 border_top">
						<ul class="page_margin_top">
							<li class="item_content clearfix">
							<span class="features_icon envelope animated_element animation-scale"></span>
							<div class="text">
								<h3>Cơ quan chủ quản</h3>
								<p>
									<?php echo $this->db_config->get_data('company_name'); ?><br />
									<?php echo $this->db_config->get_data('contact_company_address_1'); ?><br />
									<?php echo $this->db_config->get_data('contact_company_address_2'); ?>
								</p>
							</div>
							</li>
						</ul>
					</div><!--/ end .contact_map -->
					<div class="column column_1_2 border_top">
						<ul class="page_margin_top">
							<li class="item_content clearfix">
							<span class="features_icon mobile animated_element animation-scale"></span>
							<div class="text">
								<h3>Phone và E-mail</h3>
								<p>
									Phone: <?php echo $this->db_config->get_data('site_phone'); ?><br />
									Fax: <?php echo $this->db_config->get_data('site_fax'); ?><br />
									E-mail: <?php echo mailto($this->db_config->get_data('site_email'), $this->db_config->get_data('site_email')); ?>
								</p>
							</div>
							</li>
						</ul>
					</div>
				</div><!--/ end infomation contact -->

				<div class="row page_margin_top_section">
					<h4 class="box_header">Liên hệ với chúng tôi</h4>
					<p><?php
                        if (isset($error_msg))
                        {
                            echo $error_msg;
                        }
                        else
                        {
                            echo "Xin vui lòng nhập đầy đủ thông tin theo mẫu dưới đây!";
                        }
                        ?>
                    </p>
					<?php echo validation_errors(); ?>
                    <form action="<?php echo site_url($page_info->uri); ?>" class="safe_contact_form margin_top_15" id="safe_contact_form" method="post" accept-charset="utf-8">
						<fieldset class="column column_1_3">
							<div class="block"><input label="Họ và tên" class="text_input" name="name" type="text" placeholder="Họ &amp; tên *"></div>
						</fieldset>
						<fieldset class="column column_1_3">
							<div class="block"><input label="Địa chỉ Email" class="text_input" name="email" type="text" placeholder="Địa chỉ Email *"></div>
						</fieldset>
						<fieldset class="column column_1_3">
							<div class="block"><input label="Tiêu đề" class="text_input" name="subject" type="text" placeholder="Tiêu đề *"></div>
						</fieldset>
						<fieldset>
							<div class="block"><textarea label="Nội dung liên hệ" name="msg" placeholder="Nội dung liên hệ *"></textarea></div>
						</fieldset>
						<fieldset style="float:right;">
							<?php echo $this->ReCaptcha['div']; ?>
							<?php echo $this->ReCaptcha['script']; ?>
						</fieldset>
						<fieldset>
                            <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
							<input type="hidden" name="reference" value="<?php echo trim(current_url()); ?>">
							<input type="submit" name="submit" value="Phản hồi" class="more active">
						</fieldset>
                    </form>
				</div><!--/ End contact form -->
			</div><!--/ .End Home content -->
			<div class="column column_1_3">
                <?php echo placeholder_img('330x150'); ?>
                <?php echo modules::run('site-blocks/site_boxed/post_boxed_right_small_most_tabs', 'no_margin_top_section'); ?>
			</div><!--/ .End Right content -->
		</div>
	</div>
</div>