/*
Template Name: VIDOE - Video Streaming Website HTML Template
Author: Askbootstrap
Author URI: https://themeforest.net/user/askbootstrap
Version: 1.0
*/
(function($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $(document).on('click', '#sidebarToggle', function(e) {  
    e.preventDefault();
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
    if ($window.width() > 768) {
      var e0 = e.originalEvent,
      delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  
// Channels Category Owl Carousel
$(document).ready(function(){
  $('.owl-carousel-category.owl-carousel').owlCarousel();
});

$('.owl-carousel-category.owl-carousel').owlCarousel({
  loop: true,
  lazyLoad: true,
  autoplay: true,
  autoplaySpeed: 850,
  autoplayTimeout: 2000,
  autoplayHoverPause: true,
  nav: true,
  dots: false,
  navText:["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
  margin:0,
  responsive:{
    0:{
      items:2,
    },
    384:{
      items:3,
    },
    480:{
      items:4,
    },
    690:{
      items:5,
    },
    850: {
      items: 6,
    },
    1023: {
      items: 6,
    },
    1350: {
      items: 8,
    }
  }
});



 // Channels Category Owl Carousel
 $(document).ready(function(){
  $('.owl-carousel-video.owl-carousel').owlCarousel();
});

 $('.owl-carousel-video.owl-carousel').owlCarousel({
  loop: true,
  lazyLoad: true,
  autoplay: true,
  autoplaySpeed: 1000,
  autoplayTimeout: 2000,
  autoplayHoverPause: true,
  nav: true,
  dots: false,
  navText:["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
  margin:0,
  responsive:{
    0:{
      items:1,
    },
    480:{
      items:2,
      nav:false
    },
    600:{
      items:3,
      nav:false
    },
    1000: {
      items: 4
    }
  }
});


// Recommend Channels Category Owl Carousel
$(document).ready(function(){
  $('.owl-carousel-category-recommend.owl-carousel').owlCarousel();
});

$('.slider-channel-recommend.owl-carousel').owlCarousel({
  loop: true,
  lazyLoad: true,
  autoplay: false,
  autoplaySpeed: 1000,
  autoplayTimeout: 2000,
  autoplayHoverPause: true,
  nav: true,
  dots: false,
  navText:["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
  margin:0,
  responsive:{
    0:{
      items:1,
    },
    384:{
      items:3,
      nav:false
    },
    600:{
      items:3,
      nav:false
    },
    1000: {
      items: 4,
    }

  }
});


  // Tooltip
  $('[data-toggle="tooltip"]').tooltip()

  // Scroll to top button appear
  $(document).ready(function() {
    $(window).scroll(function() {
      if ($(this).scrollTop() > 300) {
        $('.scroll-to-top').fadeIn();
      } else {
        $('.scroll-to-top').fadeOut();
      }
    });
    $('.scroll-to-top').click(function() {
      $("html, body").animate({
        scrollTop: 0
      }, 600);
      return false;
    });
  });

    // menu mobile
    $(document).ready(function() {
      $('[data-sidenav]').sidenav();
    });

    // open search mobile
    $(document).ready(function() {
      $('.burger, .overlay').click(function() {
        $('.burger').toggleClass('clicked');
        $('.overlay').toggleClass('show');
        $('.open-canvas').toggleClass('show');
        $('body').toggleClass('overflow');
      });
    });

    // hidden sub menu when click body
    $(document).ready(function(){
      $('#content').click( function(e) { 
        e.stopPropagation(); // when you click within the content area, it stops the page from seeing it as clicking the body too   
      });

      $('#wrapper-body').click( function() {
        $('[data-sidenav-dropdown]').hide("toogle"); 
      });

    });

    $("button.user-dropdown-link").click(function () {
      $('#nav-user').slideToggle('toogle');
      $( "#nav-user" ).addClass("openmenu");
    });

    $(document).ready(function(){
      $('#nav-user').hasClass('openmenu');
      $('#wrapper-body').click( function() {
        $('#nav-user').hide('toogle');
      });
    });


// open / close notice login





$(document).ready(function(){
  var state = true;
  $( "button#extend-notice" ).on( "click", function() {
    if ( state ) {
      $( "button#extend-notice" ).addClass("active");
      $( "#effect" ).addClass("show");
      $( ".account-option" ).addClass("open");
      $("#effect").animate({
        left: "0"
      });
    } else {
      $( "button#extend-notice" ).removeClass("active");
      $( "#effect" ).removeClass("show");
      $( ".account-option" ).removeClass("open");
    }
    state = !state;
  });
} );







})(jQuery); // End of use strict