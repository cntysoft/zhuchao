/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'module/totop', 'Core', 'Front'], function (){
   $(function (){
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
         Cntysoft.Front.callApi('Utils', 'getNewsList',
         {
            page : page,
            limit : limit,
            nodeIdentifier : $('.news_list').attr('ident')
         }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<div class="news_ele"> <a href="' + item.infourl + '"><img src="' + item.imgurl + '" alt=""></a><h4><a href="' + item.infourl + '">' + item.title + '</a></h4>';
               out += '<p><span class="fl">' + item.time + '</span><span class="fr">浏览（' + item.hits + '）</span></p></div>';
            });
            $('.news_list').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});