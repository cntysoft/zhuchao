define(['jquery', 'module/share', 'Front', 'app/common','layer','layer.ext'], function (){
    $(function (){
        var url = window.location.pathname.split('/');
        if(url[1] == 'article'){
            var itemId = url[2].split('.')[0];
            Cntysoft.Front.callApi('Utils', 'addArticleHits', {
                id : itemId
            }, function (response){
            }, this);
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
                            start : index,
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