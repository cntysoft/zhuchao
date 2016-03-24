/**
 * Created by wangzan on 2016/3/9.
 */
define(['jquery', 'lazyload','app/common'], function (){
   $(document).ready(function (){
      var search = decodeURI(window.location.search);
      if(search){
         var params = search.substring(1);
         var cond = params.split('&');
         addRoute(cond);
         addSort(cond);
      }
      //增加排序选中-------------------------------------
      function addSort(params){
         $.each(params, function (index, item){
            var arr = item.split('=');
            var id = arr[0];
            var data = arr[1];
            if(id == 'sort'){
               var item = data.split('_');
               $('#' + item[0]).attr('sort', item[1]);
               $('#' + item[0]).addClass('main');
               $('#' + item[0]).find('a,i').addClass('main');
               if(item[1] == 1){
               } else{
                  $('#' + item[0]).find('i').removeClass('icon-rank-down').addClass('icon-rank-up');
               }
            }
            if(id == 'enableprice'){
               $('.rank_wrap span.price i').addClass('icon-checked')
            }
         });
      }
      //增加route选中-------------------------------------
      function addRoute(params){
         $.each(params, function (index, item){
            var arr = item.split('=');
            var id = arr[0];
            var data = arr[1];
            if('keyword' != id && 'sort' != id){
               $('.list_item.choosed dd').append('<span fh-name="' + id + '" fh-data="' + data + '">' + data + '<i class="icon-smallcuo"></i></span>');
            }
         });
      }
      //筛选条件点击------------------------------------------
      $('.list_item dd a').click(function (){
         addRoute([$(this).attr('id') + '=' + $(this).attr('data')]);
         redirectUrl();
      });
      //重定向网址---------------------------------------
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
         $('.list_item.choosed dd span').each(function (){
            var item = $(this);
            cond[item.attr('fh-name')] = item.attr('fh-data');
         });
         $('.rank_wrap ul li').each(function (){
            if($(this).hasClass('main')){
               cond['sort'] = $(this).attr('id') + '_' + $(this).attr('sort');
            }
         });
         if($('.rank_wrap span.price i').hasClass('icon-checked')){
            cond['enableprice'] = 1;
         }
         $.each(cond, function (index, item){
            query += index + '=' + item + '&';
         });
         query = encodeURI(query.substring(0, query.length - 1));
         if(!query){
            window.location.href = location.protocol + '//' + location.hostname + '/query/1.html';
         } else{
            window.location.href = location.protocol + '//' + location.hostname + '/query/1.html' + query;
         }
      }
      $('.i_down').click(function (){
         if($(this).hasClass('icon-jiantou3')){
            $(this).parent('.more').prev().css('maxHeight', 'none');
            $(this).removeClass('icon-jiantou3').addClass('icon-jiantou1 main');
            $(this).prev().text('收起').addClass('main');
         } else{
            $(this).parent('.more').prev().css('maxHeight', 40 + 'px');
            $(this).removeClass('icon-jiantou1 main ').addClass('icon-jiantou3');
            $(this).prev().text('更多').removeClass('main');
         }
      });
      //更多选项
      $('.show_more').click(function (){
         if(!$(this).hasClass('active')){
            $(this).addClass('active');
            $(this).children('span').addClass('main').text('收起');
            $(this).children('i').removeClass('icon-jiantou3').addClass('main icon-jiantou1');
            $('.choose_list .list_item:gt(2)').show();
            $('.choose_list .list_item:nth-child(3)').removeClass('no_bb');
         } else{
            $(this).removeClass('active');
            $(this).children('span').removeClass('main').text('更多选项');
            $(this).children('i').removeClass('main icon-jiantou1').addClass('icon-jiantou3');
            $('.choose_list .list_item:gt(2)').hide();
            $('.choose_list .list_item:nth-child(3)').addClass('no_bb');
         }
      });
      //排序
      $('.rank_wrap ul li').click(function (){
         var $i = $(this).find('i');
         var item = $(this);
         var sort = item.attr('class');
         $(this).find('a,i').addClass('main').parent().siblings().find('a,i').removeClass('main');
         if(!sort){
            item.siblings().removeAttr('class');
            item.siblings().removeAttr('sort');
            item.addClass('main');
            item.attr('sort', '1');
         } else{
            if(item.attr('sort') == '1'){
               item.attr('sort', '2');
               $i.removeClass('icon-rank-down').addClass('icon-rank-up');
            } else if(item.attr('sort') == '2'){
               item.attr('sort', '1');
               $i.removeClass('icon-rank-up').addClass('icon-rank-down');
            }
         }
         redirectUrl();
      });
      $('.rank_wrap .check').click(function (){
         var on = $(this).find('i').hasClass('icon-checked');
         if(on){
            $(this).find('i').removeClass('icon-checked');
         } else{
            $(this).find('i').addClass('icon-checked');
         }
         redirectUrl();
      });

      $('.list_item.choosed').delegate('dd span', 'click', function (){
         $(this).remove();
         redirectUrl();
      });
   });
});