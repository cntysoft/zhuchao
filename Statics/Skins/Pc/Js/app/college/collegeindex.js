/**
 * Created by jiayin on 2016/3/18.
 */
define(['jquery', 'slick','app/common'], function (){
   $(function (){
      //banner
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
      //课程安排
      $('#class_items2').slick({
         autoplay : false,
         speed : 500,
         slidesToShow : 3,
         slidesToScroll : 1,
         draggable : false,
         arrows : true,
         dots : false,
         prevArrow : '.prev_btn',
         nextArrow : '.next_btn'
      });
      var path = window.location.pathname;
      if(path.indexOf('zhuchaoschool') > 0){
         $('.head ul li a').eq(0).addClass('main_bg');
      } else if(path.indexOf('dianshangzixun') > 0){
         $('.head ul li a').eq(2).addClass('main_bg');
      } else if(path.indexOf('jingdiananli') > 0){
         $('.head ul li a').eq(3).addClass('main_bg');
      } else if(path.indexOf('article') == -1){
         $('.head ul li a').eq(1).addClass('main_bg');
      }
   });

});