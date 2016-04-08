/**
 * Created by Administrator on 2016/4/7.
 */
define(['zepto', 'module/mall_nav', 'app/common', 'search', 'Front'], function (){
    $(function (){
        var url = window.location.pathname.split('/');
        var ajaxParams = {};
        var page = 0;
        var limit = 8;
        var node = url[2];
        ajaxList();
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
                        $out += '<div class="gonggao_ele clearfix">'
                        + '<div class="gonggao_left main_bg">'
                        + '<span class="main_border"><i class="main_bg"></i></span>'
                        + '</div><div class="gonggao_right">'
                        + '<a href="/article/' + item['id'] + '.html"><img src="' + item['image'] + '" alt=""><h4>' + item['title'] + '</h4><p>' + item['intro'] + '</p>'
                        + '<p><span>' + item['time'] + '</span></p></a></div></div>'
                    });
                    $('.gonggao_list').append($out);
                    sendAjax = $out ? true : false;
                }
            }
            , this);
        }
    });
});