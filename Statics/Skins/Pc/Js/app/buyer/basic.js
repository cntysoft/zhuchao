define(['validate', 'webuploader', 'jquery', 'Core', 'Front', 'layer', 'search', 'app/common'], function (validate, WebUploader){
    $(function (){
        var reg = new RegExp(/^[0-9a-zA-Z-_]{3,10}$/);
        var uploadProductImg, uploaderConfig;
        init();
        $('#submit').click(function (){
            var hasError = false;
            var params = {};
            if($('#avatar').length){
                params['avatar'] = $('#avatar').attr('src');
            } else{
                validate.tips('请上传个人头像', '.img_plus');
                return false;
            }
            params['sex'] = getRadioValueByName('sex');
            if($('#name').attr('disabled')){

            } else{
                if(!$('#name').val() || !reg.test($('#name').val())){
                    validate.tips('请填写3-10位字母数字的用户名', '#name');
                    return false;
                }
                params['sex'] = getRadioValueByName('sex');
                if($('#name').attr('disabled')){

                } else{
                    if(!$('#name').val() || !reg.test($('#name').val())){
                        validate.tips('请填写3-10位字母数字的用户名', '#name');
                        return false;
                    }
                    params['name'] = $('#name').val();
                }
                params['fileRefs'] = [$('#avatar').attr('fh-rid')];
            }
            Cntysoft.Front.callApi('User', 'updateBuyer', params, function (response){
                  if(!response.status){
                      layer.alert('当前用户名不可用！');
                  } else{
                      layer.msg('用户信息修改成功');
                  }
              });
        });
        //上传的默认配置项

        //处理上传

        $('.img_uploading').delegate('.deleteImg', 'click', function (){
            $(this).closest('li').remove();
            $('.img_plus').show();
            if(uploadProductImg == undefined){
                createWebuploader();
            }
        });
        //根据name获得radio的值
        function getRadioValueByName(name){
            var val = null;
            $.each($('input[name=' + name + ']'), function (index, item){
                if($(item).prop('checked')){
                    val = $(item).val();
                    return false;
                }
            });
            return val;
        }

        function init(){
            uploaderConfig = {
                chunked : false,
                auto : true,
                dnd : '.img_plus',
                threads : 1,
                duplicate : true,
                accept : {
                    title : 'Images',
                    extensions : 'gif,jpg,jpeg,bmp,png',
                    mimeTypes : 'image/*'
                },
                server : '/front-api-entry',
                formData : {
                    REQUEST_META : Cntysoft.Json.encode({
                        cls : "Uploader",
                        method : "process"
                    }),
                    REQUEST_DATA : Cntysoft.Json.encode({
                        uploadDir : "/Data/UploadFiles/Apps/ZhuChao/Buyer",
                        overwrite : true,
                        randomize : true,
                        createSubDir : true,
                        enableFileRef : true,
                        useOss : true
                    }),
                    REQUEST_SECURITY : Cntysoft.Json.encode({})
                }
            };
            if(!$('#avatar').length){
                createWebuploader();
            }
        }

        function createWebuploader()
        {
            uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
                pick : '.img_plus'
            }));

            //商品图片上传成功
            uploadProductImg.on('uploadSuccess', function (file, response){
                if(response.status){
                    var out = '<li><img id="avatar" src="' + response.data[0].filename + '" fh-rid="' + response.data[0].rid + '"><em class="deleteImg">删除</em></li>';
                    $('.img_plus').siblings('li').remove();
                    $('.img_plus').before(out);
                    $('.img_plus').hide();
                }
            });
        }
    });
});