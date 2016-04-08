/**
 * Created by jiayin on 2016/4/7.
 */
define(['zepto','swiper','module/mall_nav','module/totop'],function(){
    $(function(){
        //广告
        var Ad = new Swiper('.module_ad3',{
            pagination : '.swiper-pagination',
            autoplay : 3000,
            speed:300,
            loop:true
        });
    });
});