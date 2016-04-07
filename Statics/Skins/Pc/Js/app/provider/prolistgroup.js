define(['jquery', 'Core', 'Front', 'layer', 'app/common', 'layer.ext'], function (){
   $(document).ready(function (){
      var location = window.location, type = 1;
      init();
      $('.reply li').click(function(){
         var $this = $(this);
         var ctype = $(this).attr('fh-type');
         if(3 == ctype){
            layer.prompt({
               title : '请输入分组名'
            }, function(value, index){
               if(value){
                  if(value.length > 10){
                     layer.tips('请输入10位以内的名称!', '.layui-layer-prompt .layui-layer-content', {
                        tips : [2, '#63bf82'],
                        time : 1000
                     });
                     return false;
                  }
                  Cntysoft.Front.callApi('Product', 'addGroup', {
                     id : 0,
                     name : value
                  }, function(response){
                     if(!response.status){
                        if(10007 == response.errorCode){
                           layer.msg('最多添加10个分组！', {
                              time : 1000
                           });
                        }else{
                           layer.msg('添加分组名称失败！', {
                              time : 1000
                           });
                        }
                     }else{
                        layer.msg('添加分组名称成功！', {
                           time : 1000
                        }, function(){
                           window.location.reload();
                        });
                     }
                  });
               }
            });
         }else{
            if(!$this.find('a').hasClass('main_bg')){
               $this.siblings('li').removeClass('main_bg');
               $this.addClass('main_bg');

               location.href = location.protocol + '//' + location.host + '/group/1.html?type='+ctype;
            }
         }
      });
      
      $('.group_list').delegate('.group_parent .li_name', 'click', function(){
         var child = $(this).parents('.group_parent').nextAll('.group_child');
         if(child.is(':hidden')){
            child.show();
         }else{
            child.hide();
         }
      });
      
      $('.group_list').delegate('.change_name', 'click', function(){
         var $groupName = $(this).parents('.group_parent, .group_child').find('.group_name');
         layer.prompt({
            title : '请输入分组名',
            value : $groupName.text()
         }, function(value, index){
            if(value){
               Cntysoft.Front.callApi('Product', 'modifyGroup', {
                  id : $groupName.attr('fh-id'),
                  name : value
               }, function(response){
                  if(!response.status){
                     layer.msg('修改分组名称失败！', {
                        time : 1000
                     });
                  }else{
                     layer.msg('修改分组名称成功！', {
                        time : 1000
                     }, function(){
                        window.location.reload();
                     });
                  }
               });
            }
         });
      });
      $('.group_list').delegate('.delete_group', 'click', function(){
         var $groupName = $(this).parents('.group_parent, .group_child').find('.group_name');
         layer.confirm('您确定要删除分组？', function(value, index){
            Cntysoft.Front.callApi('Product', 'deleteGroup', {
               id : $groupName.attr('fh-id')
            }, function(response){
               if(!response.status){
                  layer.msg('删除分组失败！', {
                     time : 1000
                  });
               }else{
                  layer.msg('删除分组成功！', {
                     time : 1000
                  }, function(){
                     window.location.reload();
                  });
               }
            });
         });
      });
      $('.group_list').delegate('.add_btn', 'click', function(){
         var $groupName = $(this).parents('.group_parent').find('.group_name');
         layer.prompt({
            title : '请输入分组名'
         }, function(value, index){
            if(value){
               Cntysoft.Front.callApi('Product', 'addGroup', {
                  pid : $groupName.attr('fh-id'),
                  name : value
               }, function(response){
                  if(!response.status){
                     if(10007 == response.errorCode){
                        layer.msg('子分组已达到最大值！', {
                           time : 1000
                        });
                     }else{
                        layer.msg('添加子分组失败！', {
                           time : 1000
                        });
                     }
                  }else{
                     layer.msg('添加子分组成功！', {
                        time : 1000
                     }, function(){
                        window.location.reload();
                     });
                  }
               });
            }
         });
      });
      
      $('.search_select select').change(function(){
         var val = $(this).val();
         location.href = location.protocol + '//' + location.host + '/group/1.html?type='+type +'&group='+val;
      });
      
      $('.list_action select').change(function(){
         var $product = $(this).parents('.list_items');
         var number = $product.attr('fh-num');
         var groupId = $(this).val();
         
         Cntysoft.Front.callApi('Product', 'changeGroupProduct', {
            groupId : groupId,
            numbers : number
         }, function(response){
            if(!response.status){
               layer.msg('修改分组失败！', {
                  time : 1000
               });
            }else{
               layer.msg('修改分组成功！', {
                  time : 1000
               }, function(){
                  location.href = location.protocol + '//' + location.host + '/group/1.html?type='+type;
               });
            }
         });
      });
      
      function init()
      {
         var search = location.search;
         var ret = search.split('type=');
         if(2 == parseInt(ret[1])){
            type = 2;
            $('.reply li').eq(2).hide();
         }else{
            $('.reply li').eq(2).show();
         }
         $('.reply li').each(function(index, dom){
            if($(dom).attr('fh-type') == type){
               $(dom).find('a').addClass('main_bg');
            }
         });
         $('.group_list').each(function(index, dom){
            $(dom).hide();
            if(index + 1 == type){
               $(dom).show();
               if(type == 2){
                  $('.page_list').show();
               }else{
                  $('.page_list').hide();
               }
            }
         });
      }
   });
});
