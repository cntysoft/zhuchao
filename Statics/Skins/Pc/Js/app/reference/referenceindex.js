/**
 * Created by wangzan on 2016/3/16.
 */
define(['jquery', 'slick'], function (){
   $(function (){
      $('.banner_box').slick({
         autoplay : true,
         autoplaySpeed : 3000,
         fade : true,
         slidesToShow : 1,
         arrows : true,
         dots : true,
         draggable : false,
         prevArrow : '.prev_btn',
         nextArrow : '.next_btn'
      });
   });

});