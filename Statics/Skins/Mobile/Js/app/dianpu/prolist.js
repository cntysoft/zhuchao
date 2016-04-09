/**
 * Created by jiayin on 2016/4/7.
 */
define(['zepto', 'swiper', 'module/mall_nav', 'app/dianpu/common','Front'], function (){

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
    function ajaxList(){
        sendAjax = false;
        page += 1;
        Cntysoft.Front.callApi('Utils', 'getProductList',
        {
            page : page,
            limit : limit
        }, function (response){
            var out = '';
            $.each(response.data, function (index, item){
                out += '<div class="pro_list"><a href="' + item.infourl + '" class="pro_list_a">'
                + '<img src="' + item.imgurl + '" alt=""/>'
                + '<h3>' + item.title + '</h3><p>产品编号：' + item.number + '</p>'
                + '<div class="price_btn main">' + item.price + '</div></a></div>';
            });
            $('.pro_list_box').append(out);
            sendAjax = out ? true : false;
        }
        , this);
    }
});