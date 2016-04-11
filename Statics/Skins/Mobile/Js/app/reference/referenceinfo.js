/**
 * Created by jiayin on 2016/3/18.
 */
define(['app/common', 'Core', 'Front','module/showImage'], function (common){
   $(function (){
      //导航
      $('.more_icon').click(function (){
         if($('.top_nav_box').hasClass('hide')){
            $(this).parents('.header_right').next('.top_nav_box').removeClass('hide');
            return false;
         } else{
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
      //图片预览
       require(['module/showImage','css!http://statics-b2b.fhzc.com/Mobile/Css/swiper.min.css'],function(){
          $('.module_content .content').showImage();
      });
      $('#historyBack').click(function (){
         window.location = '/category/laobanneican.html';
      });
   });
});