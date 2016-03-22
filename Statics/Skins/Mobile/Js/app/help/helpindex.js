/**
 * Created by jiayin on 2016/3/19.
 */
define(['zepto'], function () {
    $(function(){
        $('.menu_item').tap(function(){
            var that=$(this);
            if(that.hasClass('current')){
                $(this).removeClass('current').siblings().removeClass('current');
            }
            else{
                $(this).addClass('current').siblings().removeClass('current');
            }
        })
    })
});