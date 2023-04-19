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
        </div>
        <div class="row">
            <div class="column column_1_3">
                <ul class="page_margin_top">
                    <li class="item_content clearfix">
					<span title="Engaging Oppurtunities" class="features_icon speaker animated_element animation-scale">
					</span>
                        <div class="text">
                            <h3>Engaging Oppurtunities</h3>
                            <p>
                                Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios nulla.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="column column_1_3">
                <h1 class="about_title center_align page_margin_top"><?=$brand_slogan;?></h1>
                <h2 class="about_subtitle center_align"><?=$brand_name;?></h2>
            </div>
            <div class="column column_1_3">
                <ul class="page_margin_top">
                    <li class="item_content clearfix">
					<span title="Rankings and Ratings" class="features_icon faq animated_element animation-scale">
					</span>
                        <div class="text">
                            <h3>Rankings and Ratings</h3>
                            <p>
                                Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios nulla.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row page_margin_top">
            <div class="column column_1_3 border_top">
                <ul class="page_margin_top">
                    <li class="item_content clearfix">
					<span title="Engaging Oppurtunities" class="features_icon printer animated_element animation-scale">
					</span>
                        <div class="text">
                            <h3>Engaging Oppurtunities</h3>
                            <p>
                                Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios nulla.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="column column_1_3 border_top">
                <ul class="page_margin_top">
                    <li class="item_content clearfix">
					<span title="Source of Daily News" class="features_icon calendar animated_element animation-scale">
					</span>
                        <div class="text">
                            <h3>Source of Daily News</h3>
                            <p>
                                Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios nulla.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="column column_1_3 border_top">
                <ul class="page_margin_top">
                    <li class="item_content clearfix">
					<span title="Rankings and Ratings" class="features_icon graph animated_element animation-scale">
					</span>
                        <div class="text">
                            <h3>Rankings and Ratings</h3>
                            <p>
                                Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios nulla.
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <?php echo modules::run('site-blocks/page_boxed/horizontal_top_news'); ?>
        <div class="row page_margin_top_section">
            <div class="column column_1_1">
                <div class="announcement clearfix">
                    <ul class="columns no_width">
                        <li class="column_left column">
                            <div class="vertical_align">
                                <div class="vertical_align_cell">
                                    <h2>Chúng tôi đang tìm kiếm chiến hữu!</h2>
                                    <h2 class="expose">Gia nhập đội ngũ ngay hôm nay!</h2>
                                </div>
                            </div>
                        </li>
                        <li class="column_right column">
                            <div class="vertical_align">
                                <div class="vertical_align_cell">
                                    <a class="more active big" href="<?php echo site_url('pages/tuyen-dung'); ?>" title="JOIN US!">GIA NHẬP!</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- Join Us -->
        <div class="row page_margin_top_section">
            <div class="column column_1_2">
                <ul class="accordion medium clearfix">
                    <li>
                        <div id="accordion-cras-rutrum">
                            <h4>Cras rutrum leo at odio volutpat id blandit fugiats?</h4>
                        </div>
                        <ul>
                            <li class="item_content clearfix">
						<span title="Engaging Oppurtunities and Top Benefits" class="features_icon pin animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Engaging Oppurtunities and Top Benefits</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                            <li class="item_content clearfix">
						<span title="Press Office, Licensees and Employment" class="features_icon quote animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Press Office, Licensees and Employment</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div id="accordion-donec-fermentum">
                            <h4>Donec fermentum porttitor nunc amet gravida?</h4>
                        </div>
                        <ul>
                            <li class="item_content clearfix">
						<span title="Engaging Oppurtunities and Top Benefits" class="features_icon app animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Engaging Oppurtunities and Top Benefits</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                            <li class="item_content clearfix">
						<span title="Press Office, Licensees and Employment" class="features_icon clock animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Press Office, Licensees and Employment</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div id="accordion-aenean-faucibus">
                            <h4>Aenean faucibus sapien a odio varius?</h4>
                        </div>
                        <ul>
                            <li class="item_content clearfix">
						<span title="Engaging Oppurtunities and Top Benefits" class="features_icon image animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Engaging Oppurtunities and Top Benefits</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                            <li class="item_content clearfix">
						<span title="Press Office, Licensees and Employment" class="features_icon video animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Press Office, Licensees and Employment</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <div id="accordion-donec-pilvinar">
                            <h4>Donec pulvinar lectus quis laoreet vestibulum?</h4>
                        </div>
                        <ul>
                            <li class="item_content clearfix">
						<span title="Engaging Oppurtunities and Top Benefits" class="features_icon envelope animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Engaging Oppurtunities and Top Benefits</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                            <li class="item_content clearfix">
						<span title="Press Office, Licensees and Employment" class="features_icon mobile animated_element animation-scale">
						</span>
                                <div class="text">
                                    <h5>Press Office, Licensees and Employment</h5>
                                    <p>
                                        Maecenas mauris elementum, est morbi interdum cursus at elite imperdiet libero. Proin odios dapibus integer an nulla augue pharetra cursus.
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- Slogan -->
            <div class="column column_1_2">
                <?php echo modules::run('site-blocks/page_boxed/press_office_news'); ?>
            </div><!-- Press Office News -->
        </div>
    </div>
</div>