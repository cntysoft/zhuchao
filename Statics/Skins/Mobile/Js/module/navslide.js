/**
 * Created by jiayin on 2016/1/5.
 */
define(['zepto', 'swiper'], function () {
    $(function () {
        var navSlide = new Swiper('.module_text_slide_nav', {
            slidesPerView: 4,
            spaceBetween: 10
        });
        //导航点击状态
        $('.module_text_slide_nav').find('.text_slide_nav_list:first-child').addClass('mainbordercolor maincolor');
        $('.module_text_slide_nav .text_slide_nav_list').tap(function () {
            $(this).addClass('mainbordercolor maincolor').siblings().removeClass('mainbordercolor maincolor');
        });
        //导航横排
        $('.NavInline li:first-child a').addClass('mainbordercolor maincolor');
        $('.NavInline li').tap(function(){
            $('.NavInline li a').removeClass('mainbordercolor maincolor');
            $(this).find('a').addClass('mainbordercolor maincolor');
        });
    })
});
