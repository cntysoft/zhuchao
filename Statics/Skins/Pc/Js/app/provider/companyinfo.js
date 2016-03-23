define(['validate', 'webuploader', 'jquery', 'Core', 'Front', 'layer', 'module/address'], function (validate, WebUploader){
    $(function (){
        init();
        var uploadProductImg;
        $('#submit').click(function (){
            var hasError = false;
            var validation = validate.checkFields($('.checkField,.company,.register'));
            if(validation.length){
                layer.msg('请正确填写各项');
                return false;
            }
            var params = {};
            $.each($('.checkField,.register,.company'), function (index, item){
                params[$(item).attr('id')] = $(item).val();
            });
            params.products = '';
            $.each($('.checkField[id^=product]'), function (index, item){
                delete params[$(item).attr('id')];
                if($(item).val() != ''){
                    params.products == '' ? params.products += $(item).val() : params.products += ',' + $(item).val();
                }
            });
            if($('#logo').length){
                params['logo'] = $('#logo').attr('src');
            } else{
                validate.tips('请上传企业logo', '.img_plus');
                layer.msg('请正确填写各项');
                return false;
            }
            params['tradeMode'] = getRadioValueByName('tradeMode');
            Cntysoft.Front.callApi('Company', 'updateCompany', params, function (response){
                if(response.status){
                     layer.msg('信息保存成功!');
                } else{
                    layer.msg('信息保存失败,请稍候再试');
                }
            });
        });
        //上传的默认配置项
        var uploaderConfig = {
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
                    uploadDir : "/Data/UploadFiles/Apps/ZhuChao/Product",
                    overwrite : true,
                    randomize : true,
                    createSubDir : true,
                    enableFileRef : true,
                    useOss : true
                }),
                REQUEST_SECURITY : Cntysoft.Json.encode({})
            }
        };


        $('.img_uploading').delegate('.deleteImg', 'click', function (){
            $(this).closest('li').remove();
            $('.img_plus').show();
            if(uploadProductImg == undefined){
                createUploader();
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
        //初始化函数
        function init(){
            if($('.logo').length == 1){
                createUploader();
            }
        }

        function createUploader(){
            //处理上传
            uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
                pick : '.img_plus'
            }));
            //logo上传成功
            uploadProductImg.on('uploadSuccess', function (file, response){
                if(response.status){
                    var out = '<li class="logo"><img id="logo" src="' + response.data[0].filename + '" fh-rid="' + response.data[0].rid + '"><em class="deleteImg">删除</em></li>';
                    $('.img_plus').siblings('li').remove();
                    $('.img_plus').before(out);
                    $('.img_plus').hide();
                }
            });
        }
    });
});