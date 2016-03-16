/**
 * Created by jiayin on 2016/3/14.
 */
define(['jquery', 'slick'], function () {
    $(document).ready(function () {
        // banner
        $('.banner_box').slick({
            dots: true,
            arrows: false,
            dotsClass: 'banner_bottom_btn',
            easing: 'linear'
        });
        //企业介绍图片
        $('.about_img').slick({
            dots: true,
            arrows: false,
            dotsClass: 'banner_bottom_btn',
            easing: 'linear'
        });
    });
});