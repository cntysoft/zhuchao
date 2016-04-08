/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'module/mall_nav', 'module/totop', 'Core', 'Front'], function (){
    $(function (){
        var sendAjax = true; //是否可发送列表请求
        var page = 0, limit = 8;
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
                    out = '<li><a href="' + item.infourl + '"><img src="' + item.imgurl + '" alt="">'
                    + '<h3 class="new_title">' + item.title + '</h3>'
                    + '<p class="writer_info clearfix"><span class="fl">' + item.time + '</span></p>'
                    + '<span class="look">浏览（' + item.hits + '）</span></a></li>';
                });
                $('.news_list').append(out);
                sendAjax = out ? true : false;
            }
            , this);
        }
    });
});