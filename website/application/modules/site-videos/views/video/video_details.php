<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <div class="container-fluid pb-0">
        <div class="video-block-top-list section-padding">
            <div class="row">
                <!-- main view video -->
                <div class="col-md-8">
                    <div class="single-video">
                        <?php
                        $this->load->library('auth');
                        $checkPostRoles = $this->auth->checkPostRoles($category->slugs);
                        if ($checkPostRoles === TRUE) { ?>
                            <!-- clappr view -->
                            <div id="player-wrapper"></div>
                        <?php } else { ?>
                            <?php echo modules::run('site-videos-blocks/post_boxed/no_player'); ?>
                        <?php } ?>
                        <!-- info video -->
                        <div class="single-video-title video-card-body box mb-3">
                            <h1 class="video-title">
                                <?= isset($content) ? $content->name : NULL; ?>
                            </h1>
                            <div class="video-view">
                                <span class="date-time"><i class="fa fa-clock-o"></i><?= date('d-m-Y', strtotime($content->updated_at)); ?></span>
                                <span class="count-view"><i class="fa fa-eye"></i>214</span>
                                <span class="count-like"><i class="fa fa-thumbs-o-up"></i>30</span>
                            </div>
                        </div>
                        <!-- info channel -->
                        <div class="single-video-author box mb-3">
                            <img class="img-fluid" src="<?= resize_image(images_url($content->photo), 100, 100); ?>" alt="<?= $content->slug ?>">
                            <div class="info-channel">
                                <h4>
                                    <a href="<?= site_url($category->slugs); ?>"><strong><?= $category->name; ?></strong></a>
                                    <span title="" data-placement="top" data-toggle="tooltip" data-original-title="Verified"><i class="fa fa-check-circle text-success"></i></span>
                                </h4>
                                <p class="time-publish">
                                    <small>Published on <?= $category->created_at; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- list recommended video -->
                <div class="col-md-4">
                    <?php echo modules::run('site-videos-blocks/post_boxed/right_latest_recommended_video_by_category', array(
                        'categoryId' => $category->id,
                        'postId'     => $content->id,
                        'size'       => 6,
                        'page'       => 1,
                        'template'   => 'post_boxed_video_right_list'
                    )); ?>
                    <section class="advertisement_banner">
                        <div class="banner-img">
                            <img alt="banner quang cao" src="<?= assets_themes('VideoTV'); ?>images/upload/banner_402x100.png">
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!-- related video -->
        <?php echo modules::run('site-videos-blocks/post_boxed/right_random_recommended_video_by_category', array(
            'categoryId' => $category->id,
            'postId'     => $content->id,
            'size'       => 9,
            'page'       => 1,
            'template'   => 'post_boxed_video_related_by_category',
            'title'      => 'Nội dung tương tự',
            'url'        => $category->slugs
        )); ?>
    </div>
<?php
$this->load->library('auth');
$checkPostRoles = $this->auth->checkPostRoles($category->slugs);
if ($checkPostRoles === TRUE) { ?>
    <script>
        $(document).ready(function () {
            var elementFuck = document.getElementById('player-wrapper');
            var width_video = elementFuck.getBoundingClientRect().width;
            var player = new Clappr.Player({
                source: '<?php echo isset($video_url) ? trim($video_url) : config_item('default_video_url');?>',
                parentId: "#player-wrapper",
                poster: "<?= resize_image(images_url($content->photo), 640, 350); ?>",
                // watermark: "<?= assets_themes('VideoTV'); ?>images/ThuDoMultimedia_logo.png",
                autoPlay: false,
                height: 350,
                width: 640
            });
        });
    </script>
<?php } ?>