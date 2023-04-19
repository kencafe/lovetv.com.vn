jQuery(document).ready(function($) {
    //var cls = $(this).attr("class");
    $(".tab_container_tac .paging a").click(function() {
        var cls = $(this).attr("class");
        $('.tab_container_tac .slides_container_1 .items_page').hide();
        $('.tab_container_tac .slides_container_1 .' + cls).show();
    });


    $("#checkAll").click(function() {
        if ($(this).attr("checked") == "checked") {
            $(this).attr("checked", "checked");
            $(".Contents input").attr("checked", "checked");
            $(".Contents input").closest('tr').attr("checked", "checked");
        } else {
            $(this).removeAttr("checked", "checked");
            $(".Contents input").removeAttr("checked");
            $(".Contents input").closest('tr').removeAttr("checked");
        }
    });
    $(".Contents input").click(function() {
        var check = $(this).attr("checked");
        if (check == "checked") {
            $(this).attr("checked", "checked");
            $(this).closest('tr').attr("checked", "checked");

        } else {
            $(this).removeAttr("checked", "checked");
            $(this).closest('tr').removeAttr("checked");
        }
    });
    $("#tinmoi").show();
    $('.tabs li').each(function() {
        $(this).click(function() {
            $(".tabs li").removeAttr("id");
            $(this).attr("id", "active");
            var cl = $(this).attr("class");
            $(".tab_container .tab_content").each(function() {
                var clnd = $(this).attr("id");
                $(this).hide();
                if (cl == clnd) {
                    $(this).show();
                }
            });
        });
    });
    $("#ctl00_ctl03_ctl00_Path").after('<span id ="khuyencao">  Dung lượng không quá 100Mb(file hỗ trợ: flv,avi..)  </span>');
    $("#ctl00_ctl03_ctl00_showImage").after('<span id ="khuyencao">  Dung lượng không quá 1Mb(file hỗ trợ: jpg,gif,png..)  </span>');
    $("#ctl00_ctl03_ctl00_Image").after('<span id ="khuyencao">  Dung lượng không quá 1Mb(file hỗ trợ: jpg,gif,png..)  </span>');

    $("table.Contents .Item td").each(function() {
        if ($.trim($(this).text()) == 'Chờ kích hoạt') {
            $(this).css({
                color: 'red'
            });
        }
    });
    $("table.Contents .AlterItem td").each(function() {
        if ($.trim($(this).text()) == 'Chờ kích hoạt') {
            $(this).css({
                color: 'red'
            });
        }
    });
    $("#ctl00_ctl03_ctl00_ContentType").change(function() {

        if ($(this).val() == 0) {
            $("#tacgia_tin").show();
        } else {
            $("#tacgia_tin").hide();
        }
    });

    //xu ly bit mat khi click vao nut gui lien he, gui bai.

    var mail = $.trim(('#ctl03_ctl00_mailfrom').text);
    var diachi = $.trim(('#ctl03_ctl00_address').text);

    $('#ctl03_ctl00_btnSubmitGuest').click(function() {
        var hoten = $('#ctl03_ctl00_ten').val();
        var checkmail = ($("#ctl03_ctl00_mailfrom").val());
        if (hoten.length == 0) {
            alert("Chưa nhập họ tên");
        } else {
            if (checkmail.length == 0) {
                alert("Chưa nhập email");
            } else {
                if (checkmail.indexOf("@") == -1) {
                    alert("Email không đúng định dạng");
                }
            }
        }

    });

    //tab_right_2 xem nhieu, phan hoi nhieu, phan hoi moi nhat

    $("#xemnhieu").show();
    $('.tabs_right2 li').each(function() {
        $(this).click(function() {
            $(".tabs_right2 li").removeAttr("id");
            $(this).attr("id", "active");
            var cl1 = $(this).attr("class");

            $(".tab_container .tab_content_right").each(function() {
                var clnd1 = $(this).attr("id");
                $(this).hide();
                if (cl1 == clnd1) {
                    console.log(cl1);
                    $(this).show();
                }
            });
        });
    });

    $(".box-audio ul li.tinmoi").click(function() {
        $("#ptoai").show();
        $("#amnhacd").hide();
    });
    $(".box-audio ul li.hotnew").click(function() {
        $("#ptoai").hide();
        $("#amnhacd").show();
    });
    //tab chua viet ngay an vien dau la dung

    $("#ngayav").show();
    $('.tabs_chua_ngay li').each(function() {
        $(this).click(function() {
            $(".tabs_chua_ngay li").removeAttr("id");
            $(this).attr("id", "active");
            var cl12 = $(this).attr("class");

            $(".tab_chua .chuaviet").each(function() {
                var clnd12 = $(this).attr("id");
                console.log(clnd12);
                $(this).hide();
                if (cl12 == clnd12) {

                    console.log(cl12);
                    $(this).show();
                }
            });
        });
    });
    //playlist nhac trang chu
    $("#ngayav").show();
    $('.box-audio li').each(function() {
        $(this).click(function() {
            $(".box-audio li").removeAttr("id");
            $(this).attr("id", "active");


        });
    });

    //An border ở những thẻ cuối
    $(".menu_phapam li:last").css('border', 'none');
    $(".giangs li:last").css('border', 'none');


    //player cho trang phap thoai
    $('.link_path').click(function(e) {
        e.preventDefault();
        var player = document.getElementById('mediaplayer1');
        var tieude = $(this).text();
        var giang = $(this).attr("ID");
        document.getElementById('tieude').innerHTML = '<span>Tiêu đề: </span>' + tieude;
        document.getElementById('giangsupa').innerHTML = '<span>Giọng đọc: </span>' + giang;
        if (player != null) {
            var path = $(this).attr('video');
            player.sendEvent('load', path);
            player.sendEvent("play");
        }
    });


    // chay audio khi click vao file

    //Video right
    $('.link_jwplayer').click(function(e) {
        e.preventDefault();
        var player = document.getElementById('mediaplayer');
        if (player != null) {
            var path = $(this).attr('video');
            player.sendEvent('load', path);
            player.sendEvent("play");
        }
    });

});

function thongbao() {
    alert('Bạn đã gửi thành công!');
}

function playAudio(file) {
    var player = document.getElementById('mediaspace');
    if (player != null) {
        player.sendEvent('load', file);
        player.sendEvent("play");
    }
}
//Player js
function playerReady(obj) {
    switch (obj.id) {
        case 'mediaspace':
            displayFirstItem(obj.id);
            break;
    }
}

function itemMonitor(obj) {
    var info = gid('mediaspace').getPlaylist()[obj.index];
    gid('audio_title').innerHTML = info.title;
    gid('tit_nhac').innerHTML = info.title;
    gid('audio_author').innerHTML = info.author;
    gid('tacgia_amnhac').innerHTML = info.author;
}

function displayFirstItem(id) {
    var p = gid(id);
    if (p.getPlaylist()) {
        itemMonitor({
            index: 0
        });
        p.addControllerListener('ITEM', 'itemMonitor');
    } else {
        setTimeout(function() {
            displayFirstItem(id);
        }, 100);
    }
}

function gid(name) {
    return document.getElementById(name);
}