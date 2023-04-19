ADMIN_PAGE = "admin";
var fBrw = (navigator.userAgent.indexOf('MSIE') != -1 && navigator.userAgent.indexOf('Windows') != -1);
//
//function $() {
//    var elements = new Array();
//    for (var i = 0; i < arguments.length; i++) {
//        var element = arguments[i];
//        if (typeof element == 'string')
//            element = document.getElementById(element);
//        if (arguments.length == 1)
//            return element;
//        elements.push(element);
//    }
//    return elements;
//}
function replaceSearch(sources) {
    return sources.trim().replace(/(\s|$)+/g, '+');
}
function showAlert(msg, target) {
    if (!target) {
        jQuery.msg({
            content: msg,
            autoUnblock: true,
            fadeIn: 50,
            fadeOut: 100
        });
        //options: http://dreamerslab.com/blog/en/jquery-blockui-alternative-with-jquery-msg-plugin/
    }
    else {
        jQuery(target).show();
        jQuery(target).html(msg);
    }
}

function loading(show, target) {
    if (target) {
        if (show) {
            jQuery(target).prepend("<div id='targetloading'><img src='" + staticpath + "themes/images/ajax-loader.gif'/></div>");
        }
        else {
            jQuery('#targetloading').remove();
        }
    }
    else {
        if (show) {
            jQuery.msg({
                content: "<div id='targetloading' class='loading'><img src='" + staticpath + "themes/images/ajax-loader.gif'/></div>"
                , autoUnblock: false
                , clickUnblock: false
                , fadeIn: 50
                , css: {
                    background: 'transparent',
                    padding: '3px'
                }
            });
        }
        else {
            jQuery.msg('unblock', 50);
            jQuery('#targetloading').remove();
        }
    }
}
function HtmlEncode(s) {
    if (!s) return "";
    var el = document.createElement("div");
    el.innerText = el.textContent = s;
    s = el.innerHTML;
    return s;
}
function HtmlDecode(s) {
    if (!s) return "";
    var div = document.createElement('div');
    div.innerHTML = s;
    return div.firstChild.nodeValue;
}
function toggle(obj) {
    jQuery(obj).toggle();
}

function goto_link(link) {
    window.location = link;
}

function open_link(link, sameWindow) {
    if (sameWindow == 'false') {
        goto_link(link);
    } else {
        newwindow = window.open(link, '_blank');
        if (window.focus) {
            newwindow.focus();
        }
    }
}
function createLink(page, module, action, forward) {
    var url = "admin_" + page;

    if (module != null) {
        url += "_" + module + "_" + action;
    }

    if (forward != null) {
        url += "_" + forward;
    }

    return url + ".aspx";
}

function addLoadEvent(func) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = func;
    } else {
        window.onload = function () {
            oldonload();
            func();
        };
    }
}

function addEvent(elm, evType, fn, useCapture) {
    if (elm.addEventListener) {
        elm.addEventListener(evType, fn, useCapture);
        return true;
    } else if (elm.attachEvent) {
        var r = elm.attachEvent('on' + evType, fn);
        return r;
    } else {
        elm['on' + evType] = fn;
    }
}

function openEmail() {
    window.open(basepath + "SP/Email.aspx?u=" + window.location, null, "height=320,width=520,top=140,left=250,status=no,toolbar=no,menubar=no,location=no");
}

function openEmailAd(url, param) {
    window.open(url + "?u=" + param, null, "height=380,width=800,top=140,left=250,status=no,menubar=no,location=no,scrollbars=yes,resizable=yes");
}

function closeEmail() {
    window.close();
    return true;
}

function Cookies() {
    var cookieData = [];

    this.Read = function () {
        var pairs = new String(window.document.cookie).split(";");
        var tmp, cookieName, keyName;
        for (var i = 0; i < pairs.length; i++) {
            tmp = pairs[i].split("=");

            if (tmp.length == 3) {
                cookieName = new String(tmp[0]);
                cookieName = cookieName.replace(" ", "");

                if (cookieData[cookieName] == null)
                    cookieData[cookieName] = [];
                cookieData[cookieName][tmp[1]] = unescape(tmp[1]);
            } else //length = 2
            {
                keyName = tmp[0];
                keyName = keyName.replace(" ", "");
                if (keyName.substring(0, 12) != "ASPSESSIONID") {
                    if (cookieData[""] == null)
                        cookieData[""] = [];
                    cookieData[""][keyName] = unescape(tmp[1]);
                }
            }
        }

    };
    this.GetValue = function (cookie, key) {
        if (cookieData[cookie] != null) {
            if (cookieData[cookie][key] != null)
                return cookieData[cookie][key];
            else
                return "";
        } else
            return "";
    };
    this.SetValue = function (cookie, key, value) {
        if (cookieData[cookie] == null)
            cookieData[cookie] = [];
        cookieData[cookie][key] = value;
    };
    this.Write = function () {

        var toWrite;
        var thisCookie;
        var expireDateKill = new Date();
        var expireDate = new Date();
        expireDate.setYear(expireDate.getFullYear() + 10);
        expireDateKill.setYear(expireDateKill.getFullYear() - 10);


        var pairs = new String(window.document.cookie).split(";");
        var tmp, cookieName, keyName;
        for (var i = 0; i < pairs.length; i++) {
            tmp = pairs[i].split("=");
            if (tmp.length == 3) {
                window.document.cookie = tmp[0] + "=" + tmp[1] + "='';expires=" + expireDateKill.toGMTString();
            } else {
                keyName = tmp[0];
                keyName = keyName.replace(" ", "");
                if (keyName.substring(0, 12) != "ASPSESSIONID")
                    window.document.cookie = keyName + "='';expires=" + expireDateKill.toGMTString();
            }
        }

        for (var cookie in cookieData) {
            toWrite = "";
            thisCookie = cookieData[cookie];
            for (var key in thisCookie) {
                if (thisCookie[key] != null) {
                    if (cookie == "")
                        toWrite = key + "=" + thisCookie[key];
                    else
                        toWrite = cookie + "=" + key + "=" + escape(thisCookie[key]);
                    toWrite += "; expires=" + expireDate.toGMTString();
                    window.document.cookie = toWrite;
                }
            }
        }
    };
}

