/**
 * Created by jiayin on 2016/3/18.
 */
define(['zepto', 'swiper', 'module/mall_nav', 'app/common', 'Core', 'Front'], function (){
   $(function (){
      //导航
      //广告
      var Ad = new Swiper('.module_ad3', {
         pagination : '.swiper-pagination',
         autoplay : 3000,
         speed : 300,
         loop : true,
         lazyLoading:true
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
         Cntysoft.Front.callApi('Utils', 'getArticleList',
         {
            page : page,
            limit : limit,
            nodeIdentifier : $('.new_box').attr('ident')
         }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<li  ' + item.imgcls + ' ><a href="' + item.infourl + '"> ' + item.imgurl + '<p class="new_text">' + item.title + '</p><p class="writer_info clearfix">';
//               out += '<span class="writer fl">' + item.author + '</span>';
               out += '<span class="fl">' + item.time + '</span></p><span class="look">浏览（' + item.hits + '）</span></a> </li>';
            });
            $('.new_box').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});