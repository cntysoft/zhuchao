define(['validate','jquery','Core','Front'],function(validate){
   $(function(){
       $('#submit').click(function(){
           var validation = validate.checkFields($('.checkfield'));
           if(validation.length){
               validation[0].ele.focus();
               return false;
           }
           var params = {
             lowPrice:$('#lowPrice').val(),
             highPrice:$('#highPrice').val(),
             content:$('#content').val()
           };
           console.log(params);
       });
   });
});