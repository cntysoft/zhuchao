/**
 * Created by Administrator on 2016/3/16.
 */
define(['zepto', 'swiper', 'module/company_classify', 'app/common'], function (){
   $(function (){
      var banner = new Swiper('.banner', {
         pagination : '.swiper-pagination',
         autoplay : 3000,
         speed : 300,
         loop : true
      });
   });
});