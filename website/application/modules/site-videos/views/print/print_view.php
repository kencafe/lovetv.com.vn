<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/10/18
 * Time: 15:14
 */
?>
<div class="contents hasshadow subpage clearfix">
    <div class="maincontents">
        <div class="article-header">
            <h1><?php echo $data['content']->name; ?></h1>
            <p class="meta">
                <?php echo date('H:i:s A d/m/Y', strtotime($data['content']->release_time)); ?>
            </p>
        </div><!--/ end .article-header -->
        <div class="wrap-article clearfix">
            <div class="article-body cmscontents">
                <p class="summary">
                    (<?php echo $data['site_name']; ?>) - <?php echo $data['content']->summary; ?>
                </p><!--/ end .summary -->
                <table align="center" class="picBox">
                    <tbody>
                    <tr>
                        <td>
                            <img src="<?php echo images_url($data['content']->photo); ?>" alt="<?php echo html_escape($data['content']->slug); ?>" title="<?php echo html_escape($data['content']->name); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">
                            <?php echo $data['content']->name; ?>
                        </td>
                    </tr>
                    </tbody>
                </table><!--/ end .picBox -->
                <div class="main-content">
                    <?php echo str_replace('http://tvnews.com.vn/data/', config_item('static_url').'data/', $content->content); ?>
                </div><!--/ end .main-content -->
                <p class="source">
                    <?php echo $data['site_name']; ?>- <?php echo base_url(); ?>
                </p><!--/ end .source -->
            </div><!--/.article-body-->
        </div><!--/ end .wrap-article clearfix -->
    </div><!--/ end .maincontents -->
</div><!--/ end .contents -->
