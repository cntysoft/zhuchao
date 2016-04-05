define(['app/common', 'validate', 'webuploader', 'Core', 'Front'], function (common){
    $(function (){
        init();
        $('.recruit_title .check').click(function (){
            $(this).find('i').toggleClass('icon-checked');
            var checkedLength = $('.recruit_title .check .icon-checked').length;
            if($('.recruit_title .check').length == checkedLength){
                $('.recruit_delete .check i').addClass('icon-checked');
            } else{
                $('.recruit_delete .check i').removeClass('icon-checked');
            }
            checkDelBtn();
        });

        $('.delete').click(function (){
            $this = $(this);
            layer.confirm('删除之后将无法恢复,确定删除?', {
                yes : function (){
                    deleteArtible({
                        id : $($this).attr('fh-data')
                    });
                }
            });
        });

        $('.recruit_delete .btn').click(function (){
            if($(this).hasClass('btn_del')){
                return false;
            }
            var params = {};
            params.id = new Array();
            $.each($('.recruit_list').has('.icon-checked'), function (index, item){
                params.id.push($(item).find('.delete').attr('fh-data'));
            });
            layer.confirm('删除之后将无法恢复,确定删除?', {
                yes : function (){
                    deleteArtible(params);
                }
            });
        });

        $('.recruit_delete .check').click(function (){
            if($(this).find('i').hasClass('icon-checked')){
                $(this).find('i').removeClass('icon-checked');
                $('.recruit_title .check i').removeClass('icon-checked');
            } else{
                $(this).find('i').addClass('icon-checked');
                $('.recruit_title .check i').addClass('icon-checked');
            }
            checkDelBtn();
        });
        //初始化页面
        function init(){
            common.createPage(getPageUrl);
        }
        function getPageUrl(page){
            var baseUrl = '/site/job/1.html';
            return baseUrl.replace('1', page);
        }

        //检查del是否可用
        function checkDelBtn(){
            var checkedLength = $('.recruit_title .check .icon-checked').length;
            if(checkedLength > 0){
                $('.recruit_delete .btn').removeClass('btn_del');
            } else{
                $('.recruit_delete .btn').addClass('btn_del');
            }
        }

        function deleteArtible(params){
            Cntysoft.Front.callApi('Site', 'deleteArticle', params, function (response){
                if(response.status){
                    layer.msg('删除成功');
                    setTimeout(function (){
                        window.location.reload();
                    }, 1000);
                } else{
                    layer.alert('删除失败!');
                }
            });
        }
    });
});