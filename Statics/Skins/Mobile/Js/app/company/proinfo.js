/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'swiper', 'module/totop'], function (){
   $(function (){
      var banner = new Swiper('.pro_banner', {
         pagination : '.pro-pagination',
         autoplay : 3000,
         speed : 300,
         loop : true
      });
      $('.m_pro_info span').tap(function (){
         var $span = $(this);
         if($span.hasClass('main')){
            return false;
         }
         $span.addClass('main_border main').siblings('span').removeClass('main_border main');
         if($span.hasClass('intro')){
            $('.pro_content.attrs').hide();
            $('.pro_content.intro').show();
         } else{
            $('.pro_content.attrs').show();
            $('.pro_content.intro').hide();
         }
      });

   });
});