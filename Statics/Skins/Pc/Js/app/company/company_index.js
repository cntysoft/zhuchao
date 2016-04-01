/**
 * Created by Administrator on 2016/3/12.
 */
define(['jquery', 'slick', 'app/company/common'], function (){
   $(function (){
      $('.banner').slick({
         infinite : true,
         autoplay : true,
         autoplaySpeed : 2000,
         speed : 1000,
         dots : true,
         arrows : false,
         fade : true,
         dotsClass : "dots"
      });
   });
});