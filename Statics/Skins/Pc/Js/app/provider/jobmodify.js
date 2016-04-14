/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'webuploader', 'datepicker', 'jquery', 'kindEditor', 'zh_CN', 'Core', 'Front', 'app/common'], function (validate, WebUploader){
    $(function (){
        var images = new Array();
        var editor;
        init();
        $('input[fh-toggle="datepicker"]').datepicker({
            format : 'yyyy-mm-dd'
        });
        //提交 submit为保存,draft为生成草稿
        $('#submit').click(function (){
            var params;
            var validation = validate.checkFields($('.checkField'));
            if(validation.length){
                validation[0].ele.focus();
                layer.msg('请正确填写各项');
                return false;
            }
            var content = editor.html();
            if(content.length < 20){
                layer.msg('招聘详情内容过少');
                return false;
            }
            params = validate.getInputValue($('#title,#department,#number'));
            params.content = content;
            params.endTime = parseInt($('#endTime').datepicker('getDate', false).getTime() / 1000);
            params.tel = $('#telCountry').val() + '-' + $('#telArea').val() + '-' + $('#telNum').val();
            var path = window.location.pathname.split('/');
            params.id = parseInt(path.pop());
            params.phone = $('#phone').val();
            Cntysoft.Front.callApi('Site', 'modifyContent', params, function (response){
                if(response.status){
                    layer.msg('修改招聘信息成功!', {
                        time : 2000
                    }, function (){
                        window.location.href = '/site/job/1.html';
                    });
                } else{
                    layer.msg('修改招聘信息失败,请稍候再试!');
                }
            });
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
            createEditorUplad();
        }

        function createEditorUplad(){
            $('body').append('<div class="hide" id="editorUpload"></div>');
            //实例化编辑器
            editor = KindEditor.create('#info_editor', {
                allowImageUpload : false,
                items : ['source', '|', 'undo', 'redo', '|', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat',
                     'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink'],
                pluginsPath : '/Statics/Skins/Pc/Images/kindeditor/plugins/'
            });
        }
    });
});