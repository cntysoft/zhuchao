/**
 * Created by Administrator on 2016/4/12.
 */
define(['jquery', 'slick', 'app/kelepc/com'], function (){
   $(function (){
      $('.l_banner').slick({
         infinite : true,
         slidesToShow : 1,
         slidesToScroll : 1,
         dots : true,
         arrows : false,
         dotsClass : 'banner_dots'
      });
      $(".case_box").slick({
         infinite : true,
         slidesToShow : 4,
         slidesToScroll : 1,
         autoplay : true,
         autoplaySpeed : 2500,
         arrows : true,
         prevArrow : ".case_prev",
         nextArrow : '.case_next'
      });
   });
});