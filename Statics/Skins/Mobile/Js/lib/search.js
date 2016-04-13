define(['zepto'], function (){
   $(function (){
      $('#search_input').tap(function(){
            $('.search_box').show();
            $('html,body').css({
                height:'100%',
                overflowY:'hidden'
            });
        });
        $('.header_back_box ').tap(function(){
            $('.search_box').hide();
            $('html,body').css({
                height:'auto',
                overflowY:'auto'
            });
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