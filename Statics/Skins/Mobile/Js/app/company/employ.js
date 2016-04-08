/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'app/common', 'Core', 'Front'], function (){
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
         Cntysoft.Front.callApi('Utils', 'getJobList',
         {
            page : page,
            limit : limit,
            nodeIdentifier : $('.module_title').attr('ident')
         }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<div class="employ_ele"><h3><i></i>' + item.title + '<a href="' + item.infourl + '">详细>></a></h3><div class="employ_detail"><p>';
               out += '任职部门： ' + item.department + '</p><p>招聘人数：' + item.number + '</p> <p>发布时间：' + item.time + '</p></div></div>';
            });
            $('.l_main').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});