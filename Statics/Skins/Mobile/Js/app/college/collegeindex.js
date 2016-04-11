/**
 * Created by jiayin on 2016/3/19.
 */
define(['app/common', 'module/totop', 'module/mall_nav', 'swiper', 'Core', 'Front'], function (common){
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
      //广告
      var Ad = new Swiper('.module_ad3', {
         pagination : '.swiper-pagination',
         autoplay : 3000,
         speed : 300,
         loop : true,
         lazyLoading : true
      });

      $('#historyBack').click(function (){
         common.goBack('/category/zhuchaoschool.html');
      });
       //图片预览
       require(['module/showImage','css!http://statics-b2b.fhzc.com/Mobile/Css/swiper.min.css'],function(){
          $('.module_content .content').showImage();
      });
   });
});