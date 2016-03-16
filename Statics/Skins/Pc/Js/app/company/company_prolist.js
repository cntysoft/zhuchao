/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery','module/totop'],function(){
    $(function(){
        $('.classify_list h2').click(function () {
            if($(this).hasClass('active')){
                $(this).find('i').addClass('icon-black-right').removeClass('icon-black-down');
                $(this).next().hide();
                $(this).removeClass('active');
            }else {
                $(this).find('i').removeClass('icon-black-right').addClass('icon-black-down');
                $(this).next().show();
                $(this).addClass('active');
            }
        })
    })
})