function openImage(vLink, vWidth, vHeight) {
    var sLink = (typeof (vLink.href) == 'undefined') ? vLink : vLink.href;

    if (sLink == '') {
        return false;
    }

    winDef = 'status=no,resizable=no,scrollbars=no,toolbar=no,location=no,fullscreen=no,titlebar=yes,height='.concat(vHeight).concat(',').concat('width=').concat(vWidth).concat(',');
    winDef = winDef.concat('top=').concat((screen.height - vHeight) / 2).concat(',');
    winDef = winDef.concat('left=').concat((screen.width - vWidth) / 2);
    newwin = open('', '_blank', winDef);

    newwin.document.writeln('<body style="margin:0;padding:10px;">');
    newwin.document.writeln('<a href="" onclick="window.close(); return false;"><img src="', sLink, '" alt="Close" border=0>Close</a>');
    newwin.document.writeln('</center></body>');

    if (typeof (vLink.href) != 'undefined') {
        return false;
    }
}

function openPage(vLink, vWidth, vHeight) {
    window.open(vLink, null, "height=" + vHeight + ",width=" + vWidth + ",top=" + (screen.height - vHeight) / 2 + ",left=" + (screen.width - vWidth) / 2 + ",status=no,toolbar=no,menubar=no,location=no,scrollbars=yes");
}

function confirmDelete() {
    if (confirm("Bạn có chắc chắn xóa bản ghi này chứ?")) {
        return true;
    }
    return false;
}
function confirmRestore() {
    if (confirm("Bạn có chắc chắn lấy lại thông tin từ phiên bản này chứ?")) {
        return true;
    }
    return false;
}

function useAjax(options) {
    var settings = {
        url: basepath + 'Ajax.aspx',
        //type: "POST",
        //data: {p: 'Content', act: 'loadByTitle'},
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        },
        success: function (data) {
        }
    };
    if (typeof (options) != undefined) {
        jQuery.extend(settings, options);
    }
    jQuery.ajax(settings);
}

function useAjaxForm(selector, options) {
    //http://www.malsup.com/jquery/form/#options-object
    //http://jquery.bassistance.de/validate/demo/
    //http://www.malsup.com/jquery/form/#ajaxSubmit
    var settings = {
        //target: '#divToUpdate',
        url: basepath + 'Ajax.aspx',
        clearForm: true,
        resetForm: true,
        success: function () {
        }
    };
    if (typeof (options) != undefined) {
        jQuery.extend(settings, options);
    }
    //$(selector).ajaxForm(settings);
    $(selector).ajaxSubmit(settings);
    return false;
}

function resizeImgDetailContent() {
    var max_size = 200;
    $("img").each(function (i) {
        if ($(this).height() > $(this).width()) {
            var h = max_size;
            var w = Math.ceil($(this).width() / $(this).height() * max_size);
        } else {
            var w = max_size;
            var h = Math.ceil($(this).height() / $(this).width() * max_size);
        }
        $(this).css({ height: h, width: w });
    });
}
function isValidEmail(email) {
    if (email.length > 0) {
        var pattern = /^([0-9a-zA-Z]([-.\\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\\w]*[0-9a-zA-Z]\\.)+[a-zA-Z]{2,9})$/;
        return pattern.test(email);
    }
    return false;
}
//List Video
jQuery(document).ready(function ($) {
    $(".vstatus").each(function () {
        var text = $(this).text();
        text = text.replace("1", "Chờ duyệt").replace("0", "Xuất bản");
        $(this).text(text);
    });
});

jQuery(document).ready(function ($) {
    
    $("table.Contents .Item td").each(function () {
        if ($.trim($(this).text()) == 'Chờ kích hoạt') {
            $(this).css({ color: 'red' });
        }
    });
    $("table.Contents .AlterItem td").each(function() {
        if ($.trim($(this).text()) == 'Chờ kích hoạt') {
            $(this).css({ color: 'red' });
        }
    });
});