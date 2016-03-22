/**
 * Created by Administrator on 2016/3/16.
 */
define(['zepto'],function(){
    $(function(){
        //导航栏
        $('.header_left').tap(function(e){
            if($('.classify').width() == 0){
                $('.classify').css('width','120px');
                $('.l_main').css('margin-left','120px')
                $('body,html').css({'overflow':'hidden','height':'100%'});
                e.stopPropagation();
            }
            else{
                $('.classify').css('width','0px');
                $('.l_main').css('margin-left','0px');
                setTimeout(function(){
                    $('body,html').css({'overflow-y':'scroll','height':'auto'});
                },200)
            }
        });
        //$('body,html').click(function(){
        //    if($('.classify').width() > 0){
        //        $('.classify').css('width','0px');
        //        $('.l_main').css('margin-left','0px');
        //        setTimeout(function(){
        //            $('body,html').css({'overflow-y':'scroll','height':'auto'});
        //        },200)
        //    }
        //});
        /*
        导航栏触摸隐藏
        * */
        var  initX = 0;
        var initY = 0;
        var endX = 0;
        var endY = 0;
        $('.l_main').bind('touchstart',function(e){
            initX =  event.targetTouches[0].clientX;
            initY = event.targetTouches[0].clientY;
        });
        //document.getElementById('l_main').addEventListener('touchstart',function(){
        //      initX =  event.targetTouches[0].clientX;
        //    initY = event.targetTouches[0].clientY;
        //
        //
        //},true);
        //document.getElementById('l_main').addEventListener('touchmove',function(e){
        //    if($('.classify').width() != 0){
        //        //e.stopPropagation();
        //    }
        //},true);
        $('.l_main').bind('touchmove',function(e){
            if($('.classify').width() != 0) {
                e.preventDefault();
            }
        });

        $('.l_main').bind('touchend',function(e){

            endX =   event.changedTouches[0].clientX ;
            endY = event.changedTouches[0].clientY;
            //alert(342);
            //alert(Math.abs(endX-initX));
            if( Math.abs(endX-initX) > Math.abs(endY-initY) && Math.abs(endX-initX) > 10){
                $('.classify').css('width','0px');

                $('.l_main').css('margin-left','0px');
                setTimeout(function(){
                    $('body,html').css({'overflow-y':'scroll','height':'auto'});
                },200);
            }
        });
        //document.getElementById('l_main').addEventListener('touchend',function(){
        //      endX =   event.changedTouches[0].clientX ;
        //     endY = event.changedTouches[0].clientY;
        //    alert(Math.abs(endX-initX));
        //    if( Math.abs(endX-initX) > Math.abs(endY-initY) && Math.abs(endX-initX) > 10){
        //        $('.classify').css('width','0px');
        //        alert(342);
        //        $('.l_main').css('margin-left','0px');
        //        setTimeout(function(){
        //            $('body,html').css({'overflow-y':'scroll','height':'auto'});
        //        },200);
        //    }
        //},true);

    })
})