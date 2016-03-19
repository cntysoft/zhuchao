/**
 * Created by jiayin on 2016/3/10.
 */
define(['jquery2.1.4','slick','layer'], function () {
    $(document).ready(function () {
        //导航栏展开
        $('.sort_list').show();
        $('.all_sort_bg').show();
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

        //layer
        $('.register_btn').click(function () {
            layer.confirm('您是如何看待前端开发？', {
                btn: ['重要','奇葩'] //按钮
            }, function(){
                layer.msg('的确很重要', {icon: 1});
            }, function(){
                layer.msg('也可以这样', {
                    time: 20000, //20s后自动关闭
                    btn: ['明白了', '知道了']
                });
            });
        });



    });
});