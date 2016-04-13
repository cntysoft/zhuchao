/**
 * Created by wangzan on 2016/4/12.
 */

define(['zepto', 'swiper', 'module/mall_nav', 'module/totop'], function () {
    $(function () {

        $('.work_space').each(function (i, elements) {
            var $len = $(elements).find('.swiper-slide').not('.swiper-slide-duplicate').length;
            var $silde = $(elements).find('.swiper-container')
            var swiper = new Swiper($silde, {
                //pagination : '.swiper-pagination',
                // autoplay : 3000,
                // speed:300,
                loop: true,
                onSlideChangeStart: function (swiper) {
                    var $num = parseInt($(elements).find('.swiper-slide-active').attr('data-swiper-slide-mall')) + 1;
                    $(elements).find('.progress em').html($num);
                    $(elements).find('.progress span').html($len);
                }
            });

        });
        //进度


    });
});