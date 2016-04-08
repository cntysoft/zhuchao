/**
 * Created by jiayin on 2016/4/7.
 */
define(['zepto', 'swiper', 'module/mall_nav', 'module/totop'], function (){
    $(function (){
        //广告
        var Ad = new Swiper('.module_ad3', {
            pagination : '.swiper-pagination',
            autoplay : 3000,
            speed : 300,
            loop : true
        });
        //导航
        $('.module_nav_bar li').tap(function (){
            var indexA = $(this).index();
            $(this).addClass('current').siblings().removeClass('current');
            $('.pro_content').hide().eq(indexA).show();
        });
    });
});