define(['jquery', 'Core', 'Front', 'layer'], function() {
	$(document).ready(function() {
		//全選
		$('.prolist_operate').find('.check').click(function() {
			var $this = $(this);
			if ($this.find('i').hasClass('icon-checked')) {
            $('.prolist_operate .btn').addClass('btn_del');
				$('.prolist_operate').find('.check i').removeClass('icon-checked');
				$('.prolist_list ').find('.check i').removeClass('icon-checked');
			} else {
            $('.prolist_operate .btn').removeClass('btn_del');
				$('.prolist_operate').find('.check i').addClass('icon-checked');
				$('.prolist_list ').find('.check i').addClass('icon-checked');
			}
		});
		//單選
		$('.prolist_list ').find('.check').click(function() {
			var $this = $(this);
			if ($this.find('i').hasClass('icon-checked')) {
				$this.find('i').removeClass('icon-checked');
			} else {
				$this.find('i').addClass('icon-checked');
			}
		});
		$('.prolist_list ').find('.check').click(function() {
			var prolistLenght = $('.prolist_list ').length;
			var icheckedLenght = $('.prolist_list ').has('.icon-checked').length;

			if (prolistLenght == icheckedLenght) {
				$('.prolist_operate').find('.check i').addClass('icon-checked');
			} else {
				$('.prolist_operate').find('.check i').removeClass('icon-checked');
			}
         if($('.prolist_list ').has('.icon-checked').length > 0){
            $('.prolist_operate .btn').removeClass('btn_del');
         }else{
            $('.prolist_operate .btn').addClass('btn_del');
         }
		});
      
      $('.search_key').blur(function(){
         if($(this).val()){
            redirectUrl();
         }else{
            var search = window.location.search;
            if(search.indexOf('keyword=')){
               redirectUrl();
            }
         }
      });
      
      $('.pro_status li').click(function(){
         if(!$(this).hasClass('current')){
            $(this).siblings('li').removeClass('current');
            $(this).addClass('current');
            redirectUrl();
         }
      });
      
      function redirectUrl()
      {
         var cond = {};
         if($('.search_key').val()){
            cond['keyword'] = $('.search_key').val();
         }
         
         var index = $('.pro_status li').index($('.pro_status li.current'));
         switch(index){
            case 0:
               cond['status'] = 3;
               break;
            case 1:
               cond['status'] = 2;
               break;
            case 2:
               cond['status'] = 5;
               break;
            case 3:
               cond['status'] = 4;
               break;
         }
         var query = '?';
         $.each(cond,function(key,item){
            query += key+'='+item+'&';
         });
         query = encodeURI(query.substring(0,query.length-1));

         if(!query){
            window.location.href = location.protocol + '//' + location.hostname + location.pathname;
         } else {
            window.location.href = location.protocol + '//' + location.hostname + location.pathname + query;
         }
      }
      
      $('.pro_operate .delete').click(function(){
         var $delete = $(this);
         var ids = $delete.parents('.prolist_list').attr('fh-index');
         deleteProducts(ids)
      });
      
      $('.prolist_operate .delete').click(function(){
         if($(this).hasClass('btn_del')){
            return;
         }
         var checkedList = $('.prolist_list ').has('.icon-checked');
         if(0 == checkedList.length){
            return;
         }
         var ids = [];
         checkedList.each(function(index, dom){
            ids.push($(dom).attr('fh-index'));
         });
         
         deleteProducts(ids);
      });
      
      $('.prolist_operate .shelf').click(function(){
         if($(this).hasClass('btn_del')){
            return;
         }
         var checkedList = $('.prolist_list ').has('.icon-checked');
         if(0 == checkedList.length){
            return;
         }
         var ids = [];
         checkedList.each(function(index, dom){
            ids.push($(dom).attr('fh-index'));
         });
         
         shelfProduct(ids);
      });
      
      function shelfProduct(ids)
      {
         layer.confirm('您确定要下架选中的产品吗?', function(index){
            layer.close(index);
            Cntysoft.Front.callApi('Product', 'shelfProduct', {
               ids : ids
            }, function(response){
               if(!response.status){
                  layer.alert('下架失败，请稍后再试！');
               }else{
                  layer.alert('下架成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.href = '/product/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      }
      
      function deleteProducts(ids)
      {
         layer.confirm('您确定要删除选中的产品吗?', function(index){
            layer.close(index);
            Cntysoft.Front.callApi('Product', 'deleteProduct', {
               ids : ids
            }, function(response){
               if(!response.status){
                  layer.alert('删除失败，请稍后再试！');
               }else{
                  layer.alert('删除成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.href = '/product/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      }
	});
});