define(['zepto'],function(){
   $(function () {
      $.fn.manhuatoTop = function (options) {
         var defaults = {
            showHeight: 150,
            speed: 1000
         };
         var options = $.extend(defaults, options);
         var $toTop = $(this);
         var $top = $("#totop");
         var $ta = $("#totop a");
         var scrolltop = document.body.scrollTop;
         if (scrolltop >= options.showHeight) {
            $top.show();
         }
         $toTop.scroll(function () {
            var scrolltop = document.body.scrollTop;
            if (scrolltop >= options.showHeight) {
               $top.show();
            }
            else {
               $top.hide();
            }
         });
         $ta.click(function () {
            window.scrollTo(0,0);
            //$("html,body").animate({scrollTop: 0}, options.speed);
         });
      }
   });
   $(function(){
      $("body").prepend("<div id='totop' style='display:none'><span><a class='mainbgcolor'></a></span></div>");
      $(window).scroll(function(){
         $(window).manhuatoTop({
            showHeight: 200,//设置滚动高度时显示
            speed: 500 //返回顶部的速度以毫秒为单位
         });
      })
   });
});