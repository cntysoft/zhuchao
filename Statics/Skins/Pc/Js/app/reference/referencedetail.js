/**
 * Created by wangzan on 2016/3/16.
 */
define(['jquery', 'app/common', 'layer', 'layer.ext'], function (){
    $(function (){
        var path = window.location.pathname;
        if(path.indexOf('laobanneican') > 0){
            $('.head ul li a').eq(0).addClass('main_bg');
        }
        if(path.indexOf('jiancaiqiwen') > 0){
            $('.head ul li a').eq(1).addClass('main_bg');
        }
        if(path.indexOf('shendujiexi') > 0){
            $('.head ul li a').eq(2).addClass('main_bg');
        }
        if(path.indexOf('zhongbangtuijian') > 0){
            $('.head ul li a').eq(3).addClass('main_bg');
        }
        $('head').append('<script type="text/javascript" charset="utf-8" async=""  src="/Statics/Skins/Pc/Js/lib/qrcode.js"></script>');
        var origin = window.location.href;
//      setTimeout(function (){
        $('#weixin_code').qrcode({
            width : 200,
            height : 200,
            text : origin
        });
        << << << < HEAD
        if($('.article_summary').attr('article')){
            Cntysoft.Front.callApi('Utils', 'addArticleHits',
            {
                id : $('.article_summary').attr('article')
            }, function (response){
            }
            , this);
        }
        layer.photos({
            photos : '.summary_inner',
            shift : 0
        });
    });
});