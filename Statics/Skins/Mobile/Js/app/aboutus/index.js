/**
 * Created by wangzan on 2016/4/12.
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
        $('#contact').tap(function(event){
            event.stopPropagation();
            $('#contactWrap').show();
        });
        $('body').tap(function(){
            $('#contactWrap').hide();
        });
        $('.detail').tap(function (event) {
            event.stopPropagation();
        });

        $('.zihi ul li div:first-child').click(function () {
           $(this).parents('li').toggleClass('on');
            if( $(this).find('i').hasClass('icon-xiangxiajiantou')){
                $(this).find('i').removeClass('icon-xiangxiajiantou').addClass('icon-xiangshangjiantou')
            }else {
                $(this).find('i').removeClass('icon-xiangshangjiantou').addClass('icon-xiangxiajiantou')
            }
        });
        $('.join_us ul li div:first-child').click(function () {
            $(this).parents('li').toggleClass('on');
            if( $(this).find('i').hasClass('icon-xiangxiajiantou')){
                $(this).find('i').removeClass('icon-xiangxiajiantou').addClass('icon-xiangshangjiantou')
            }else {
                $(this).find('i').removeClass('icon-xiangshangjiantou').addClass('icon-xiangxiajiantou')
            }
        });
        

    });
});