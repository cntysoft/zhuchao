/**
 * Created by jiayin on 2016/3/19.
 */
define(['zepto', 'app/common', 'Front'], function (){
   $(function (){
      //增加文章阅读量
      var url = window.location.pathname.split('/');
      if(url[1] == 'article'){
         var itemId = url[2].split('.')[0];
         Cntysoft.Front.callApi('Utils', 'addArticleHits', {
            id : itemId
         }, function (response){

         }, this);
      }
      $('.menu_item').click(function (){
         var that = $(this);
         if(that.hasClass('current')){
            $(this).removeClass('current').siblings().removeClass('current');
         } else{
            $(this).addClass('current').siblings().removeClass('current');
         }
      });
      //图片预览
      require(['module/showImage','css!http://statics-b2b.fhzc.com/Mobile/Css/swiper.min.css'],function(){
          $('.module_content').showImage();
      });
   });
});