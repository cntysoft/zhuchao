/**
 * Created by jiayin on 2016/3/18.
 */
define(['app/common', 'module/mall_nav', 'Core', 'Front', 'module/showImage'], function (common){
   $(function (){
      //导航
      if($('.main_content').attr('article')){
         Cntysoft.Front.callApi('Utils', 'addArticleHits',
         {
            id : $('.main_content').attr('article')
         }, function (response){
         }
         , this);
      }
      //图片预览
      require(['module/showImage', 'css!http://statics-b2b.fhzc.com/Mobile/Css/swiper.min.css'], function (){
         $('.module_content .content').showImage();
      });
      $('#historyBack').click(function (){
         window.location = '/category/laobanneican.html';
      });
   });
});