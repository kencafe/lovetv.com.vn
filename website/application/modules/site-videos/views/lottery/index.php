<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: huypv2
 * Date: 10/25/2018
 * Time: 9:29 AM
 */
?>
<style>
    .card h4{
        background-color: #6b8891;
        padding: 10px 10px;
        color: white;
    }
    .card .p-20{
        border-bottom: 2px solid #ececec;
    }
    .card .center a{
        color: #0AA540;
    }
</style>
<div class="container">
    <?php foreach ($details->MB as $mb){ ?>
        <div class="row" >
            <div class="card">
                <div class="black-text lighten-4" >
                    <h4 class="center white-text uppercase bold" >Kết quả xổ số miền Bắc</h4>
                    <h5 class="center uppercase bold">Ngày <?php echo date_format(date_create($mb->date),'d-m-Y'); ?></h5>
                    <div class="p-20">
                        <?php echo nl2br($mb->kq); ?>
                    </div>
                    <br>
                    <?php if($type == 0){ ?>
                        <h5 class="center uppercase bold" >Bạn vui lòng <a class="red-text" href="<?php echo site_url('users/login'); ?>">đăng nhập</a> / <a class="red-text" href="<?php echo site_url('dich-vu/vnm/dang-ky-su-dung-dich-vu/KQXSMB'); ?>">đăng ký</a>  để xem đầy đủ kết quả mới nhất và cập nhật nhanh nhất.</h5>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="row" >
        <div class="card">
            <div class="black-text lighten-4" >
                <h4 class="center white-text uppercase bold" >Kết quả xổ số miền Trung</h4>
                <?php foreach ($details->MT as $key => $mt){
                    if($key > 0){
                        echo "<div class= 'divider' style='background-color: #252020;'></div>";
                    }
                    ?>
                    <h5 class="center uppercase bold"><?php echo $mt->name; ?> Ngày <?php echo date_format(date_create($mt->date),'d-m-Y'); ?></h5>
                    <div class="p-20">
                        <?php echo nl2br($mt->kq); ?>
                    </div>
                <?php } ?>
                <?php if($type == 0){ ?>
                    <h5 class="center bold" >Bạn vui lòng <a class="uppercase red-text" href="<?php echo site_url('users/login'); ?>">đăng nhập</a> / <a class="uppercase red-text" href="<?php echo site_url('dich-vu/vnm/dang-ky-su-dung-dich-vu/KQXSMT'); ?>">đăng ký</a>  để xem đầy đủ kết quả mới nhất và cập nhật nhanh nhất.</h5>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="card">
            <div class="black-text lighten-4" >
                <h4 class="center white-text uppercase bold" >Kết quả xổ số miền Nam</h4>
                <?php foreach ($details->MN as $key => $mn){
                    if($key > 0){
                        echo "<div class= 'divider' style='background-color: #252020;'></div>";
                    }
                    ?>
                    <h5 class="center bold"><?php echo $mn->name; ?> Ngày <?php echo date_format(date_create($mn->date),'d-m-Y'); ?></h5>
                    <div class="p-20">
                        <?php echo nl2br($mn->kq); ?>
                    </div>
                <?php } ?>
                <?php if($type == 0){ ?>
                    <h5 class="center bold" >Bạn vui lòng <a class="uppercase red-text" href="<?php echo site_url('users/login'); ?>">đăng nhập</a> / <a class="uppercase red-text" href="<?php echo site_url('users/sign-up'); ?>">đăng ký</a>  để xem đầy đủ kết quả mới nhất và cập nhật nhanh nhất.</h5>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="card">
            <div class="black-text lighten-4" >
                <h4 class="center white-text uppercase bold" >Tư vấn xổ số miền Bắc</h4>
                <div class="p-20">
                    <div >
                        <p>Dịch vụ <strong>Tư vấn xổ số miền Bắc</strong> ĐẦY ĐỦ và đặc biệt CHÍNH XÁC. Đăng ký ngay </p>
                        <p>Sử dụng dịch vụ: <strong>DK MB</strong> gửi tới <strong>599</strong> (3.000 VNĐ/ngày) </p>
                        <p>Điện thoại hỗ trợ: 789</p>
                        <div class="center">
                            <a class=" waves-effect waves-light btn-large yellow-color animated bouncein delay-4 lg-button" href="<?php echo site_url('dich-vu/vnm/dang-ky-su-dung-dich-vu/TKXSMB');?>">Đăng ký</a>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="card">
            <div class="black-text lighten-4" >
                <h4 class="center white-text uppercase bold" >Tư vấn xổ số miền Trung</h4>
                <div class="p-20">
                    <div >
                        <p>Dịch vụ <strong>Tư vấn xổ số miền Trung</strong> ĐẦY ĐỦ và đặc biệt CHÍNH XÁC. Đăng ký ngay </p>
                        <p>Sử dụng dịch vụ: <strong>DK MT</strong> gửi tới <strong>599</strong> (3.000 VNĐ/ngày) </p>
                        <p>Điện thoại hỗ trợ: 789</p>
                        <div class="center">
                            <a class=" waves-effect waves-light btn-large yellow-color animated bouncein delay-4 lg-button" href="<?php echo site_url('dich-vu/vnm/dang-ky-su-dung-dich-vu/TKXSMT');?>">Đăng ký</a>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" >
        <div class="card">
            <div class="black-text lighten-4" >
                <h4 class="center white-text uppercase bold" >Tư vấn xổ số miền Nam</h4>
                <div class="p-20">
                    <div >
                        <p>Dịch vụ <strong>Tư vấn xổ số miền Nam</strong> ĐẦY ĐỦ và đặc biệt CHÍNH XÁC. Đăng ký ngay </p>
                        <p>Sử dụng dịch vụ: <strong>DK MN</strong> gửi tới <strong>599</strong> (3.000 VNĐ/ngày) </p>
                        <p>Điện thoại hỗ trợ: 789</p>
                        <div class="center">
                            <a class=" waves-effect waves-light btn-large yellow-color animated bouncein delay-4 lg-button" href="<?php echo site_url('dich-vu/vnm/dang-ky-su-dung-dich-vu/TKXSMN');?>">Đăng ký</a>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

