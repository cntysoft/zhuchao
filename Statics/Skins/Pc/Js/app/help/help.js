/**
 * Created by jiayin on 2016/3/11.
 */
define(['jquery','module/helpbase'], function () {
    $(function(){
        $('.left_menu .menu_item').click(function(){
            var that=$(this);
            if(that.hasClass('current')){
                $(this).removeClass('current').siblings().removeClass('current');
                $(this).find('.sub_menu').slideUp(300);
            }
            else{
                $('.left_menu .menu_item').removeClass('current');
                $(this).addClass('current');
                $('.sub_menu').slideUp(300);
                $(this).find('.sub_menu').slideDown(300);
            }
        })
    })
});