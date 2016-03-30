/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto', 'module/mall_nav', 'module/totop'], function (){
   $(function (){
      $('.l_nav span').tap(function (){
         $('.his_lis').hide();
         $('.l_nav span').removeClass('main');
         $(this).addClass('main');
         $('.his_lis').eq($.inArray(this, $('.l_nav span'))).show();
      });
   });
});