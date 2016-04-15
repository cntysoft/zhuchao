/**
 * Created by jiayin on 2016/3/16.
 */
define(['zepto','module/mall_nav'], function (){
   $(function (){
      $('.icon-sousuo').tap(function (){
         var keyword = $('.search_input').val();
         if(keyword){
            window.location.href = $('.icon_logo a').attr('href') + '/query/1.html?keyword=' + keyword;
         }
      });
   });
});
