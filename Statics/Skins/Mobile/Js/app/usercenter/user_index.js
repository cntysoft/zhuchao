/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto', 'module/mall_nav', 'Core', 'Front'], function (){
   $(function (){
      console.log($().click);
      $('body').tap(function(){
         console.log('asdfadsf');
      });
      $('.cancel_login').tap(function(){
          console.log('ffffffffff');
         Cntysoft.Front.callApi('User','logout', {}, function(response){
            if(response.status){
               window.location.href="/login.html";
            }
         });
      });
   });
});