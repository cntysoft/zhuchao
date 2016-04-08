/**
 * Created by Administrator on 2016/3/21.
 */
define(['zepto', 'module/mall_nav', 'Core', 'Front', 'app/common', ], function (){
   $(function (){
      var sendAjax = true, params = {}, page = 1, limit = 5;
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
         params.page = page;
         params.limit = limit;
         Cntysoft.Front.callApi('User', 'getXunJiaList',
         params, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<div class="c_baojia"><p class="baojia_time clearfix"><span class="fl">' + item.time + '</span><span class="fr">' + item.status + '</span></p><div class="baojia_content"><p class="baojia_count">' + item.number + '条报价</p>';
               out += '<p class="baojia_pro">采购产品：<i>' + item.productName + '</i></p><p>报价来源：<i>产品咨询</i></p></div><a href="' + item.url + '" class="to_detail main_bg_light">查看报价单</a></div>';
            });
            $('.l_baojia').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});