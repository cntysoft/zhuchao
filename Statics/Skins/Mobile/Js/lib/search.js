define(['zepto'], function (){
   $(function (){
      $('#search_input').tap(function (event){
         $('.search_box').show().css({
            position : 'absolute',
            zIndex : '1988'
         });
         $('body>div:not(.search_box)').hide();
         $('body>ul').hide();
      });
      $('.header_back_box').tap(function (event){
         $('body>div:not(.search_box)').show();
         $('body>ul').show();
         $('#totop').hide();
         $('.online').hide();
         $('#shaixuanBox').hide();
         $('.search_box').hide();

      });
      $('.header_right_icon_search').tap(function (){
         var keyword = $('.search_input_real').val();
         var location = window.location;
         if(keyword){
            window.location.href = location.protocol + '//' + location.hostname + '/query/1.html?keyword=' + keyword;
         }
      });
      $('.search_hot_key li').tap(function (){
         var keyword = $(this).text();
         var location = window.location;
         if(keyword){
            window.location.href = location.protocol + '//' + location.hostname + '/query/1.html?keyword=' + keyword;
         }
      });
   });
});