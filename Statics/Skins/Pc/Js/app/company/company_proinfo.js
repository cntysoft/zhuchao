/**
 * Created by wangzan on 2016/3/14.
 */
define(['jquery','slick','module/totop'],function(){
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
        });
        $('.show_small').slick({
            speed: 500,
            slidesToShow: 4,
            slidesToScroll:1,
            arrows: true,
            dots: false,
            draggable: false,
            infinite:true,
            prevArrow:'.prev_btn',
            nextArrow:'.next_btn',
        });

        $('.show_small img').hover(function () {
            var imgSrc = $(this).attr('src');
            $('.show_big img').attr({
                "src": imgSrc,
            });
            $(this).parent('div').addClass('main_border').siblings().removeClass('main_border');
        });
        $('.describe_title a').click(function () {
            $(this).addClass('main_border').siblings().removeClass('main_border');
            $('.describle_info').children().eq($(this).index()).show().siblings().hide();
            return false;
        });
    })
})