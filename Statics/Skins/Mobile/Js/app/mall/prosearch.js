/**
 * Created by jiayin on 2016/1/6.
 */
define(['zepto', 'module/totop', 'search'], function (){
   //导航
   $('.header_right').not('.header_right_icon_search').tap(function (){
      var that = $('.top_nav_box');
      if(that.hasClass('in')){
         $(that).removeClass('in');
         return false;
      } else{
         $(that).addClass('in');
         return false;
      }
   });
   //头部导航样式
   $(function (){
      var search = decodeURI(window.location.search);
      if(search){
         var params = search.substring(1);
         var cond = params.split('&');
         $.each(cond, function (index, item){
            var arr = item.split('=');
            var id = arr[0];
            var data = arr[1];
            if(('keyword' == id)){
               if(data){
                  $('.text_ellipsis').text(data);
               } else{
                  $('.text_ellipsis').text('全部');
               }
            }
         });
         addSort(cond);
         addQuery(cond);
      }
      function redirectUrl(){
         var cond = {};
         var query = '?';
         if(search){
            var params = search.substring(1);
            var condition = params.split('&');
            $.each(condition, function (index, item){
               var arr = item.split('=');
               var id = arr[0];
               var data = arr[1];
               if(data && ('keyword' == id)){
                  cond[id] = data;
               }
            });
         }
         $('.shaixuan_item li.current').each(function (){
            var item = $(this);
            cond[item.attr('id')] = item.attr('data');
         });
         $('#mallSearchNav li').each(function (){
            if($(this).hasClass('current')){
               cond['sort'] = $('#mallSearchNav li.current').attr('id') + '_' + $('#mallSearchNav li.current').attr('sort');
            }
         });
         $.each(cond, function (index, item){
            query += index + '=' + item + '&';
         });
         query = encodeURI(query.substring(0, query.length - 1));
         if(!query){
            window.location.href = location.protocol + '//' + location.hostname + location.pathname;
         } else{
            window.location.href = location.protocol + '//' + location.hostname + location.pathname + query;
         }
      }
      $('#mallSearchNav li').tap(function (){
         var $this = $(this);
         if($this.attr('id') == '1' || $this.attr('id') == '2'){
            if(!$this.hasClass('current')){
               $this.siblings('.current').removeClass('current');
               $this.addClass('current');
               $this.attr('sort', 1);
            }
            redirectUrl();
         }
         if($this.attr('id') == '3'){
            $this.siblings('.current').removeClass('current');
            $this.addClass('current');
            if($('.sanjiao').hasClass('current')){
               $this.find('.sanjiao').toggleClass('current');
            }
            if($('.up_sanjiao').hasClass('current')){
               $this.attr('sort', 1);
            }
            if($('.down_sanjiao').hasClass('current')){
               $this.attr('sort', 2);
            }
            if($this.hasClass('up')){
               $this.removeClass('up').addClass('down');
            } else{
               $this.removeClass('down').addClass('up');
            }
            redirectUrl();
         }
      });
      function addQuery(params){
         $.each(params, function (index, item){
            var arr = item.split('=');
            var id = arr[0];
            var data = arr[1];
            $.each($('#' + id), function (){
               var span = $(this);
               if(span.attr('data') == data){
                  span.addClass('current');
               }
            });
         });
      }
      function addSort(params){
         $.each(params, function (index, item){
            var arr = item.split('=');
            var id = arr[0];
            var data = arr[1];
            if(id == 'sort'){
               var item = data.split('_');
               if(item[0] == '3'){
                  if(item[1] == '1'){
                     $('.search_price').removeClass('down').addClass('up');
                     $('.up_sanjiao').addClass('current');
                     $('.down_sanjiao').removeClass('current');
                  }
                  if(item[1] == '2'){
                     $('.search_price').removeClass('up').addClass('down');
                     $('.down_sanjiao').addClass('current');
                     $('.up_sanjiao').removeClass('current');
                  }
               } else{
                  $('#' + item[0]).attr('sort', item[1]);
                  $('#' + item[0]).addClass('current');
               }
            }
         });
      }
      //点击筛选
      $('#shaixuan').tap(function (){
         $('#shaixuanBox').show();
         $('html,body').css({'height' : '100%', 'overflow' : 'hidden'});
      });
      //点击取消
      $('.cancel').tap(function (){
         $('#shaixuanListNav').find('.choose_text').text('不限');
         $('.shaixuan_item').find('li.current').removeClass('current');
         $('#shaixuanBox').hide();
         $('html,body').css({'height' : 'auto', 'overflow' : 'visible'});
      });
      //点击保存
      $('.save').tap(function (){
         $('#shaixuanBox').hide();
         $('html,body').css({'height' : 'auto', 'overflow' : 'visible'});
         redirectUrl();
      });
      //点击条件
      $('.shaixuan_item .module_text_nav li').tap(function (){
         var index = $(this).closest('.shaixuan_item').index();
         $(this).addClass('current').siblings().removeClass('current');
         var textValue = $(this).children('a').text();
         $('#shaixuanListBox').hide();
         $('#shaixuanIndex').show();
         $('#shaixuanListNav li').eq(index).find('span').text(textValue);
      });
      //点击分类
      $('#shaixuanListNav li').tap(function (){
         var indexA = $(this).index();
         $('#shaixuanIndex').hide();
         $('#shaixuanListBox').show();
         $('#shaixuanListBox').find('.shaixuan_item').hide().eq(indexA).show();
      });

      //清除选择
      $('.submit_btn').tap(function (){
         $('#shaixuanListNav').find('.choose_text').text('不限');
         $('.shaixuan_item').find('li.current').removeClass('current');
      });
      //返回按钮
      $('#shaixuanListBox .goback').tap(function (){
         $('#shaixuanListBox').hide();
         $('#shaixuanIndex').show();
      });
   });

});