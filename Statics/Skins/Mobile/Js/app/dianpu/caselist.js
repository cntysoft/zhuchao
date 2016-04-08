/**
 * Created by wangzan on 2016/4/1.
 */
define(['zepto', 'module/mall_nav', 'module/totop', 'Front'], function (){
    $(function (){
        var ajaxParams = {};
        var page = 1;
        var limit = 8;
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
            ajaxParams.total = false;
            ajaxParams.page = page;
            ajaxParams.limit = limit;
            Cntysoft.Front.callApi('Utils', 'getCaseList',
            ajaxParams, function (response){
                if(response.status){
                    var $out = '';
                    $.each(response.data, function (index, item){
                        $out += '<div class="case_list"><a href="/casedetail/' + item['id'] + '.html" class="case_list_a">'
                        + '<img src="' + item['image'] + '" alt=""/><h3>' + item['title'] + '</h3>'
                        + '<p class="case_text">' + item['intro'] + '</p></a></div>';
                    });
                    $('.caselist').append($out);
                    sendAjax = $out ? true : false;
                }
            }
            , this);
        }
    });
});