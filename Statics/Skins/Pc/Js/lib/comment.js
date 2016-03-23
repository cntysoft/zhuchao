define(['jquery', 'Front'], function (){
   $(document).ready(function (){
      $('.logout').click(function (){
         Cntysoft.Front.callApi('User', 'logout', {
         }, function (response){
            if(response.status){
               window.location.reload();
            }
         }, true);
      });
      $('.search_button').click(function (){
         var key = $('.search_key').val();
         if(key){
            window.location.href = '/query/1.html?keyword=' + key;
         }
      });
   });
});


