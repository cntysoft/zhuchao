define(['jquery', 'layer', 'Core', 'Front', 'search', 'comment'], function(){
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
   });
});