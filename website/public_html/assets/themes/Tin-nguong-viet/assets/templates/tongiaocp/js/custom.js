jQuery(document).ready(function($) {
    $(function() {
        //slideshow
        $('#slides').slides({
            generateNextPrev: true,
            next: "nexBeauty",
            prev: "preBeauty",
            effect: "fade",
            play: 10000,
            pause: 5000,

            hoverPause: true,
            pagination: true,
            generatePagination: true,
            animationStart: function(current) {
                $('.captiondep').animate({
                    bottom: -35
                }, 100);
                if (window.console && console.log) {
                    // example return of current slide number
                    // console.log('animationStart on slide: ', current);
                };
            },
            animationComplete: function(current) {
                $('.captiondep').animate({
                    bottom: 0
                }, 200);
                if (window.console && console.log) {
                    // example return of current slide number
                    //console.log('animationComplete on slide: ', current);
                };
            },
            slidesLoaded: function() {
                $('.captiondep').animate({
                    bottom: 0
                }, 200);
            }
        });

        //phong su anh
        $('#slides1').slides({
            generateNextPrev: false,
            next: "nexBeauty",
            prev: "preBeauty",
            effect: "slide",
            play: 10000,
            pause: 5000,
            hoverPause: true,
            pagination: false,
            generatePagination: false,
            animationStart: function(current) {
                $('.captiondep').animate({
                    bottom: -35
                }, 100);
                if (window.console && console.log) {
                    // example return of current slide number
                    // console.log('animationStart on slide: ', current);
                };
            },
            animationComplete: function(current) {
                $('.captiondep').animate({
                    bottom: 0
                }, 200);
                if (window.console && console.log) {
                    // example return of current slide number
                    //console.log('animationComplete on slide: ', current);
                };
            },
            slidesLoaded: function() {
                $('.captiondep').animate({
                    bottom: 0
                }, 200);
            }
        });

        //scrollpane
        $('.scroll-pane').jScrollPane();
        $(function() {
            $('.scroll-pane2').jScrollPane({
                showArrows: true,
                horizontalGutter: 30,
                verticalGutter: 30
            });
        });
        //forrm
        $('form.transform').jqTransform({
            imgPath: 'images/form/'
        });

        //tab
        $('#tabs div.tabcontent').hide();
        $('#tabs div.tabcontent:first').show();
        $('#tabs ul.tabid li:first').addClass('active');
        $('#tabs ul.tabid li a.tabre').click(function() {
            $('#tabs ul.tabid li').removeClass('active');
            $(this).parent().addClass('active');
            var currentTab = $(this).attr('href');
            $('#tabs div.tabcontent').hide();
            $(currentTab).show();
            return false;
        });
        $('#tabs2 div.tabcontent').hide();
        $('#tabs2 div.tabcontent:first').show();
        $('#tabs2 ul.tabid li:first').addClass('active');
        $('#tabs2 ul.tabid li a.tabre').click(function() {
            $('#tabs2 ul.tabid li').removeClass('active');
            $(this).parent().addClass('active');
            var currentTab = $(this).attr('href');
            $('#tabs2 div.tabcontent').hide();
            $(currentTab).show();
            return false;
        });

        //slideshow - reportage
        $("#large_images li").each(function(index, element) {
            $(element).attr("class", 'hide');
        });
        $("#large_images li").each(function(index, element) {
            $(element).attr("id", 'img' + index);
        });
        $("#thumb_holder li a").each(function(index, element) {
            $(element).attr("rel", 'img' + index);
        });

        var mainImg = 'img0';
        var current = 'img0';

        $('#img0').css('display', 'inline');
        $('#img0').addClass('current');

        $('#thumb_holder li a').click(function() {
            //alert(123);
            mainImg = $(this).attr('rel');
            markBorder('click_thumb', mainImg);
            markBorder('', mainImg);
            if (mainImg != current) {
                $('.current').fadeOut('slow');
                $('#' + mainImg).fadeIn('slow', function() {
                    $(this).addClass('current');
                    current = mainImg;

                });
            }
        });

        //slideshow - reportage2
        $("#large_images2 li").each(function(index2, element) {
            $(element).attr("class", 'hide');
        });
        $("#large_images2 li").each(function(index2, element) {
            $(element).attr("id", 'img' + index2);
        });
        $("#thumb_holder2 li a").each(function(index2, element) {
            $(element).attr("rel", 'img' + index2);
        });

        var mainImg = 'img3';
        var current = 'img3';

        $('#img3').css('display', 'inline');
        $('#img3').addClass('current');

        $('#thumb_holder2 li a').click(function() {
            mainImg = $(this).attr('rel');
            $('#thumb_holder2 li a').removeClass('currentli');
            $(this).addClass('currentli');
            markBorder('click_link', mainImg);

            if (mainImg != current) {
                $('.current').fadeOut('slow');
                $('#' + mainImg).fadeIn('slow', function() {
                    $(this).addClass('current');
                    current = mainImg;

                });
            }
        });
        var indexCurrent = 1;
        $('#thumb_holder2 li a').eq(0).trigger('click').ready(function() {
            var a = setInterval(function() {
                $('#thumb_holder2 li a').eq(indexCurrent).trigger('click');
                indexCurrent++;
                if (indexCurrent == 3) indexCurrent = 0;
            }, 10000);
        });

        function markBorder(type, sIndex) {
            if (type == 'click_thumb') {
                $('#thumb_holder2 li a').removeClass('currentli').each(function(index) {
                    if ($(this).attr('rel') == sIndex) {
                        $(this).addClass('currentli');
                    }
                });
            } else {
                $('#thumb_holder a').attr('style', '').each(function(index) {
                    if ($(this).attr('rel') == sIndex) {
                        $(this).css({
                            opacity: '1'
                        });
                    }
                });
            }
            indexCurrent = sIndex.substr(3);
            //alert(indexCurrent);
        }

    });

    function gone() {
        location = document.jumpy.example.options[document.jumpy.example.selectedIndex].value
    }

    /*slider home*/
    $(document).ready(function() {
        $('.revolution-slider.owl-carousel').owlCarousel();
    });

    $('.revolution-slider.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplaySpeed: 1000,
        autoplayTimeout: 10000,
        responsive: {
            0: {
                items: 1
            },
            760: {
                items: 1
            }
        }
    })

    /*slider picture section*/
    $(document).ready(function() {
        $('.slidebox-image.owl-carousel').owlCarousel();
    });

    $('.slidebox-image.owl-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        autoplay: true,
        autoplaySpeed: 1000,
        autoplayTimeout: 10000,
        responsive: {
            0: {
                items: 1
            },
            660: {
                items: 2
            },
            990: {
                items: 1
            }
        }
    })

    /*gallery image*/
    $(document).ready(function() {
        $('.revolution-slider.owl-carousel').owlCarousel();
    });

    $('.gallery-images-slider.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: false,
        autoplaySpeed: 1000,
        autoplayTimeout: 5000,
        responsive: {
            0: {
                items: 1
            },
            760: {
                items: 1
            }
        }
    })
    $(document).ready(function() {
        $('.revolution-slider.slider-food.owl-carousel').owlCarousel();
    });

    $('.slider-home .revolution-slider.slider-food.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: false,
        autoplaySpeed: 500,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1
            },
            760: {
                items: 1
            }
        }
    })

    $(document).ready(function() {
        $('.blog-slider.owl-carousel').owlCarousel();
    });

    $('.blog-slider.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: false,
        autoplaySpeed: 500,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1
            },
            760: {
                items: 1
            }
        }
    })

    // slide Lễ hội bốn phương
    $(document).ready(function() {

        // Tùy biến cấu hình
        $("#foo3").carouFredSel({
            items: 4,
            margin: 0,
            width: "100%",
            direction: "bottom",
            scroll: {
                visible: 4,

                easing: "swing",
                duration: 300,
                pauseOnHover: true
            },
            items: {
                visible: 4,
                start: 1,
                height: "variable",
                autoplaySpeed: 500,
                autoplayTimeout: 1000,
            }
        });
    })

    // slide đời sống tín ngưỡng
    $(document).ready(function() {
        $('.blog-slider.owl-carousel').owlCarousel();
    });

    $('.list-blog-slider.owl-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        autoplay: true,
        autoplaySpeed: 500,
        autoplayTimeout: 1500,
        responsive: {
            0: {
                items: 2
            },
            480: {
                items: 2
            },
            720: {
                items: 3
            }
        }
    })


    // slider blogs trang con
    // slide đời sống tín ngưỡng
    $(document).ready(function() {
        $('.list-blogs-slider.owl-carousel').owlCarousel();
    });

    $('.list-blogs-slider.owl-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        autoplay: false,
        autoplaySpeed: 500,
        autoplayTimeout: 1200,
        responsive: {
            0: {
                items: 2
            },
            480: {
                items: 2
            },
            690: {
                items: 3
            }
        }
    })

    /* calender*/
    $(document).ready(function() {
        (function($) {
            //Căn giữa phần tử thuộc tính là absolute so với phần hiển thị của trình duyệt, chỉ dùng cho phần tử absolute đối với body
            $.fn.absoluteCenter = function() {
                this.each(function() {
                    var top = -($(this).outerHeight() / 2) + 'px';
                    var left = -($(this).outerWidth() / 2) + 'px';
                    $(this).css({
                        'position': 'absolute',
                        'position': 'fixed',
                        'margin-top': top,
                        'margin-left': left,
                        'top': '50%',
                        'left': '50%'
                    });
                    return this;
                });
            }
        })(jQuery);

        $('.lunarEvent').click(function() {
            //Đặt biến cho các đối tượng để gọi dễ dàng
            var hanoi = $(this).find("span.nd1").html();
            $(".popup-content").html(hanoi);
            var bg = $('.popup-bg');
            var obj = $('.popup222');
            var btnClose = obj.find('.popup-close');
            //Hiện các đối tượng
            bg.animate({
                opacity: 0.4
            }, 0).fadeIn(1000); //cho nền trong suốt
            obj.fadeIn(100).draggable({
                cursor: 'move',
                handle: '.popup-header'
            }).absoluteCenter();
            //căn giữa popup và thêm draggable của jquery UI cho phần header của popup
            //Đóng popup khi nhấn nút
            btnClose.click(function() {
                bg.fadeOut(100);
                obj.fadeOut(100);
            });
            //Đóng popup khi nhấn background
            bg.click(function() {
                btnClose.click(); //Kế thừa nút đóng ở trên
            });
            //Đóng popup khi nhấn nút Esc trên bàn phím
            $(document).keydown(function(e) {
                if (e.keyCode == 27) {
                    btnClose.click(); //Kế thừa nút đóng ở trên
                }
            });
            return false;
        });
    });


    /*back to top */
    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#goTop').fadeIn();
            } else {
                $('#goTop').fadeOut();
            }
        });
        $('#goTop').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    });



});