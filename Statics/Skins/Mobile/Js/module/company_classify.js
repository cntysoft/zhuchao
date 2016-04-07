/**
 * Created by Administrator on 2016/3/16.
 */
define(['zepto'], function (){
   $(function (){
      $('.header_left').tap(function (){
         $('.classify').toggleClass('show');
      });
      $("body").bind("click", function (evt){
         if($(evt.target).parents(".classify").length == 0 && $(evt.target).parents(".header_left").length == 0)
         {
            $('.classify').removeClass('show');
         }
      });
   });
});