define(['jquery'], function(){
   $(function (){
      $('.search_btn').click(function(){
         var key = $('.search_key').val();
         var baseUrl = $('.logo_img').attr('href');
         if(key){
            window.location.href = baseUrl + '/query/1.html?keyword=' + key;
         }
      });
   });
});