<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: hungna
 * Date: 1/23/2017
 * Time: 4:20 PM
 */
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php
        if (isset($title)) {
            echo $title;
        } else {
            echo 'Đang xây dựng!';
        }
        ?>
    </title>
    <style>
        body { text-align: center; padding: 150px; }
        h1 { font-size: 50px; }
        body { font: 20px Helvetica, sans-serif; color: #333; }
        article { display: block; text-align: left; width: 650px; margin: 0 auto; }
        a { color: #dc8100; text-decoration: none; }
        a:hover { color: #333; text-decoration: none; }
    </style>
</head>
<body>
<article>
    <h1><?php
        if (isset($heading)) {
            echo $heading;
        } else {
            echo 'Hệ thống đang được xây dựng!';
        }
        ?></h1>
    <div>
        <p><?php
            if (isset($message)) {
                echo $message;
            } else {
                echo 'Hệ thống đang được xây dựng, vui lòng quay lại sau.';
            }
            ?></p>
        <p>
            &mdash; <?php
            if (isset($signature)) {
                echo $signature;
            } else {
                echo 'Thủ Đô Multimedia';
            }
            ?>
        </p>
    </div>
</article>
</body>
</html>
