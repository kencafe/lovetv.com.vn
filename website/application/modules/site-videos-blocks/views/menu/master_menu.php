<nav class="sidenav sidebar navbar-nav sidebar vertical-menu" data-sidenav data-sidenav-toggle="#sidenav-toggle">
    <ul class="sidenav-menu nav navbar-nav  ">
        <!-- <ul class="sidebar navbar-nav"> -->
        <li class="nav-item home active">
            <a href="<?= site_url(); ?>" class="nav-link active">
                <i class="fa fa-home"></i><span class="sidenav-link-title name-item">Trang chủ</span>
            </a>
        </li>
        <?= modules::run('site-videos-blocks/menu/category_menu'); ?>
        <?= modules::run('site-videos-blocks/menu/user_menu'); ?>
    </ul>
</nav>