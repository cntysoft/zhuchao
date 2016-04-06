/**
 * Created by wangzan on 2016/3/14.
 */
define(['jquery', 'slick', 'app/company/common'], function (){
   $(function (){
      $('.classify_list h2').click(function (){
         if($(this).hasClass('active')){
            $(this).find('i').addClass('icon-black-right').removeClass('icon-black-down');
            $(this).next().hide();
            $(this).removeClass('active');
         } else{
            $(this).find('i').removeClass('icon-black-right').addClass('icon-black-down');
            $(this).next().show();
            $(this).addClass('active');
         }
      });
      $('.show_small').slick({
         speed : 500,
         slidesToShow : 5,
         slidesToScroll : 1,
         arrows : true,
         dots : false,
         draggable : false,
         infinite : true,
         prevArrow : '.prev_btn',
         nextArrow : '.next_btn'
      });

      $('.show_small img').hover(function (){
         var imgSrc = $(this).attr('src');
         $('.show_big img').attr({
            "src" : imgSrc
         });
         $(this).parent('div').addClass('main_border').siblings().removeClass('main_border');
      });
      $('.describe_title a').click(function (){
         $(this).addClass('main_border').siblings().removeClass('main_border');
         $('.describle_info').children().eq($(this).index()).show().siblings().hide();
         return false;
      });
      var origin = window.location.href;
      $('.pro_info div.icon-erweima').mouseenter(function (){
         if(!$('#qrcode2').hasClass('loaded')){
            $('#qrcode2').qrcode({
               render : "canvas",
               height : 130,
               width : 130,
               text : origin
            });
            $('#qrcode2').addClass('loaded');
         }
      });
      var small_img = $('.show_small').find('div.mainbd_hover');
      $('.next_btn').click(function (){
         var index = $('.show_small').find('.main_border').index();
         small_img.eq(index).removeClass('main_border');
         if(index == small_img.size() - 1){
            index = -1;
         }
         var big_src = small_img.eq(index + 1).find('img').attr('src');
         $('.show_big').find('img').attr('src', big_src);
         small_img.eq(index + 1).addClass('main_border');
      });
      $('.prev_btn').click(function (){
         var index = $('.show_small').find('.main_border').index();
         small_img.eq(index).removeClass('main_border');
         if(index == 0){
            index = small_img.size();
         }
         var big_src = small_img.eq(index - 1).find('img').attr('src');
         $('.show_big').find('img').attr('src', big_src);
         small_img.eq(index - 1).addClass('main_border');
      });
      if($('.pro_info').attr('num')){
         Cntysoft.Front.callApi('Utils', 'addProductHits',
         {
            number : $('.pro_info').attr('num')
         }, function (response){
         }
         , this);
      }
   });
});