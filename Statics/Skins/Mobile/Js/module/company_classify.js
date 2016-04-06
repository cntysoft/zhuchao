/**
 * Created by Administrator on 2016/3/16.
 */
define(['zepto'], function (){
    $(function (){
        $('.header_left').tap(function (){
            $('.classify').toggleClass('show');
        });
    });
});