/**
 * Created by Administrator on 2016/3/14.
 */
define(['jquery', 'module/totop'], function (){
   $(function (){
      var path = window.location.pathname;
      if(path.indexOf('productclassify/') >= 0 || path.indexOf('item/') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(1).addClass('main_border main');
      } else if(path.indexOf('news/') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(2).addClass('main_border main');
      } else if(path.indexOf('/recruit') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(3).addClass('main_border main');
      } else if(path.indexOf('/aboutus') >= 0){
         $('.l_nav ul li.mainbd_hover').eq(4).addClass('main_border main');
      } else{
         $('.l_nav ul li.mainbd_hover').eq(0).addClass('main_border main');
      }
   });
});