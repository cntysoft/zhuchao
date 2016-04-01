/**
 * Created by jiayin on 2016/3/17.
 */
define(['zepto', 'swiper', 'Front', 'search', 'module/totop'], function (){
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
        $('.header_right').not('.header_right_icon_search').tap(function (){
            var that = $('.top_nav_box');
            if(that.hasClass('in')){
                $(that).removeClass('in');
                return false;
            } else{
                $(that).addClass('in');
                return false;
            }
        });
//在线询价弹窗
        $('.zaixianxunjia').click(function (){
            $('.online').show();
            $('.footer_fixed').hide();
        });
        $(".online_close").click(function (){
            $('.online').hide();
            $('.footer_fixed').show();
        });
        $('.xunjia_button').click(function (){
            var content = $('#xunjia_content').val();
            var params = {
                number : number,
                content : content
            };
            if(content.length < 10){
                return false;
            }
            Cntysoft.Front.callApi('User', 'addXunjiadan', params, function (response){
                if(response.status){
                    $('.online').hide();
                    $('.footer_fixed').show();
                    $('#xunjia_content').val('');
                } else{
                    if(response.errorCode == 5){
                        window.location.href = window.BUYER_SITE_NAME + '/login.html';
                    }
                }
            }, true);
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
        });
    });
});