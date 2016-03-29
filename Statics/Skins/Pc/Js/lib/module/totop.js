/**
 * Created by wangzan on 2016/1/12.
 */
define(['jquery'],function(){
    $(function () {
        $.fn.manhuatoTop = function (options) {
            var defaults = {
                showHeight: 150,
                speed: 1000
            };
            var options = $.extend(defaults, options);
            $("body").prepend("<div id='totop'><a><i class='icon-jiantou1'></i></a><a class='shoucang'><i class='icon-empty-star'></i><em>收藏</em></a><a><i class='icon-kefu1'></i><em>客服</em>" +
                "<div class='block'><span>电话：12345678910</span><span>QQ：123456789</span></div>" +
                "</a></div>");
            var $toTop = $(this);
            var $top = $("#totop");
            var $ta = $("#totop>a:first-child");
            var scrolltop = $(window).scrollTop();
            $top.hide();
            if (scrolltop >= options.showHeight) {
                $top.show();
            }
            $toTop.scroll(function () {
                var scrolltop = $(this).scrollTop();
                if (scrolltop >= options.showHeight) {
                    $top.show();
                }
                else {
                    $top.hide();
                }
            });
            $ta .click(function () {
                $("html,body").animate({scrollTop: 0}, options.speed);
            });
        }
    });

    $(function(){
        $(window).manhuatoTop({
            showHeight: 500,//设置滚动高度时显示
            speed: 500 //返回顶部的速度以毫秒为单位
        });
    })
});