/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'webuploader', 'jquery', 'kindEditor', 'zh_CN', 'Core', 'Front'], function (validate, WebUploader){
    $(function (){
        var uploadIndex = -1;
        var images = new Array();
        var sendQuery =  Cntysoft.fromQueryString(window.location.search);
        var checkArea = '#title,#brand,#description,#advertText,#minimum,#stock,#price';
        //提交 submit为保存,draft为生成草稿
        $('#submit,#draft').click(function (){
            var validation = validate.checkFields($(checkArea + ',#keyword,#keyword2,#keyword3,.basic .attrInput,.customAttr .attr_info,.customAttr .attrTitle'));
            var imgReg = /<img fh-rid="[\d]*" src="[\w\.\/\:\-]*"[\s]*?\/>/gim;
            var params = {};
            var fileRefs = new Array();
            var imgRefMap = new Array();
            if(validation.length){
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
            params['images'] = images;

            var editorHtml = editor.html();
            params['introduction'] = editorHtml;
            var imgArray = editorHtml.match(imgReg);
            if(imgArray != null){
                for(var i = 0, length = imgArray.length; i < length; i++) {
                    var ridSrc = imgArray[i].match(/<img fh-rid="([\d])*" src="([\w\.\/\:\-]*)"[\s]*?\/>/);
                    imgRefMap.push([ridSrc[2], ridSrc[1]]);
                    fileRefs.push(ridSrc[1]);
                }
            }
            params['imgRefMap'] = imgRefMap;
            for(var i = 0, length = images.length; i < length; i++) {
                fileRefs.push(images[i][1]);
            }
            params['fileRefs'] = fileRefs;
            params['isBatch'] = getRadioValueByName('isBatch');
            params['unit'] = $('#proUnit').val();
            params['categoryId'] = sendQuery['category'];
            if($(this).attr('id') === 'submit'){
                params['status'] = 1;
            }else{
                params['status'] = 3;
            }
            Cntysoft.Front.callApi('Product','addProduct',params,function(response){
                if(response.status){
                    layer.alert('商品发布成功！', {
                        btn : '',
                        success : function(){
                           var redirect = function(){
                              window.location = '/product/1.html';
                           };
                           setTimeout(redirect, 300);
                        }
                     });
                }else{
                    layer.alert('商品发布错误，请核对您的信息！');
                }
            });
        });
        //添加属性
        $('.attr_add').click(function (){
            var out = '<div class="attr_list clearfix customAttr"><span class="attr_title add_title"><input type="text" class="attrTitle" style="text-align:left"  fh-type="length" minlength="1" maxlength="6" required>：</span><input class="attr_info input_text attrInput" type="text"  fh-type="length" minlength="1" maxlength="10" required><span class="attr_delete deleteAttr">删除</span> </div>';
            if($('.customAttr').length > 3){
                layer.alert('最多添加4个自定义属性');
                return false;
            }
            $(this).before(out);
        });
        //删除属性
        $('.attr_wrap').delegate('.deleteAttr', 'click', function (){
            console.log($(this).closest('.customAttr'));
            $(this).closest('.customAttr').remove();
        });
        //上传的默认配置项
        var uploaderConfig = {
            chunked : false,
            auto : true,
            dnd : '#uploadBtn',
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
        var uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
            pick : '#uploadBtn'
        }));
        //上传商品图片
        uploadProductImg.on('beforeFileQueued', function (){
            if(images.length == 5){
                layer.alert('最多上传5张图片');
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
        //删除商品图片
        $('.img_uploading').delegate('.deleteImg','click',function(){
            var imgWrap = $(this).closest('li');
            images.splice($(imgWrap).index(),1);
            showImg();
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
                $('#uploadBtn').before('<li><img src="'+item[0]+'"><em class="deleteImg">删除</em></li>');
            });
            if(images.length != 5){
                $('#uploadBtn').show();
            }else{
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
            pluginsPath : '/Statics/Skins/Pc/Images/kindeditor/plugins/'
        });
        //给img标签添加fh-rid属性
        editor.htmlTags.img = ['src', 'width', 'height', 'border', 'alt', 'title', 'align', '.width', '.height', '.border', 'fh-rid'];
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