/**
 * Created by jiayin on 2016/4/7.
 */
define(['zepto', 'swiper', 'module/mall_nav', 'module/totop'], function (){
    $(function (){
        $('#contact').tap(function (event){
            event.stopPropagation();
            $('#contactWrap').show();
        });
        $('.bg').tap(function (){
            $('#contactWrap').hide();
        });
        $('#contactWrap').tap(function (event){
            event.stopPropagation();
        });
    });
});