/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery', 'kindEditor','zh_CN'], function () {
    $(function () {
      editor = KindEditor.create('#info_editor',{
          items:[ 'source', '|', 'undo', 'redo', '|', 'plainpaste', 'wordpaste','|', 'image',  'justifyleft', 'justifycenter', 'justifyright',
              'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
              'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
              'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
              'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|',  'multiimage',
              'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
              'anchor', 'link', 'unlink', '|', 'about']
      });
    });
});