/**
 * Created by Administrator on 2016/3/28.
 */
define(['jquery', 'app/common'], function (){
   $(function (){
      $('.join_list p').click(function (){
         $(this).addClass('active').siblings('p').removeClass('active');
      });
   });
});