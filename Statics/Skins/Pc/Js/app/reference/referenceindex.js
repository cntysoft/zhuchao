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
      var path = window.location.pathname;
      if(path.indexOf('laobanneican') > 0){
         $('.head ul li a').eq(0).addClass('main_bg');
      }
      if(path.indexOf('jiancaiqiwen') > 0){
         $('.head ul li a').eq(1).addClass('main_bg');
      }
      if(path.indexOf('shendujiexi') > 0){
         $('.head ul li a').eq(2).addClass('main_bg');
      }
      if(path.indexOf('zhongbangtuijian') > 0){
         $('.head ul li a').eq(3).addClass('main_bg');
      }
   });

});