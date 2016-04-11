/**
 * Created by Administrator on 2016/4/8.
 */
define(["zepto", "swiper"], function (){
    ;
    (function ($){
        $.fn.showImage = function (options){
            var setting = {
                index : 0
            };
            $.extend(setting, options);
            var $img = $(this).find("img");
            //调用swiper
            var out;
            $.each($img, function (index, item){
                $(item).click(function (){
                    if(out){
                        $("#showImage").show();
                        out.slideTo(index, 1);
                        $('html').css('overflow', 'hidden');
                    } else{
                        if($("#showImage").length == 0){
                            var length = $($img).length;
                            //添加html
                            $("body").append('<div class="out" id="showImage" style="position:fixed;width:' + window.screen.width + 'px;height:' + window.screen.height + 'px;"><h4><i class="icon-fanhuijian"></i><span>1</span>/<span>' + length + '</span></h4></i>' +
                            '<div class="out_box"> <div class="swiper-wrapper"></div></div></div>');
                            console.log(length);
                            for(var i = 0; i < length; i++) {
                                var src = $($img).eq(i).attr("src");
                                if($($img).eq(i).attr("data-original")){
                                    src = $($img).eq(i).attr("data-original");
                                }
                                var slide = '<div class="swiper-slide"><img src="' + src + '"></div>'
                                $(".out .swiper-wrapper").append(slide);
                            }
                            out = new Swiper(".out_box", {
                                loop : false,
                                initialSlide : index,
                                onTransitionEnd : function (swiper){
                                    $(".out span").eq(0).html(swiper.activeIndex + 1);
                                }
                            });
                            $("#showImage").click(function (event){
                                event.stopPropagation();
                            });
                            $("#showImage h4 i").click(function (){
                                $("#showImage").hide();
                                $('html').css('overflow', 'auto');
                            });
                        }
                    }
                });
            });
        };
       $(window).resize(function(){
           $('showImage').css({
               width:window.screen.width+'px',
               height:window.screen.height+'px'
           });
       });
    })(Zepto)
})