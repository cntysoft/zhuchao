/**
 * Created by Administrator on 2016/3/16.
 */
define(['zepto', 'module/company_classify', 'app/common', 'Core', 'Front'], function (){
   $(function (){
      var query = window.location.search;
      if(query.length){
         query += '&';
      } else{
         query += '?';
      }
      query = query.split('sort');
      $('.sort_new ').click(function (){
         if($(this).hasClass('main')){
            return;
         }
         if($(this).attr('sort')){
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('sort');
         }
      });
      $('.sort_price ').click(function (){
         if($(this).hasClass('up')){
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('downsort');
         } else{
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('upsort');
         }
      });
      var sendAjax = true; //是否可发送列表请求
      var page = 0, limit = 6;
      //底部刷新
      $(window).scroll(function (){
         var scrollTop = $(window).scrollTop();
         var windowHeight = $(window).height();
         var documentHeight = $(document).height();
         if(scrollTop + windowHeight > documentHeight - 20){
            sendAjax ? ajaxList() : function (){
            };
         }
      });
//下拉刷新请求
      function ajaxList(){
         sendAjax = false;
         page += 1;
         Cntysoft.Front.callApi('Utils', 'getProductList',
         {
            page : page,
            limit : limit,
            sort : $('.l_main div.sort').attr('sort'),
            keyword : $('.l_main div.sort').attr('keyword')
         }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<div class="pro_ele"><a  href="' + item.infourl + '"><img src="' + item.imgurl + '" alt=""></a><h3 class="pro_ele_title"><a  href="';
               out += item.infourl + '">' + item.title + '  </a></h3><p class="pro_ele_price">' + item.price + '</p></div>';
            });
            $('.pro_list').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});