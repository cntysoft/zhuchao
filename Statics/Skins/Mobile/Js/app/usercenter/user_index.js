/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto', 'module/mall_nav', 'Core', 'Front'], function (){
   $(function (){
      $('.cancel_login').tap(function(){
         Cntysoft.Front.callApi('User','logout', {}, function(response){
            if(response.status){
               window.location.href="/login.html";
            }
         });
      });
   });
});