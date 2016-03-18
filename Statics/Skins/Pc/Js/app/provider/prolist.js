define(['jquery'], function() {
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
      
	});
});