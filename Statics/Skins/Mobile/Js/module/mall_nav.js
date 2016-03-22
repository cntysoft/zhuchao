/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto'],function(){
    $(function(){
        //导航
        $('.header_right').tap(function(){
            var that=$('.top_nav_box');
            if(that.hasClass('in')){
                $(that).removeClass('in');
                return false;
            }
            else{
                $(that).addClass('in');
                return false;
            }

        });
    })
})