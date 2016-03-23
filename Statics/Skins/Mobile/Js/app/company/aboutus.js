/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'module/totop'], function (){
   $(function (){
      $('.employ_ele').click(function (){
         $(this).siblings('.employ_ele').hide();
         $(this).find('.employ_detail').show();
      });
      $('.module_title').click(function (){
         $('.employ_ele').show();
         $('.employ_detail').hide();
      });
   });
});