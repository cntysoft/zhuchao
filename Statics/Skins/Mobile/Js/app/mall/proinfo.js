/**
 * Created by jiayin on 2016/3/17.
 */
define(['zepto', 'swiper', 'Front','search'], function (){
    $(function (){
        var number = window.location.pathname.split('/')[2].split('.')[0];
        //增加点击量
        Cntysoft.Front.callApi('Utils', 'addHits', {
            number : number
        }, function (response){
            if(response.status){
            }
        }, true);
        //导航
        $('.header_right').click(function (){
            var that = $('.top_nav_box');
            if(that.hasClass('in')){
                $(that).removeClass('in');
                return false;
            } else{
                $(that).addClass('in');
                return false;
            }
        });

        //广告
        var Ad = new Swiper('.module_ad3', {
            pagination : '.swiper-pagination',
            autoplay : 3000,
            speed : 300,
            loop : true
        });
        //产品介绍
        $('.item_box').click(function (){
            var that = $(this);
            if(that.hasClass('current')){
                $(this).removeClass('current');
            } else{
                $(this).addClass('current').siblings().removeClass('current');
            }
        })
    });
});