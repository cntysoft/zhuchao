/**
 * Created by wangzan on 2016/4/8.
 */
define(['zepto', 'module/mall_nav', 'app/common'], function (){
   $(function (){
       //图片预览
       require(['module/showImage','css!http://statics-b2b.fhzc.com/Mobile/Css/swiper.min.css'],function(){
          $('.items_box').showImage();
      });
   });
});