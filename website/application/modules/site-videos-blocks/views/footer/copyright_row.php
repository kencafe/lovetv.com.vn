<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/4/18
 * Time: 14:37
 */
?>

<div id="bottom" class="clearfix style-1">
    <div id="bottom-bar-inner" class="wprt-container">
        <div class="bottom-bar-inner-wrap">
            <div class="bottom-bar-content">
                <div id="copyright" class="text-center">
                    <p><span> &copy; <?php if (date('Y') == 2018) {echo '2018';} else {echo '2018 - ' . date('Y'); } ?> by </span><span><strong><a rel="nofollow" href="http://thudomultimedia.vn" title="ThuDoMultimedia" target="_blank" style="text-transform: uppercase"><?php echo html_escape($data['company_name']); ?></a></strong></span>
                    </p>
                </div>
                <!-- /#copyright -->
            </div>
        </div>
    </div>
</div>