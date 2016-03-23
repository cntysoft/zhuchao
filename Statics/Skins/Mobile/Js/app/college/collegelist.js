/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 */
define(['zepto', 'module/totop', 'Core', 'Front'], function (){
   $(function (){
      //导航
      $('.more_icon').click(function (){
         if($('.top_nav_box').hasClass('hide')){
            $(this).parents('.header_right').next('.top_nav_box').removeClass('hide');
            return false;
         }
         else{
            $(".top_nav_box").addClass('hide');
            return false;
         }
      });
      $('body').click(function (){
         $(".top_nav_box").addClass('hide');
      });
      var sendAjax = true; //是否可发送列表请求
      var page = 0, limit = 4;
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
         Cntysoft.Front.callApi('Utils', 'getCollegeArticleList',
         {
            page : page,
            limit : limit,
            nodeIdentifier : $('.choose_box').attr('ident')
         }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
               out += '<div class="class_item"><h3 class="class_title text_ellipsis main_hover"><a href="' + item.infourl + '">' + item.title + '</a></h3><div class="writer_info clearfix">';
               out += '<div class="fl"><span>发表时间：' + item.time + '</span><span class="writer">作者：' + item.author + '</span> </div><span class="look fr"><i class="icon-yan"></i>' + item.hits + '</span>';
               out += '</div><div class="class_img">' + item.imgurl + '</div><p class="class_text">' + item.intro + '</p><div class="user_action tr clearfix">';
               out += ' <a class="study_btn fl main_bg" href="' + item.infourl + '">立即学习</a><span>' + item.hits + '人已学</span></div></div>';
            });
            $('body').append(out);
            sendAjax = out ? true : false;
         }
         , this);
      }
   });
});

