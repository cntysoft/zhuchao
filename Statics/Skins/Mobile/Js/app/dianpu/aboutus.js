/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 */
define(['zepto', 'module/mall_nav', 'app/dianpu/common'], function (){
   $(function (){
      var path = window.location.search;
      if(path.indexOf('zizhi') > 0){
         $('.items_box.qiyezizhi').show();
      } else if(path.indexOf('culture') > 0){
         $('.items_box.culture').show();
      } else if(path.indexOf('connect') > 0){
         $('.items_box.connect').show();
      } else if(path.indexOf('environ') > 0){
         $('.items_box.environ').show();
      } else{
         $('.items_box.intro').show();
      }
      $(window).scrollTop($(window).scrollTop() + 1);
      $(window).scrollTop($(window).scrollTop() - 1);
   });
});


