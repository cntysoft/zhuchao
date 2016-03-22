/**
 * Created by jiayin on 2016/3/19.
 */
define(['zepto','Front'], function (){
    $(function (){
        //增加文章阅读量
        var url = window.location.pathname.split('/');
        if(url[1] == 'article'){
            var itemId = url[2].split('.')[0];
            Cntysoft.Front.callApi('Utils', 'addArticleHits', {
                id : itemId
            }, function (response){

            }, this);
        }
        $('.menu_item').click(function (){
            var that = $(this);
            if(that.hasClass('current')){
                $(this).removeClass('current').siblings().removeClass('current');
            } else{
                $(this).addClass('current').siblings().removeClass('current');
            }
        });
    });
});