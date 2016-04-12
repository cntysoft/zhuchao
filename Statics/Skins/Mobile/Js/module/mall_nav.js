/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto'], function (){
   $(function (){
      //导航
      $('.more_icon').tap(function (){
         if($('.top_nav_box').hasClass('hide')){
            $(this).parents('.header_right').next('.top_nav_box').removeClass('hide');
            return false;
         }
         else{
            $(".top_nav_box").addClass('hide');
            return false;
         }
      });
      $(this).scroll(function (){
         $(".top_nav_box").addClass('hide');
      });
      $('.header_right.fr').siblings().click(function (){
         if(!$(".top_nav_box").hasClass('hide')){
            $(".top_nav_box").addClass('hide');
         }
      });
      $('.headbar.clearfix').siblings().click(function (){
         if(!$(".top_nav_box").hasClass('hide')){
            $(".top_nav_box").addClass('hide');
         }
      });
   });
});