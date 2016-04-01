/**
 * Created by Administrator on 2016/4/1.
 */
define(['jquery','slick'],function () {
    $(function () {
        for(var i=0;i<$('.small_img').size();i++){
            var n=$('.small_img').size();
            $('.count').eq(i).html(i+1+'/'+n);
            $('.big_img >div').eq(i).css({'height':$('.big_img img').eq(i).height()+'px','width':$('.big_img img').eq(i).width()+'px'});
        }
        var status=0;
        $('.slick').slick({
            autoplay:false,
            slidesToShow:1,
            prevArrow:'.big_left',
            nextArrow:'.big_right',
            infinite:false,
            asNavFor:'.slick_nav',
            fade:true,
            speed:100
        });
        $('.slick_nav').slick({
            slidesToShow: 5,
            slidesToScroll: 5,
            prevArrow:'.small_left',
            nextArrow:'.small_right',
            infinite:false,
            asNavFor: '.slick',
            focusOnSelect: true
        });
        $('.small_img').click(function(){
            status= $.inArray(this,$('.small_img'));
            $('.small_img img').removeClass('border_green');
            $('.small_img img').eq($.inArray(this,$('.small_img'))).addClass('border_green')
        });
        $('.big_com').click(function(){
            status=$('.slick').find('.slick-active').attr('data-slick-index');
            $('.small_img img').removeClass('border_green');
            $('.small_img img').eq(status).addClass('border_green');
        });
        $('.small_right').click(function(){
            var n=$('.slick').find('.slick-active').attr('data-slick-index');
            if(status==n){
                status=parseInt(n)+5>$('.big_img').size()-1?parseInt(n):parseInt(n)+5;
            }
            else{
                status=n;
            }
            $('.small_img img').removeClass('border_green');
            $('.small_img img').eq(status).addClass('border_green');
            status=n;
        });
        $('.small_left').click(function(){
            var n=$('.slick_nav').find('.slick-active').attr('data-slick-index');
            $('.small_img img').removeClass('border_green');
            $('.small_img img').eq(n).addClass('border_green');
        });
        var delay=0;
        var time=null;
        $('.big_img >div').hover(function(){
            var index=$.inArray(this, $('.big_img >div'));
            var width=$(this).width();
            var height=$(this).height();
            clearInterval(time);
            delay=0;
            time=setInterval(function(){
                delay+=1;
                if(delay>2){
                    window.clearInterval(time);
                    delay=0;
                    //$('.store').eq(index).css({'right':(1029-width)/2+'px','top':(550-height)/2+'px'});
                    $('.store').eq(index).animate({width:'54px'},200);
                }
            },100);
        },function(){
            delay=0;
            clearInterval(time);
            $('.store').animate({width:'0'},200);
        });
        $('.slick_bg>div,.big_com').click(function(){
            $('.big_img >div').hover(function(){
                var index=$.inArray(this,   $('.big_img >div'));
                var width=$(this).width();
                var height=$(this).height();
                clearInterval(time);
                delay=0;

                time=setInterval(function(){
                    delay+=1;
                    if(delay>2){
                        window.clearInterval(time);
                        delay=0;
                        //$('.store').eq(index).css({'right':(1029-width)/2+'px','top':(550-height)/2+'px'});
                        $('.store').eq(index).animate({width:'54px'},200);
                    }
                },100);
            },function(){
                delay=0;
                clearInterval(time);
                $('.store').animate({width:'0'},200);
            });
        });
    })
})