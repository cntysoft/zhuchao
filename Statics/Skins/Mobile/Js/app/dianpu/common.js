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
    });
});