/**
 * Created by jiayin on 2016/3/17.
 */
define(['zepto','swiper'],function(){
    $(function(){
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

        //广告
        var Ad = new Swiper('.module_ad3',{
            pagination : '.swiper-pagination',
            autoplay : 3000,
            speed:300,
            loop:true
        });
        //产品介绍
        $('.item_box').tap(function(){
            var that=$(this);
            if(that.hasClass('current')){
                $(this).removeClass('current');
            }
            else{
                $(this).addClass('current').siblings().removeClass('current');
            }
        })
    });
});