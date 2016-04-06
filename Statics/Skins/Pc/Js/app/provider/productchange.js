/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'webuploader', 'app/common', 'jquery', 'kindEditor', 'zh_CN', 'Core', 'Front'], function (validate, WebUploader, common){
    $(function (){
        var uploadIndex = -1;
        var images = new Array();
        var pathname = window.location.pathname;
        var number = pathname.substring(pathname.indexOf('/product/change/') + 16, pathname.lastIndexOf('.html'));
        var checkArea = '#title,#brand,#description,#advertText,#minimum,#stock,#price';
        var uploaderConfig, uploadProductImg;
        init();
        function init()
        {
            //如果上传按钮没隐藏时
            if(!$('.img_uploading li.hide').length){
                createProductUpload();
            }
            var $uploaded = $('.img_uploading .image_uploaded');
            if($uploaded.length > 0){
                $.each($uploaded, function (index, item){
                    var $img = $(item).find('img');
                    images.push([$img.attr('src').split('@.src')[0], $img.attr('fh-rid')]);
                });
            }
        }
        $('#brand,#title,#description,#advertText').keyup(function (){
            var len = $(this).val().length;
            $(this).next('span').find('em').text(len);
        });
        //提交 submit为保存,draft为生成草稿
        $('#submit,#draft').click(function (){
            var validation = validate.checkFields($(checkArea + ',#keyword,#keyword2,#keyword3,.basic .attrInput,.customAttr .attr_info,.customAttr .attrTitle'));
            var imgReg = /<img fh-rid="[\d]*" src="[\w\.\/\:\-]*"[\s]*?\/>/gim;
            var params = {};
            var fileRefs = new Array();
            var imgRefMap = new Array();
            if(validation.length){
                validation[0].ele.focus();
                layer.msg('请正确填写每项!');
                return false;
            }
            if(validate.notEqual($('.customAttr .attrTitle'))){
                layer.msg('请正确填写每项!');
                return false;
            }
            $.each($(checkArea), function (index, item){
                params[$(item).attr('id')] = $(item).val();
            });
            params['keywords'] = new Array();
            params['keywords'].push($('#keyword').val());
            if($('#keyword2').val()){
                params['keywords'].push($('#keyword2').val());
            }
            if($('#keyword3').val()){
                params['keywords'].push($('#keyword3').val());
            }
            params['group'] = $('#proGroup').val();
            
            params['attribute'] = {
                '基本参数' : {},
                '自定义属性' : {}
            }
            $.each($('.basic .attrSelect,.basic .attrInput'), function (index, item){
                params['attribute']['基本参数'][$(item).attr('id')] = $(item).val();
            });
            $.each($('.customAttr'), function (index, item){
                var key = $(item).find('input').eq(0).val();
                var val = $(item).find('input').eq(1).val();
                params['attribute']['自定义属性'][key] = val;
            });
            if(0 == images.length){
                layer.msg('请上传产品的图片，至少1张!');
                return;
            }
            params['images'] = images;

            var editorHtml = editor.html(), editorText = editor.text();
            if(!editorText || editorText.length < 20){
                layer.msg('请填写商品简介信息，至少20个字！');
                return;
            }
            
            var iframeBody = $('.ke-edit-iframe')[0].contentWindow.document.body;
            var $introImg = $(iframeBody).find('img');
            if($introImg.length){
                $.each($introImg, function (index, item){
                    imgRefMap.push([$(item).attr('src'), $(item).attr('fh-rid')]);
                    fileRefs.push($(item).attr('fh-rid'));
                });
            }
            var cloneIframe = $(iframeBody).clone();
            $.each($(cloneIframe).find('img'), function (index, item){
                $(item).addClass('lazy');
                $(item).attr('data-original', $(item).attr('src'));
                $(item).attr('src', common.lazyicon);
                $(item).removeAttr('data-ke-src');
            });

            params['introduction'] = $(cloneIframe).html();
            params['imgRefMap'] = imgRefMap;
            for(var i = 0, length = images.length; i < length; i++) {
                fileRefs.push(images[i][1]);
            }
            params['fileRefs'] = fileRefs;
            params['isBatch'] = getRadioValueByName('isBatch');
            params['unit'] = $('#proUnit').val();
            if($(this).attr('id') === 'submit'){
                params['status'] = 3;
            } else{
                params['status'] = 1;
            }
            params['number'] = number;
            Cntysoft.Front.callApi('Product', 'updateProduct', params, function (response){
                if(response.status){
                    layer.msg('商品修改成功！', {
                        success : function (){
                            var redirect = function (){
                                window.location = '/product/1.html';
                            };
                            setTimeout(redirect, 300);
                        }
                    });
                } else{
                    layer.msg('商品修改错误，请核对您的信息！');
                }
            });
        });
        //添加属性
        $('.attr_add').click(function (){
            var out = '<div class="attr_list clearfix customAttr"><span class="attr_title add_title"><input type="text" class="attrTitle" style="text-align:left"  fh-type="length" minlength="1" maxlength="6" required>：</span><input class="attr_info input_text attrInput" type="text"  fh-type="length" minlength="1" maxlength="10" required><span class="attr_delete deleteAttr">删除</span> </div>';
            if($('.customAttr').length > 3){
                layer.msg('最多添加4个自定义属性');
                return false;
            }
            $(this).before(out);
        });
        //删除属性
        $('.attr_wrap').delegate('.deleteAttr', 'click', function (){
            $(this).closest('.customAttr').remove();
        });

        //删除商品图片
        $('.img_uploading').delegate('.deleteImg', 'click', function (){
            var imgWrap = $(this).closest('li');
            images.splice($(imgWrap).index(), 1);
            showImg();
            if(uploadProductImg == undefined){
                createProductUpload();
            }
        });
        //商品标题预览
        $('#title,#brand,#description,#advertText').keyup(function (){
            var val = $(this).val();
            if($(this).attr('id') === 'title'){
                if(val !== ''){
                    $('#preview strong').eq(1).text(val);
                } else{
                    $('#preview strong').eq(1).text('产品名称+型号');
                }
            } else if($(this).attr('id') === 'brand'){
                if(val !== ''){
                    $('#preview strong').eq(0).text(val);
                } else{
                    $('#preview strong').eq(0).text('产品名称+型号');
                }
            } else if($(this).attr('id') === 'description'){
                if(val !== ''){
                    $('#preview strong').eq(2).text(val);
                } else{
                    $('#preview strong').eq(2).text('产品描述');
                }
            } else if($(this).attr('id') == 'advertText'){
                if(val !== ''){
                    $('#preview dd').text(val);
                } else{
                    $('#preview dd').text('产品广告语');
                }
            }
        });
        //展示上传的图片
        function showImg(){
            $('#uploadBtn').siblings('li').remove();
            $.each(images, function (index, item){
                $('#uploadBtn').before('<li><img src="' + item[0] + '"><em class="deleteImg">删除</em></li>');
            });
            if(images.length != 5){
                $('#uploadBtn').show();
            } else{
                $('#uploadBtn').hide();
            }
        }
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
        //初始化商品图片上传
        function createProductUpload(){
            uploaderConfig = {
                chunked : false,
                auto : true,
                dnd : '#uploadBtn',
                threads : 1,
                duplicate:true,
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
            //处理上传
            uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
                pick : '#uploadBtn'
            }));
            //上传商品图片
            uploadProductImg.on('beforeFileQueued', function (){
                if(images.length == 5){
                    layer.msg('最多上传5张图片');
                    return false;
                }
            });
            //商品图片上传成功
            uploadProductImg.on('uploadSuccess', function (file, response){
                if(response.status){
                    images.push([response.data[0].filename, response.data[0].rid]);
                    showImg();
                }
            });
        }
        //添加图片上传插件
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
        var editor = KindEditor.create('#info_editor', {
            allowImageUpload : false,
            items : ['source', '|', 'undo', 'redo', '|', 'plainpaste', 'wordpaste', '|', 'upload', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat',
                'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
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
        $.each($introImg,function(index,item){
            $(item).attr('src',$(item).attr('data-original'));
        });
        var editorUpload = WebUploader.create($.extend(uploaderConfig, {
            pick : '#editorUpload'
        }));
        //编辑器上传图片
        editorUpload.on('uploadSuccess', function (file, response){
            if(response.status){
                editor.insertHtml('<p fh-type="img"><img fh-rid="' + response.data[0].rid + '" src="' + response.data[0].filename + '"></p>');
            }
        });

    });
});