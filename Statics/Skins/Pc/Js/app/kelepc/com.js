/**
 * Created by jiayin on 2016/3/16.
 */
define(['jquery', 'Front', 'Core'], function (){
   $(function (){
      $.fn.manhuatoTop = function (options){
         var defaults = {
            showHeight : 150,
            speed : 1000
         };
         var options = $.extend(defaults, options);
         $("body").prepend("<div id='totop' class='main_bg'><a><img src='/Statics/Skins/Pc/Images/kelepc/icon/icon_totop.png'> </a></div>");
         var $toTop = $(this);
         var $top = $("#totop");
         var $ta = $("#totop a");
         var scrolltop = $(window).scrollTop();
         $top.hide();
         if(scrolltop >= options.showHeight){
            $top.show();
         }
         $toTop.scroll(function (){
            var scrolltop = $(this).scrollTop();
            if(scrolltop >= options.showHeight){
               $top.show();
            }
            else{
               $top.hide();
            }
         });
         $top.click(function (){
            $("html,body").animate({scrollTop : 0}, options.speed);
         });
      };
   });
   $(function (){
      $(window).manhuatoTop({
         showHeight : 500, //设置滚动高度时显示
         speed : 500 //返回顶部的速度以毫秒为单位
      });
      $('#providerlogout').click(function (){
         Cntysoft.Front.callApi('Provider', 'logout', {
         }, function (response){
            if(response.status){
               window.location.reload();
            }
         }, true);
      });
   });
});
