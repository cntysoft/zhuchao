define(['validate', 'webuploader', 'app/common', 'jquery', 'Core', 'Front'], function (validate, WebUploader, common){
    $(function (){
        var uploadIndex = -1;
        var uploaderConfig, uploadProductImg;
        init();
        function init()
        {
            //如果上传按钮没隐藏时
            if(!$('.img_uploading li.hide').length){
                createProductUpload();
            }
        }

        //加载案例中心的子栏目列表
//        Cntysoft.Front.callApi('Site', 'getCaseCategory', {}, function(response) {
//            if(response.status) {
//                var data = response.data;
//                var options = '';
//                $.each(data, function(index) {
//                    var item = data[index];
//                    options += '<option  value="' + item['id'] + '" selected>' + item['text'] + '</option>';
//                });
//                $('#nodeId').append(options);
//            }else {
//                //暂不处理
//            }
//        });

        //提交 submit为保存,draft为生成草稿
        $('#submit').click(function (){
            var validation = validate.checkFields($('.checkField'));
            if(validation.length){
                layer.msg('请正确填写各项');
                return false;
            }
            var images = $('.img_wrap_div');
            var content = [];
            var fileRefs = [];
            var imageLen = images.length;
            if(imageLen > 0){
                $.each(images, function (index, dom){
                    var img = {};
                    img['rid'] = $(dom).find('img').attr('fh-id');
                    img['src'] = $(dom).find('img').attr('src');
                    img['intro'] = $(dom).find('input').val();
                    content.push(img);
                    fileRefs.push(img['rid']);
                });
            } else{
                layer.msg('请至少上传一张图片');
                return false;
            }

            var title = $('#title').val();
            var intro = $('#intro').val();
            var path = window.location.pathname.split('/');
            var id = parseInt(path.pop());
            Cntysoft.Front.callApi('Site', 'modifyCase', {
                id : id,
                title : title,
                intro : intro,
                content : content,
                fileRefs : fileRefs
            }, function (response){
                if(response.status){
                    layer.msg('案例信息修改成功！', {
                        success : function (){
                            var redirect = function (){
                                window.location = '/site/caselist/1.html';
                            };
                            setTimeout(redirect, 300);
                        }
                    });
                } else{
                    layer.msg('案例信息修改失败，请核对您的信息！');
                }
            });
        });

        //删除商品图片
        $('.img_uploading').delegate('.deleteImg', 'click', function (){
            var imgWrap = $(this).parents('.img_wrap_div');
            imgWrap.remove();
            if(uploadProductImg == undefined){
                createProductUpload();
            }
        });
        //展示上传的图片
        function showImg(item){
            $('#uploadBtn').before('<div class="img_wrap_div"><div class="show_img"><img fh-id="' + item['rid'] + '" src="' + item['filename'] + '"></div><em class="deleteImg">删除</em><input type="text" placeholder="填写图片的简介" value=""></div>');
            if($('.img_wrap_div').length < 10){
                $('#uploadBtn').show();
            } else{
                $('#uploadBtn').hide();
            }
        }
        //初始化商品图片上传
        function createProductUpload(){
            uploaderConfig = {
                chunked : false,
                auto : true,
                dnd : '#uploadBtn',
                threads : 1,
                accept : {
                    title : 'Images',
                    extensions : 'gif,jpg,jpeg,bmp,png',
                    mimeTypes : 'image/*'
                },
                compress : {
                    // 图片质量，只有type为`image/jpeg`的时候才有效。
                    quality : 90,
                    // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
                    allowMagnify : false,
                    // 是否允许裁剪。
                    crop : false,
                    // 是否保留头部meta信息。
                    preserveHeaders : true,
                    // 如果发现压缩后文件大小比原来还大，则使用原来图片
                    // 此属性可能会影响图片自动纠正功能
                    noCompressIfLarger : false,
                    // 单位字节，如果图片大小小于此值，不会采用压缩。
                    compressSize : 1024 * 1024
                },
                server : '/front-api-entry',
                formData : {
                    REQUEST_META : Cntysoft.Json.encode({
                        cls : "Uploader",
                        method : "process"
                    }),
                    REQUEST_DATA : Cntysoft.Json.encode({
                        uploadDir : "/Data/UploadFiles/Apps/ZhuChao/YunZhan",
                        overwrite : true,
                        randomize : true,
                        createSubDir : true,
                        enableFileRef : true,
                        useOss : true
                    }),
                    REQUEST_SECURITY : Cntysoft.Json.encode({})
                }
            };
            //处理上传
            uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
                pick :{ id: '#uploadBtn',multiple:false}
            }));
            //上传商品图片
            uploadProductImg.on('beforeFileQueued', function (){
                if($('.img_wrap_div').length == 10){
                    layer.msg('最多上传10张图片');
                    return false;
                }
            });
            //商品图片上传成功
            uploadProductImg.on('uploadSuccess', function (file, response){
                if(response.status){
                    var data = response.data[0];
                    showImg(data);
                }
            });
        }

    });
});