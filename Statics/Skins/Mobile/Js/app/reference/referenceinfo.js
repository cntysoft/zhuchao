/**
 * Created by jiayin on 2016/3/18.
 */
define(['zepto', 'module/totop', 'Core', 'Front'], function (){
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
      if($('.main_content').attr('article')){
         Cntysoft.Front.callApi('Utils', 'addArticleHits',
         {
            id : $('.main_content').attr('article')
         }, function (response){
         }
         , this);
      }
   });
});