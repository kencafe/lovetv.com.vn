<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>{title} - {site_name}</title>
    <meta name="author" content="{site_author}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Bootstrap CSS -->
    <link type="text/css" media="all" href="{url_assets}bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Template CSS -->
    <link type="text/css" media="all" href="{url_assets}css/style.css" rel="stylesheet" />
    <!-- Responsive CSS -->
    <link type="text/css" media="all" href="{url_assets}css/responsive.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300italic,800italic,800,700italic,700,600italic,600,400italic,300' rel='stylesheet' type='text/css' />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{url_assets}img/favicon.png" />
  </head>
  <body>
    <!-- Header -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h1>{name}</h1>
            <h2>{title}</h2>
            <p>{heading}</p>
          </div>
        </div>
      </div>
    </section>
    <!-- end Header -->

    <!-- Illustration -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="illustration">
              <div class="boat"></div>
              <div class="water1"></div>
              <div class="water2"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end Illustration -->

    <!-- Button -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <a href="{site_link}"><div class="btn btn-action">Take me out of here</div></a>
          </div>
        </div>
      </div>
    </section>
    <!-- end Button -->

    <!-- Footer -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <p>&copy; Powered by <a href="{site_link}" title="{site_name}"><strong>{site_name}</strong></a> All Rights Reserved.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- end Footer -->

    <!-- Scripts -->
    <script src="{url_assets}js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="{url_assets}bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>