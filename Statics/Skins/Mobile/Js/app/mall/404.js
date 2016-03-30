/**
 * Created by jiayin on 2016/3/16.
 */
define(['zepto'], function (){
   $(function (){
      //导航
      $('.header_right').not('.header_right_icon_search').tap(function (){
         var that = $('.top_nav_box');
         if(that.hasClass('in')){
            $(that).removeClass('in');
            return false;
         } else{
            $(that).addClass('in');
            return false;
         }
      });
      $('.icon-sousuo').tap(function (){
         var keyword = $('.search_input').val();
         if(keyword){
            window.location.href = $('.icon_logo a').attr('href') + '/query/1.html?keyword=' + keyword;
         }
      });
   });
});
