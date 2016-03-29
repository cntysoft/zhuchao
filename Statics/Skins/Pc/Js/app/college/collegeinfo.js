/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 */
define(['jquery', 'module/share', 'app/common'], function (){

   //手机二维码
   $(function (){
      $('head').append('<script type="text/javascript" charset="utf-8" async=""  src="/Statics/Skins/Pc/Js/lib/qrcode.js"></script>');
      var origin = window.location.href;
//      setTimeout(function (){
      $('#weixin_code').qrcode({
         width : 200,
         height : 200,
         text : origin
      });
//      }, 1);

   });
});


