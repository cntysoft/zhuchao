define(['jquery', 'layer', 'Core', 'Front'], function(){
   $(function (){
      $('.company_operation .icon-shanchu').click(function(){
         var collect = $(this).parents('.company_ele');
         var id = collect.attr('fh-index');
         layer.confirm('您确定要删除这条关注记录吗？', function(){
            Cntysoft.Front.callApi('User', 'deleteFollows', {
               ids : id
            }, function(response){
               if(!response.status){
                  layer.alert('删除失败，请稍后再试！');
               }else{
                  layer.alert('删除成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.href = '/follow/1.html';
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