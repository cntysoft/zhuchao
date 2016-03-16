/**
 * Created by wangzan on 2016/3/9.
 */
define(['jquery','module/base'], function () {
    $(document).ready(function () {

        $('.i_down').click(function () {
            if($(this).hasClass('icon-jiantou3')){
                $(this).parent('.more').prev().css('maxHeight','none');
                $(this).removeClass('icon-jiantou3').addClass('icon-jiantou1 main');
                $(this).prev().text('收起').addClass('main');
            }else {
                $(this).parent('.more').prev().css('maxHeight',40+'px');
                $(this).removeClass('icon-jiantou1 main ').addClass('icon-jiantou3');
                $(this).prev().text('更多').removeClass('main');
            }
        })
        //更多选项
        $('.show_more').click(function () {

            if(!$(this).hasClass('active')){
                $(this).addClass('active');
                $(this).children('span').addClass('main').text('收起');
                $(this).children('i').removeClass('icon-jiantou3').addClass('main icon-jiantou1');
                $('.choose_list .list_item:gt(1)').show();
                $('.choose_list .list_item:nth-child(2)').removeClass('no_bb');
            }else {
                $(this).removeClass('active');
                $(this).children('span').removeClass('main').text('更多选项');
                $(this).children('i').removeClass('main icon-jiantou1').addClass('icon-jiantou3');
                $('.choose_list .list_item:gt(1)').hide();
                $('.choose_list .list_item:nth-child(2)').addClass('no_bb');
            }
        });
        //排序
        $('.rank_wrap ul li').click(function () {
            var $i=$(this).find('i');
            var $index=$(this).index;
            $(this).find('a,i').addClass('main').parent().siblings().find('a,i').removeClass('main');
            if($index==0){
                $(this).find('a').addClass('main').parent().siblings().find('a,i').removeClass('main');
            }
            if($i.hasClass('icon-rank-down')){
                $i.removeClass('icon-rank-down').addClass('icon-rank-up');
            }else {
                $i.removeClass('icon-rank-up').addClass('icon-rank-down');
            }
        });
        $('.rank_wrap .check').click(function () {
            var on=$(this).find('i').hasClass('icon-checked');
            if(on){
                $(this).find('i').removeClass('icon-checked');
            }else {
                $(this).find('i').addClass('icon-checked');
            }
        });
    });
});