/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto'],function(){
    $(function(){
        //导航
        $('.more_icon').tap(function(){
            if($('.top_nav_box').hasClass('hide')){
                $(this).parents('.header_right').next('.top_nav_box').removeClass('hide');
                return false;
            }
            else{
                $(".top_nav_box").addClass('hide');
                return false;
            }
        });
        $('body').tap(function(){
            $(".top_nav_box").addClass('hide');
        });
    })
})