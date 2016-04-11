define(['zepto', 'lazyload'], function (){
    $(function (){
        //联系方式
        $('#contact').tap(function (event){
            event.stopPropagation();
            $('#contactWrap').show();
        });
        $('body').tap(function (){
            $('#contactWrap').hide();
        });
        $('#contactWrap .detail').tap(function (event){
            event.stopPropagation();
        });
        $(function (){
            $.fn.manhuatoTop = function (options){
                var defaults = {
                    showHeight : 150,
                    speed : 1000
                };
                var options = $.extend(defaults, options);
                var $toTop = $(this);
                var $top = $("#totop");
                var $ta = $("#totop a");
                var scrolltop = document.body.scrollTop;
                if(scrolltop >= options.showHeight){
                    $top.show();
                }
                $toTop.scroll(function (){
                    var scrolltop = document.body.scrollTop;
                    if(scrolltop >= options.showHeight){
                        $top.show();
                    } else{
                        $top.hide();
                    }
                });
                $ta.click(function (){
                    window.scrollTo(0, 0);
                    //$("html,body").animate({scrollTop: 0}, options.speed);
                });
            }
        });
        $(function (){
            $("body").prepend("<div id='totop' style='display:none'><span><a class='mainbgcolor'></a></span></div>");
            $(window).scroll(function (){
                $(window).manhuatoTop({
                    showHeight : 200, //设置滚动高度时显示
                    speed : 500 //返回顶部的速度以毫秒为单位
                });
            });
        });
        //延迟加载
        $('.lazy').picLazyLoad({
            threshold : 50
        });
        $(function (){
            $(window).scrollTop($(window).scrollTop() + 1);
            $(window).scrollTop($(window).scrollTop() - 1);
        });
        //统计
        var _hmt = _hmt || [];
        (function (){
            var host = window.location.host;
            //当域名为fhzc.com时加载统计js
            if(/fhzc.com/.test(host)){
                var hm = document.createElement("script");
                hm.src = "//hm.baidu.com/hm.js?150b10a8e39ef8fb51d20dc9293353fe";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            }
        })();
        //生成微信分享图片
        var shareImg = false;//标识是否生成分享图片
        var cdnImgReg = /http\:\/\/img-b2b.*/;
        if(!shareImg){
            var pathname = window.location.pathname;
            var logoPath = [
                '/newscenter.html',
                '/companynews.html',
                '/industrynews.html',
                '/joinus.html',
                '/about.html',
                '/'
            ];
            if($.inArray(pathname, logoPath) != -1 || pathname.match(/^(\/productlist\/[\d]*?.html|\/caselist\/1.html)$/)){
                shareImg = $('.icon_logo img').attr('src');
            }
        }
        if(!shareImg && $('.module_ad_img').length && $('.module_ad_img').eq(0).css('background-image')){
            var shareImg = $('.module_ad_img').eq(0).css('background-image');
            shareImg = shareImg.match(/(http\:\/\/.+?\.(?:gif|jpg|jpeg|bmp|png))\@(?:[\d]*?w\_)(?:[\d]*?h\_).*?\.src/);
            if(shareImg.length == 2){
                shareImg = shareImg[1];
            }
        }
         if(!shareImg && $('img').length > 0){
            $.each($('img'), function (index, item){
                if(!$(item).attr('alt')){
                    if($(item).attr('data-original')){
                        shareImg = $(item).attr('data-original');
                    } else{
                        shareImg = $(item).attr('src');
                    }
                    return false;
                }
            });
        }
        if(!shareImg){
            shareImg = $('.icon_logo img').src();
        }
        if(cdnImgReg.test(shareImg)){
            shareImg = shareImg.match(/(http\:\/\/.+?\.(?:gif|jpg|jpeg|bmp|png))/);
            if(shareImg.length && shareImg.length>0){
                shareImg = shareImg[1];
            }
            shareImg = shareImg + '@300w_300h_1c_1e.src';
            $('body').prepend('<div class="hide"><img src="' + shareImg + '"></div>');
        }

    });
});