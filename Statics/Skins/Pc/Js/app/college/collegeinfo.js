/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 */
define(['jquery', 'module/share', 'app/common', 'layer', 'layer.ext'], function (){

    //手机二维码
    $(function (){
        $('head').append('<script type="text/javascript" charset="utf-8" async=""  src="/Statics/Skins/Pc/Js/lib/qrcode.js"></script>');
        var origin = window.location.href;
//      setTimeout(function (){
        $('#weixin_code').qrcode({
            width : 200,
            height : 200,
            text : origin
        });
//      }, 1);
        if($('.module_content').attr('article')){
            Cntysoft.Front.callApi('Utils', 'addArticleHits',
            {
                id : $('.module_content').attr('article')
            }, function (response){
            }
            , this);
        }
        //文章图片预览
        initContentPhotos('.module_content');

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


