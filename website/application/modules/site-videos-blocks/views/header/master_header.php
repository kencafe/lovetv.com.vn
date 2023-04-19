<header id="masthead" class="site-header header">
    <div class="header-content">
        <div class="container-fluid">
            <nav class="navbar navbar-expand navbar-light bg-white static-top lovetv-nav sticky-top">
                <div class="wrapper-nav">
                    <div class="col-md-2 col-sm-8 col-xs-8">
                        <button class="btn btn-link btn-sm text-secondary order-1 order-sm-0" id="sidebarToggle">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a href="javascript:;" class="toggle mobile-menu" id="sidenav-toggle">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </a>
                        <div class="logo">
                            <h1 class="title-logo">
                                <a href="<?= site_url(); ?>" target="_self"><img class="logo-img" src="<?= assets_url('logo.png'); ?>" alt="LoveTV" title="LoveTV" width="100">
                                </a>
                            </h1>
                        </div>
                    </div>
                    <div class="col-md-10 col-sm-4 col-xs-4">
                        <?= modules::run('site-videos-blocks/header/header_search_form'); ?>
                        <?= modules::run('site-videos-blocks/header/header_user_bar'); ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <?= modules::run('site-videos-blocks/header/header_mobile'); ?>
    <?= modules::run('site-videos-blocks/header/header_mobile_search_form'); ?>
    <div class="overlay"></div>
</header>