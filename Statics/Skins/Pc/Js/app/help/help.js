/**
 * Created by jiayin on 2016/3/11.
 */
define(['jquery', 'Front','app/common','search'], function (){
    $(function (){
        var url = window.location.pathname.split('/');
        if(url[1] == 'article'){
            var itemId = url[2].split('.')[0];
             Cntysoft.Front.callApi('Utils', 'addArticleHits', {
                id : itemId
            }, function (response){
                
            }, this);
        }
        $('.left_menu .menu_item').click(function (){
            var that = $(this);
            if(that.hasClass('current')){
                $(this).removeClass('current').siblings().removeClass('current');
                $(this).find('.sub_menu').slideUp(300);
            } else{
                $('.left_menu .menu_item').removeClass('current');
                $(this).addClass('current');
                $('.sub_menu').slideUp(300);
                $(this).find('.sub_menu').slideDown(300);
            }
        });

        $('.left_menu a.menu_head').click(function (){
            $('.left_menu a.menu_head').each(function (){
                $(this).removeClass('main');
            });
            $(this).addClass('main');
        });
        var rootnode = $('.left_menu').attr('rootnode');
        var identifier = $('.left_menu').attr('identifier');
        if(rootnode >= 0){
            var $this = $('.left_menu li.menu_item').eq(rootnode);
            $this.find('.menu_head').addClass('main');
            $this.addClass('current').find('ul.sub_menu').show();
            $this.find('a.sub_link').each(function (){
                if($(this).attr('href').indexOf(identifier) > 0){
                    $(this).addClass('main');
                }
            });
        }
         //文章图片预览
        initContentPhotos('.help_content,.right_inner');

        function initContentPhotos(select){

            var imgs = $(select).find('img');
            var ret = {
                title : '图片预览',
                data : []
            }
            $.each(imgs, function (index, item){
                var info = {pid : index};
                if($(item).attr('data-original')){
                    info.src = $(item).attr('data-original');
                } else{
                    info.src = $(item).attr('src');
                }
                ret.data.push(info);
            });
            $.each(imgs, function (index, item){
                ret.start = index;
                $(item).click(function (){
                    layer.photos({
                        photos : {
                            title : '图片预览',
                            data : [],
                            start:index,
                            data:ret.data
                        },
                        shift : 0
                    });
                });
            });
            return ret;
        }
    });
});
