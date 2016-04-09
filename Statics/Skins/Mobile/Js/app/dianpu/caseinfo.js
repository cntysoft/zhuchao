/**
 * Created by jiayin on 2016/4/8.
 */
define(['zepto','swiper','module/totop'],function(){
    $(function(){
        //广告
        var Ad = new Swiper('#caseinfo', {
            direction: 'horizontal',
            loop: true,
            onSlideChangeEnd: function (swiper) {
                var nowIndex = $('.swiper-slide-active').attr('data-swiper-slide-mall');
                var swiperPage = $('.header_title');
                var nowPageNum = Number(nowIndex) + 1;
                swiperPage.find('span').html(nowPageNum);


                var swiperTotal = $('.swiper-slide').size();
                swiperPage.find('b').html(swiperTotal - 2);
            }
        })

    });
});