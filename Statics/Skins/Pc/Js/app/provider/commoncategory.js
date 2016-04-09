/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery', 'layer', 'Core', 'Front','app/common'], function (){
   $(function (){
      var $search = $('.searched'), $select = $('.select_wrap');
      $('.category_select').click(function(){
         if($select.is(':hidden')){
            $search.hide();
            $select.show();
         }
      });
      
      $('.category_search, .icon-sousuo').click(function(){
         var key = $('.search_key').val();
         if(key){
            if($search.is(':hidden')){
               $search.show();
               $select.hide();
            }
            Cntysoft.Front.callApi('Product', 'searchCategory', {
               key : key
            }, function (response){
               if(response.status){
                  var data = response.data, len = data.length, ul = $search.find('ul');
                  var out='';
                  ul.empty();
                  if(len > 0){
                     for(var i = 0; i < len; i++){
                        var len1 = data[i].length, list = [];
                        var cate = data[i], categoryId = 0;
                        for(var j = 0; j < len1; j++){
                           list.push('<em index='+cate[j]['id']+'>'+cate[j]['text']+'</em>');
                           categoryId = cate[j]['id'];
                        }
                        out+='<li index='+categoryId+'><label><input type="radio"  name="searched" /><span>';
                        out +=list.join('<b>&gt;</b>');
                        out +='</span></label></li>';
                     }
                     ul.append($(out));
                  }else{
                     ul.append($('<span>抱歉，没有找到您想要的类目，请去手动选择</span>'));
                  }
               }
            });
         }
      });
      
      $search.delegate('li label', 'click', function(){
         $('.selected_tips p').html('选择的类目是：'+$(this).find('span').html());
      });
      
      //分类选择
      $('.select_wrap').delegate('.select_list li', 'click', function(){
         var $this = $(this);
         if(!$this.hasClass('current')){
            $this.siblings('li').removeClass('current');
            $this.addClass('current');
            var $currentSelect = $this.parents('.select_list');
            $currentSelect.nextAll('.select_list').remove();
            
            if(0 == $this.attr('leaf')){
               Cntysoft.Front.callApi('Product', 'getChildCategory', {
                  categoryId : $this.attr('index')
               }, function (response){
                  if(response.status){
                     var data = response.data, len = data.length;
                     if(len > 0){
                        var out = '<div class="select_list"><span>产品分类</span><ul>';
                        for(var i = 0; i<len; i++){
                           out+='<li index="'+data[i]["id"]+'" leaf='+data[i]["leaf"]+'>'+data[i]["text"]+'</li>';
                        }
                        out+='</ul></div>';
                        $currentSelect.after($(out));
                     }else{
                        $('.submit_next');
                     }
                  }
               });
            }

            var selected = [];
            $('.select_list li.current').each(function(index, dom){
               selected.push('<em>'+$(dom).text()+'</em>');
            });
            
            var text = '选择的类目是：' + selected.join('<b>&gt;</b>');
            $('.selected_tips p').html(text);
         }
      });
      //填写详细信息
      $('.submit').click(function(){
         if(!$select.is(':hidden')){
            var flag = true, categoryId = 0;
            $('.select_list').each(function(index){
               var current = $(this).find('li.current');
               if(0 == current.length){
                  flag = false;
               }else{
                  categoryId = current.attr('index');
               }
            });
            if(flag){
               addCommonCategory(categoryId);
            }
         }else if(!$search.is(':hidden')){
            var checked = $search.find('li input:checked');
            if(checked.length > 0){
               var categoryId = checked.parents('li').attr('index');
               addCommonCategory(categoryId);
            }
         }
      }); 
      
      $('.icon-shanchu').click(function(){
         var li = $(this).parents('li');
         var categoryId = li.attr('index');
         layer.confirm('您确定要删除选择的常用分类？', {}, function(){
            Cntysoft.Front.callApi('Product', 'deleteCommonCategory', {categoryId : categoryId}, function(response){
               if(!response.status){
                  layer.msg('常用分类删除失败，请稍后再试', {
                     time : 1000
                  });
               }else{
                  layer.msg('常用分类删除成功！', {
                     time : 1000
                  }, function(){
                     window.location.reload();
                  })
               }
            });
         });
      });
      
      function addCommonCategory(categoryId)
      {
         var flag = true;
         $('.common_category li').each(function(index, dom){
            if(categoryId == $(dom).attr('index')){
               flag = false;
            }
         });
         if(flag){
            Cntysoft.Front.callApi('Product', 'addCommonCategory', {categoryId : categoryId}, function(response){
               if(!response.status){
                  layer.msg('常用分类添加失败，请稍后再试', {
                     time : 1000
                  });
               }else{
                  layer.msg('常用分类添加成功！', {
                     time : 1000
                  }, function(){
                     window.location.reload();
                  })
               }
            });
         }else{
            layer.msg('所选分类已经添加过了', {
               time : 1000
            });
         }
      }
   });
});
