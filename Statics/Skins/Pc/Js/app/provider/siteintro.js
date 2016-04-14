/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'webuploader', 'app/common', 'jquery', 'kindEditor', 'zh_CN', 'Core', 'Front'], function (validate, WebUploader, common){
    $(function (){
        var editor;
        init();
        //提交 submit为保存,draft为生成草稿
        $('#submit').click(function (){
            var params = {};
            var content = editor.html();
            if(content.length < 20){
                layer.msg('详情内容过少');
                editor.focus();
                return false;
            }
            params.content = content;
            $.extend(params,getEditorFileRef(content));
            Cntysoft.Front.callApi('Site', 'modifyIntro', params, function(response) {
                if(response.status){
                     layer.msg('信息保存成功!');
                } else{
                    layer.msg('信息保存失败,请稍候再试');
                }
            });
        });


        function init(){
            //上传的默认配置项
            uploaderConfig = {
                chunked : false,
                auto : true,
                threads : 1,
                duplicate:true,
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
                    'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink'],
                pluginsPath : '/Statics/Skins/Pc/Images/kindeditor/plugins/',
                htmlTags : {
                    font : ['color', 'size', 'face', '.background-color'],
                    span : [
                        '.color', '.background-color', '.font-size', '.font-family', '.background',
                        '.font-weight', '.font-style', '.text-decoration', '.vertical-align', '.line-height'
                    ],
                    div : [
                        'align', '.border', '.margin', '.padding', '.text-align', '.color',
                        '.background-color', '.font-size', '.font-family', '.font-weight', '.background',
                        '.font-style', '.text-decoration', '.vertical-align', '.margin-left'
                    ],
                    table : [
                        'border', 'cellspacing', 'cellpadding', 'width', 'height', 'align', 'bordercolor',
                        '.padding', '.margin', '.border', 'bgcolor', '.text-align', '.color', '.background-color',
                        '.font-size', '.font-family', '.font-weight', '.font-style', '.text-decoration', '.background',
                        '.width', '.height', '.border-collapse'
                    ],
                    'td,th' : [
                        'align', 'valign', 'width', 'height', 'colspan', 'rowspan', 'bgcolor',
                        '.text-align', '.color', '.background-color', '.font-size', '.font-family', '.font-weight',
                        '.font-style', '.text-decoration', '.vertical-align', '.background', '.border'
                    ],
                    a : ['href', 'target', 'name'],
                    embed : ['src', 'width', 'height', 'type', 'loop', 'autostart', 'quality', '.width', '.height', 'align', 'allowscriptaccess'],
                    img : ['src', 'fh-rid', 'data-original', 'class', 'width', 'height', 'border', 'alt', 'title', 'align', '.width', '.height', '.border'],
                    'p,ol,ul,li,blockquote,h1,h2,h3,h4,h5,h6' : [
                        'align', '.text-align', '.color', '.background-color', '.font-size', '.font-family', '.background',
                        '.font-weight', '.font-style', '.text-decoration', '.vertical-align', '.text-indent', '.margin-left'
                    ],
                    pre : ['class'],
                    hr : ['class', '.page-break-after'],
                    'br,tbody,tr,strong,b,sub,sup,em,i,u,strike,s,del' : []
                }
            });
            var iframeBody = $('.ke-edit-iframe')[0].contentWindow.document.body;
            var $introImg = $(iframeBody).find('img');
            $.each($introImg, function (index, item){
                $(item).attr('src', $(item).attr('data-original'));
            });
            editor.html($(iframeBody).html());
            //编辑器上传图片
            editorUpload.on('uploadSuccess', function (file, response){
                if(response.status){
                    editor.insertHtml('<p fh-type="img"><img fh-rid="' + response.data[0].rid + '" src="' + response.data[0].filename + '"></p>');
                }
            });
        }


         function getEditorFileRef(){
            var iframeBody = $('.ke-edit-iframe')[0].contentWindow.document.body;
            var $introImg = $(iframeBody).find('img');
            var params = {
                imgRefMap : [],
                fileRefs : [],
                content : ''
            };
            if($introImg.length){
                $.each($introImg, function (index, item){
                    params.imgRefMap.push([$(item).attr('src'), $(item).attr('fh-rid')]);
                    params.fileRefs.push($(item).attr('fh-rid'));
                });
            }
            var cloneIframe = $(iframeBody).clone();
            $.each($(cloneIframe).find('img'), function (index, item){
                $(item).addClass('lazy');
                $(item).attr('data-original', $(item).attr('src'));
                $(item).attr('src', common.lazyicon);
                $(item).removeAttr('data-ke-src');
            });
            params.content = $(cloneIframe).html();
            return params;
        }
    });
});