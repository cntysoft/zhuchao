/**
 * Created by jiayin on 2016/3/16.
 */
define(['zepto'], function () {
    $(function () {
        //导航
        $('.header_right').tap(function(){
            var that=$('.top_nav_box');
            if(that.hasClass('in')){
                $(that).removeClass('in');
                return false;
            }
            else{
                $(that).addClass('in');
                return false;
            }

        });

        //点击头部筛选条件
        $('#mallSearchNav li').tap(function () {
            var that = $(this);
            $(this).addClass('current').siblings().removeClass('current up down');
            if ($.inArray(this, $('#mallSearchNav li')) < 3) {
                if (that.hasClass('search_price')) {
                    if (that.hasClass('up')) {
                        $(this).removeClass('up').addClass('down')
                    }
                    else {
                        $(this).addClass('up').removeClass('down')
                    }
                }
            }
            else {
                $('html,body').addClass('current');
                $('#shaixuanBox').show();
            }
        });
        //筛选条件展开
        $('#shaixuanListNav>li').tap(function () {
            var that=$(this);
           if(that.hasClass('current')){
               $(this).removeClass('current');
           }
            else{
               $(this).addClass('current').siblings().removeClass('current');
           }
        });
        $('.sub_nav li').tap(function () {
            var text = $(this).text();
            $(this).parents('li').find('.choose_text').html(text);
        });
        $('.sub_nav li').tap(function () {
            $('.sub_nav li').removeClass('add_color');
            $(this).addClass('add_color').siblings().removeClass('add_color');
        });
        $('.reset').tap(function () {
            $('.choose_text').html('');
        });
        $('.enter').tap(function () {
            $('#shaixuanBox').hide();
            $('html,body').removeClass('current');
        });
    });
});