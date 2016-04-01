/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'module/totop', 'lazyload'], function (){
   $(function (){
      $('.module_title').not('.about_us').click(function (){
         if($(this).hasClass('active')){
            $(this).next().hide();
            $(this).removeClass('active');
         } else{
            $(this).next().show();
            $.each($(this).next().find('img'), function (index, item){
               if($(this).attr('data-original')){
                  $(this).attr('src', $(this).attr('data-original'));
               }
            });
            $(this).addClass('active');
         }
      });
   });
});