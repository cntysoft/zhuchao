/**
 * Created by Administrator on 2016/3/14.
 */
define(['jquery', 'module/totop','app/common'], function (){
   $(function (){
      var path = window.location.pathname;
      if(path.indexOf('productlist/') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(1).addClass('main_border main');
      } else if(path.indexOf('/newscenter') >= 0 || path.indexOf('/companynews') >= 0 || path.indexOf('/industrynews') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(2).addClass('main_border main');
      } else if(path.indexOf('/joinus') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(3).addClass('main_border main');
      } else if(path.indexOf('/about') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(4).addClass('main_border main');
      }
      if(!$('.l_nav ul li.mainbd_hover.main_border').length){
         $('.l_nav ul li.mainbd_hover').eq(0).addClass('main_border main');
      }
      $('.l_nav i.icon-sousuo').click(function (){
         var text = $(this).siblings('input').val();
         if(text.length){
            window.location.href = '/productlist/1.html?keyword=' + text;
         }
      });
   });
});