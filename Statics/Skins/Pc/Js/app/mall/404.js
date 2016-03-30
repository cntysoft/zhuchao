/**
 * Created by jiayin on 2016/3/16.
 */
define(['jquery', 'Front', 'Core'], function (){
   $(function (){
      //搜索
      $('.search_button').click(function (){
         var key = $('.search_key').val();
         if(key){
            window.location.href = $('.icon_logo a').attr('href') + '/query/1.html?keyword=' + key;
         }
      });
      // 退出登录
      $('.logout').click(function (){
         Cntysoft.Front.callApi('Utils', 'logout', {
         }, function (response){
            if(response.status){
               window.location.reload();
            }
         }, true);
         Cntysoft.Front.callApi('User', 'logout', {
         }, function (response){
            if(response.status){
               window.location.reload();
            }
         }, true);
      });
      //返回顶部
      $(function (){
         $.fn.manhuatoTop = function (options){
            var defaults = {
               showHeight : 150,
               speed : 1000
            };
            var options = $.extend(defaults, options);
            $('body').prepend('<div id="totop" style="display: block;" > <a id="totop_up"><i class = "icon-jiantou1" > </i></a> <a href="http://wpa.qq.com/msgrd?v=3&uin=2788984873&site=qq&menu=yes"> <i class = "icon-kefu"> </i><em>客服</em > </a></div >');
            var $toTop = $(this);
            var $top = $('#totop');
            var $totopUp = $('#totop_up');
            var $ta = $('#totop a');
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
            $totopUp.click(function (){
               $("html,body").animate({scrollTop : 0}, options.speed);
            });
         };
      });
      $(function (){
         $(window).manhuatoTop({
            showHeight : 500, //设置滚动高度时显示
            speed : 500 //返回顶部的速度以毫秒为单位
         });
      });
   });
});
