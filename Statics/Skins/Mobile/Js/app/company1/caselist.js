/**
 * Created by wangzan on 2016/4/1.
 */
define(['zepto', 'module/company_classify', 'app/common', 'Front'], function (){
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
                        $out += '<div class="case_list"><a class="case_img" href="/casedetail/' + item['id'] + '.html"><img src="' + item['image'] + '"></a>'
                        + '<h2><a href="/casedetail/' + item['id'] + '.html">' + item['title'] + '</a></h2>'
                        + '<div class="case_intro"><p>' + item['intro'] + '</p></div></div>';
                    });
                    $('.caselist').append($out);
                    sendAjax = $out ? true : false;
                }
            }
            , this);
        }
    });
});