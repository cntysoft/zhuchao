/**
 * Created by jiayin on 2016/3/10.
 */
define(['jquery','slick','layer','app/common'], function () {
    $(document).ready(function () {
        // banner
        $('.banner_box').slick({
            dots: true,
            arrows: false,
            dotsClass: 'banner_bottom_btn',
            easing: 'linear'
        });
        //产品切换
        var proBox = $('div[pro-box^="pro-box"]');
        //循环初始化每个展示商品
        for (var i = 1; i < proBox.length + 1; i++) {
            $('div[pro-box^="pro-box' + i + '"]').slick({
                dots: false,
                arrows: true,
                easing: 'linear',
                prevArrow: ".left_btn" + i ,
                nextArrow: ".right_btn"+ i
            });
        }
        $('.search_button').click(function(){
            var key = $('.search_key').val();
            if(key){
               window.location.href = '/query/1.html?keyword=' + key;
            }
        });
        
        //产品切换
        //$('.pro_banner_box').slick({
        //    dots: false,
        //    arrows: true,
        //    prevArrow: '.pro_banner_prev',
        //    nextArrow: '.pro_banner_next',
        //    easing: 'linear'
        //});
        //品牌切换
        //$('.pro_brank').slick({
        //    dots: false,
        //    arrows: true,
        //    prevArrow: '.pro_brank_prev',
        //    nextArrow: '.pro_brank_next',
        //    easing: 'linear'
        //});

    });
});