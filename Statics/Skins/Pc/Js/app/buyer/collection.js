define(['jquery', 'layer', 'Core', 'Front', 'search','app/common'], function(){
   $(function (){
      $('.collect_operation .del_btn').click(function(){
         var collect = $(this).parents('.collect_ele');
         var id = collect.attr('fh-id');
         layer.confirm('您确定要删除这条收藏记录吗？', function(){
            Cntysoft.Front.callApi('User', 'deleteCollects', {
               ids : id
            }, function(response){
               if(!response.status){
                  layer.alert('删除失败，请稍后再试！');
               }else{
                  layer.alert('删除成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.href = '/collection/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      });
      $('.phone_btn').click(function(){
         var collect = $(this).parents('.collect_ele');
         var number = collect.attr('fh-number');
         
         Cntysoft.Front.callApi('User', 'getLinker', {
            number : number
         }, function(response){
            if(response.status){
               var data = response.data;
               $('.linker_name').text(data.name);
               $('.linker_phone').text(data.phone);
               $('#linker').show();
            }
         });
      });
      $('.xunjia_button').click(function (){
            var content = $('#xunjia_content').val();
            var number = $('#xunjia').attr('fh-number');
            var params = {
                number : number,
                content : content
            };
            if(content.length < 10){
                return false;
            }
            Cntysoft.Front.callApi('User', 'addXunjiadan', params, function (response){
                if(response.status){
                   layer.alert('询价提交成功，请等待商家回复！', function(index){
                           layer.close(index);
                           $('#xunjia_content').val('');
                           $('#xunjia').attr('fh-number', '');
                           $('#xunjia').hide();
                   });
                }
            }, true);
        });
      $('.xunjia_btn').click(function(){
         var collect = $(this).parents('.collect_ele');
         var name = collect.find('.collect_container h4 a').text();
         $('.online_detail.product_name').text(name);
         $('#xunjia').attr('fh-number', collect.attr('fh-number'));
         $('#xunjia').show();
      });
      $('.online_close').click(function(){
         $('.online').hide();
      });
   });
});