/**
 * Created by Administrator on 2016/3/14.
 */
define(['jquery', 'module/totop', 'Front', 'Core', 'lazyload'], function (){
    $(function (){
        var path = window.location.pathname;
        if(path.indexOf('productlist/') >= 0 || path.indexOf('item/') >= 0){
            $('.product_nav').addClass('main_border main');
        } else if(path.indexOf('/case') >= 0){
            $('.case_nav').addClass('main_border main');
        } else if(path.indexOf('/newscenter') >= 0 || path.indexOf('/companynews') >= 0 || path.indexOf('/industrynews') >= 0){
            $('.news_nav').addClass('main_border main');
        } else if(path.indexOf('/joinus') >= 0){
            $('.zhaopin_nav').addClass('main_border main');
        } else if(path.indexOf('/about') >= 0){
            $('.aboutus_nav').addClass('main_border main');
        }
        if(!$('.l_nav ul li.mainbd_hover.main_border').length){
            $('.l_nav ul li.mainbd_hover').eq(0).addClass('main_border main');
        }
        $('.l_nav i.icon-sousuo').click(function (){
            var text = $(this).siblings('input').val();
            if(text.length){
                window.location.href = '/productlist/1.html?keyword=' + text;
            }
        });
        var origin = window.location.origin;
        var imgData = '';
        setTimeout(function (){
            $('head').append('<script type="text/javascript" charset="utf-8" async=""  src="/Statics/Skins/Pc/Js/lib/qrcode.js"></script>');
        }, 1);
        $('.l_top_right').mouseenter(function (){
            if(!$('#qrcode1').hasClass('loaded')){
                $('#qrcode1').qrcode({
                    render : "canvas",
                    height : 130,
                    width : 130,
                    text : origin
                });
                $('#qrcode1').addClass('loaded');
                var canvas = $('#qrcode1 canvas')[0];
// 图片导出为 png 格式
                var type = 'image/jpeg';
                imgData = canvas.toDataURL(type);
            }
        });
        /**
         * 在本地进行文件保存
         * @param  {String} data     要保存到本地的图片数据
         * @param  {String} filename 文件名
         */
        var saveFile = function (data, filename){
            var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
            save_link.href = data;
            save_link.download = filename;

            var event = document.createEvent('MouseEvents');
            event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            save_link.dispatchEvent(event);
        };

// download
        $('#qrcode1').delegate('canvas', 'click', function (){
            saveFile(imgData, $('.l_top_left span').text());
        });
        var shouchang = $('.header_top a.logout').attr('followed');
        if(shouchang == 1){
            $('#totop a.shoucang').addClass('followed');
            $('#totop a.shoucang em').html('已加');
        }
        $('#totop a.shoucang').click(function (){
            var thisurl = window.location.href;
            if($('.header_top a.logout').length == 0){
                window.location.href = window.BUYER_SITE_NAME + '/login.html?returnUrl=' + encodeURIComponent(thisurl);
            }
            if($(this).hasClass('followed')){
                return;
            }
            Cntysoft.Front.callApi('Utils', 'addFollow',
            {
                id : $('#qrcode1').attr('company')
            }, function (response){
                if(response.status){
                    $('#totop a.shoucang').addClass('followed');
                    $('#totop a.shoucang em').html('已加');
                } else{
                    if(response.errorCode == 10014){
                        window.location.href = window.BUYER_SITE_NAME + '/login.html?returnUrl=' + encodeURIComponent(thisurl);
                    }
                }
            });
        });

        $('.login_btn').click(function (){
            if($(this).attr('hrefdata')){
                var thisurl = window.location.href;
                window.location.href = $(this).attr('hrefdata') + '?returnUrl=' + encodeURIComponent(thisurl);
            }
        });
    });
    // 退出登录
    $('.logout').click(function (){
        Cntysoft.Front.callApi('Utils', 'logout', {
        }, function (response){
            if(response.status){
                window.location.reload();
            }
        }, true);
    });
    //延迟加载
    $(".lazy").lazyload({
        threshold : 10,
        effect : "fadeIn"
    });
    //统计
    var _hmt = _hmt || [];
    (function (){
        var hm = document.createElement("script");
        hm.src = "//hm.baidu.com/hm.js?150b10a8e39ef8fb51d20dc9293353fe";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
});