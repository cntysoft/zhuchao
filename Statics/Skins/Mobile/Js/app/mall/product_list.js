/**
 * Created by jiayin on 2016/3/16.
 */
define(['zepto', 'swiper', 'module/totop', 'Front','module/mall_nav'], function (){
   $(function (){
      var ajaxParams = {};
      var page = 1;
      var limit = 10;
      var cid = parseInt(window.location.pathname.split('/')[2]);
      var sendAjax = true;
      $(window).scroll(function (){
         var scrollTop = $(window).scrollTop();
         var windowHeight = $(window).height();
         var documentHeight = $(document).height();
         if(scrollTop + windowHeight > documentHeight - 20){
            sendAjax ? ajaxList() : function (){
            };
         }
      });
      function ajaxList(){
         sendAjax = false;
         page += 1;
         ajaxParams.page = page;
         ajaxParams.limit = limit;
         ajaxParams.cid = cid;
         Cntysoft.Front.callApi('Utils', 'getGoodsListBy',
         ajaxParams, function (response){
            if(response.status){
               var $out = '';
               $.each(response.data, function (index, item){
                  $out += '<li><a href="/item/' + item.number + '.html"><div class="pro_img"><div class="pro_table">'
                  + '<img src="' + item.image + '" alt=""/></div></div>'
                  + '<p class="pro_text">' + item.name + '</p>'
                  + '<p class="pro_price add_color">¥' + item.price + '</p></a></li>';
               });
               $('.card3').append($out);
               sendAjax = $out ? true : false;
            }
         }
         , this);
      }
   });
});
