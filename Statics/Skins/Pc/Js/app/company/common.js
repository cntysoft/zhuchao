/**
 * Created by Administrator on 2016/3/14.
 */
define(['jquery', 'module/totop', 'Front', 'Core','lazyload'], function (){
    $(function (){
        var path = window.location.pathname;
        if(path.indexOf('productlist/') >= 0){
            $('.l_nav ul li.mainbd_hover').eq(1).addClass('main_border main');
        } else if(path.indexOf('/newscenter') >= 0 || path.indexOf('/companynews') >= 0 || path.indexOf('/industrynews') >= 0){
            $('.l_nav ul li.mainbd_hover').eq(2).addClass('main_border main');
        } else if(path.indexOf('/joinus') >= 0){
            $('.l_nav ul li.mainbd_hover').eq(3).addClass('main_border main');
        } else if(path.indexOf('/about') >= 0){
            $('.l_nav ul li.mainbd_hover').eq(4).addClass('main_border main');
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
            }
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
            var origin = window.location.origin;
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
                }
            }, true);
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
});