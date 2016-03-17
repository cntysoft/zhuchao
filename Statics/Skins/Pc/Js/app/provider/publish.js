/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery', 'layer', 'Core', 'Front'], function (){
   $(function (){
      var $search = $('.searched_wrap'), $select = $('.select_wrap');
      $('.category_select').click(function(){
         if(!$search.is(':hidden')){
            $search.hide();
            $select.show();
         }
      });
      
      $('.category_search').click(function(){
         if($('.search_key').val()){
            if($search.is(':hidden')){
               $search.show();
               $select.hide();
            }
            Cntysoft.Front.callApi('Product', 'getChildCategory', {
               categoryId : $this.attr('index')
            }, function (response){
               
            });
         }
      });
      //分类选择
      $('.select_wrap').delegate('.select_list li', 'click', function(){
         var $this = $(this);
         if(!$this.hasClass('current')){
            $this.siblings('li').removeClass('current');
            $this.addClass('current');
            var $currentSelect = $this.parents('.select_list');
            $currentSelect.nextAll('.select_list').remove();
            
            Cntysoft.Front.callApi('Product', 'getChildCategory', {
               categoryId : $this.attr('index')
            }, function (response){
               if(response.status){
                  var data = response.data, len = data.length;
                  if(len > 0){
                     var out = '<div class="select_list"><span>产品分类</span><ul>';
                     for(var i = 0; i<len; i++){
                        out+='<li index="'+data[i]["id"]+'">'+data[i]["text"]+'</li>';
                     }
                     out+='</ul></div>';
                     $currentSelect.after($(out));
                  }else{
                     $('.submit_next');
                  }
               }
            });
            
            var selected = [];
            $('.select_list li.current').each(function(index, dom){
               selected.push('<em>'+$(dom).text()+'</em>');
            });
            
            var text = '选择的类目是：' + selected.join('<b>&gt;</b>');
            $('.selected_tips p').html(text);
         }
      });
      //填写详细信息
      $('.submit_next').click(function(){
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
               window.location.href='/product/addproduct/2.html?category='+categoryId;
            }
         }else{
            
         }
      });
      
   });
});