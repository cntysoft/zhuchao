/**
 * Created by jiayin on 2016/3/19.
 */
define(['zepto', 'app/common', 'Front'], function (){
   $(function (){
        var url = window.location.pathname.split('/');
        var ajaxParams = {};
        var page = 1;
        var limit = 8;
        var node = url[2];
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
            ajaxParams.node = node;
            Cntysoft.Front.callApi('Utils', 'getInfoListByNodeAndStatus',
            ajaxParams, function (response){
                if(response.status){
                    var $out = '';
                    $.each(response.data, function (index, item){
                        $out += '<li><a href="/article/' + item['id'] + '.html"><div class="new_img"><div class="new_table">'
                        + '<img src="' + item['image'] + '" alt=""></div></div>'
                        + '<p class="new_text">' + item['title'] + '</p><p class="writer_info clearfix">'
                        + '<span class="writer fl">' + item['author'] + '</span><span class="fl">' + item['time'] + '</span></p>'
                        + '<span class="look">浏览（' + item['hits'] + '）</span></a></li>';
                    });
                    $('.new_box').append($out);
                    sendAjax = $out ? true : false;
                }
            }
            , this);
        }
   });
});