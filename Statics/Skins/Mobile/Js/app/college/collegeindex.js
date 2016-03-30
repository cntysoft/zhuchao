/**
 * Created by jiayin on 2016/3/19.
 */
define(['zepto', 'module/totop', 'swiper'], function (){
   $(function (){
      //导航
      $('.more_icon').click(function (){
         if($('.top_nav_box').hasClass('hide')){
            $(this).parents('.header_right').next('.top_nav_box').removeClass('hide');
            return false;
         }
         else{
            $(".top_nav_box").addClass('hide');
            return false;
         }
      });
      $('body').click(function (){
         $(".top_nav_box").addClass('hide');
      });

      //广告
      var Ad = new Swiper('.module_ad3', {
         pagination : '.swiper-pagination',
         autoplay : 3000,
         speed : 300,
         loop : true
      });
   });
});