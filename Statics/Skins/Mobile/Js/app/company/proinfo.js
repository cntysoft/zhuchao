/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto','module/company_classify','swiper','module/totop'],function() {
    $(function () {
        var banner = new Swiper('.pro_banner',{
            pagination : '.pro-pagination',
            autoplay : 3000,
            speed:300,
            loop:true
        });
    });
})