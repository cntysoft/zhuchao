define(['zepto'], function(){
   $(function (){
      $('.icon-sousuo').click(function(){
         var key = $('.search_input').val();
         if(key){
            window.location.href = '/query/1.html?keyword=' + key;
         }
      });
   });
});