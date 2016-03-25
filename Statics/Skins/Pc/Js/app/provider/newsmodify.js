/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'webuploader', 'jquery', 'kindEditor', 'zh_CN', 'Core', 'Front','app/common'], function (validate, WebUploader){
    $(function (){
        var uploadImg, editor, uploaderConfig;
        init();
        //提交 submit为保存,draft为生成草稿
        $('#submit').click(function (){
            var params = {};
            var validation = validate.checkFields($('.checkfield'));
            if(validation.length){
                validation[0].ele.focus();
                return false;
            }
            var content = editor.html();
            if($('#logo').length == 0){
                layer.msg('请上传封面');
                return false;
            }
            if(content.length < 20){
                layer.msg('新闻内容过少');
                editor.focus();
                return false;
            }
            params.title = $('#title').val();
            params.defaultPicUrl = [$('#logo').attr('src'), $('#logo').attr('fh-rid')];
            params.content = content;
            params.nodeId = $('#nodeId').val();
            $.extend(params, getEditorFileRef(content));
            params.fileRefs.push(params.defaultPicUrl[1]);
            //获取ID
            var path = window.location.pathname.split('/');
            params.id = parseInt(path.pop());
            Cntysoft.Front.callApi('Site', 'modifyContent', params, function (response){
                if(response.status) {
                    layer.msg('发表成功!',{
                        time:1000
                    }, function() {
                        window.location.href = '/site/news/1.html';
                    });
                }else {
                    if(response.errorCode == 10004){
                        layer.alert('文章标题重复!');
                        return false;
                    }
                    layer.alert('发表失败,请稍候再试!');
                }
            });
        });
        $('.img_uploading').delegate('.deleteImg', 'click', function (){
            $(this).closest('li').remove();
            $('.img_plus').show();
            if(uploadImg == undefined){
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



        function init(){
            //上传的默认配置项
            uploaderConfig = {
                chunked : false,
                auto : true,
                threads : 1,
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
                        uploadDir : "/Data/UploadFiles/Apps/YunZhan",
                        overwrite : true,
                        randomize : true,
                        createSubDir : true,
                        enableFileRef : true,
                        useOss : true
                    }),
                    REQUEST_SECURITY : Cntysoft.Json.encode({})
                }
            };
            createEditorUplad();
            if(!$('#logo').length){
                createUploader();
            }
        }

        function createUploader(){
            //处理上传
            uploadImg = WebUploader.create($.extend(uploaderConfig, {
                pick : '.img_plus'
            }));
            //logo上传成功
            uploadImg.on('uploadSuccess', function (file, response){
                if(response.status){
                    var out = '<li><img id="logo" src="' + response.data[0].filename + '" fh-rid="' + response.data[0].rid + '"><em class="deleteImg">删除</em></li>';
                    $('.img_plus').siblings('li').remove();
                    $('.img_plus').before(out);
                    $('.img_plus').hide();
                }
            });
        }
        //初始化编辑器
        function createEditorUplad(){
            var $editorUpload = $('body').append('<div class="hide" id="editorUpload"></div>');
            var editorUpload = WebUploader.create($.extend(uploaderConfig, {
                pick : '#editorUpload'
            }));
            //添加图片上传插件
            $('head').append('<style type="text/css" rel="stylesheet">.ke-icon-upload {background-position: 0px -496px;' +
            'width: 50px;height: 16px;}</style>');
            KindEditor.plugin('upload', function (K){
                var editor = this, name = 'upload';
                // 点击图标时执行
                editor.clickToolbar(name, function (){
                    $('#editorUpload input').click();
                });
            });
            KindEditor.lang({
                hello : '你好'
            });
            //实例化编辑器
            editor = KindEditor.create('#info_editor', {
                allowImageUpload : false,
                items : ['source', '|', 'undo', 'redo', '|', 'plainpaste', 'wordpaste', '|', 'upload', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat',
                    'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink'],
                pluginsPath : '/Statics/Skins/Pc/Images/kindeditor/plugins/'
            });
            //给img标签添加fh-rid属性
            editor.htmlTags.img = ['src', 'width', 'height', 'border', 'alt', 'title', 'align', '.width', '.height', '.border', 'fh-rid'];

            //编辑器上传图片
            editorUpload.on('uploadSuccess', function (file, response){
                if(response.status){
                    editor.insertHtml('<p fh-type="img"><img fh-rid="' + response.data[0].rid + '" src="' + response.data[0].filename + '"></p>');
                }
            });
        }


        function getEditorFileRef(content){
            var imgReg = /<img fh-rid="[\d]*" src="[\w\.\/\:\-]*"[\s]*?\/>/gim;
            var imgArray = content.match(imgReg);
            var params = {
                imgRefMap : [],
                fileRefs : []
            };
            if(imgArray != null){
                for(var i = 0, length = imgArray.length; i < length; i++) {
                    var ridSrc = imgArray[i].match(/<img fh-rid="([\d]*)" src="([\w\.\/\:\-]*)"[\s]*?\/>/);
                    params.imgRefMap.push([ridSrc[2], ridSrc[1]]);
                    params.fileRefs.push(ridSrc[1]);
                }
            }
            return params;
        }
    });
});