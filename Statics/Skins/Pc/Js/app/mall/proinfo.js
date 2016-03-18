/**
 * Created by Administrator on 2016/3/10.
 */
define(['jquery','module/base','module/totop'],function(){
    $(function(){
        /*图片切换*/
        var small_img = $('.small_img').find('div');
        small_img.hover(function(){
            small_img.removeClass('main_border');
            $(this).addClass('main_border');
            var big_src = $(this).find('img').attr('big');
            $('.big_img').find('img').attr('src',big_src);
        });
        $('.next').click(function(){
            var index = $('.small_img').find('.main_border').index();
            small_img.eq(index).removeClass('main_border');
            if(index == small_img.size()-1){
                index = -1;
            }
            var big_src = small_img.eq(index+1).find('img').attr('big');
            $('.big_img').find('img').attr('src',big_src);
            small_img.eq(index+1).addClass('main_border');
        });
        $('.prev').click(function(){
            var index = $('.small_img').find('.main_border').index();
            small_img.eq(index).removeClass('main_border');
            if(index == 0){
                index = small_img.size();
            }
            var big_src = small_img.eq(index-1).find('img').attr('big');
            $('.big_img').find('img').attr('src',big_src);
            small_img.eq(index-1).addClass('main_border');
        });
            /*
            * 商品详情切换
            * */
        $('.pro_infor_nav span').click(function(){
            if(!$(this).hasClass('main_bg')){
                $('.pro_infor_nav span').removeClass('main_bg');
                $(this).addClass('main_bg');
            }
            $('.pro_info').hide();
            $('.pro_info').eq($.inArray(this,   $('.pro_infor_nav span'))).show();
        })
       //创建和初始化地图函数：
        function initMap(){
            createMap();//创建地图
            setMapEvent();//设置地图事件
            addMapControl();//向地图添加控件
            addMapOverlay();//向地图添加覆盖物
        }
        function createMap(){
            map = new BMap.Map("map");
            map.centerAndZoom(new BMap.Point(113.723188,34.749882),16);
        }
        function setMapEvent(){
            map.enableScrollWheelZoom();
            map.enableKeyboard();
            map.enableDragging();
            map.enableDoubleClickZoom()
        }
        function addClickHandler(target,window){
            target.addEventListener("click",function(){
                target.openInfoWindow(window);
            });
        }
        function addMapOverlay(){
            var markers = [
                {content:"郑州神恩信息技术有限公司 河南省郑州市金水区郑汴路与玉凤路凤凰城北城五楼",title:"神恩信息",imageOffset: {width:-46,height:-21},position:{lat:34.751276,lng:113.719474}}
            ];
            for(var index = 0; index < markers.length; index++ ){
                var point = new BMap.Point(markers[index].position.lng,markers[index].position.lat);
                var marker = new BMap.Marker(point,{icon:new BMap.Icon("http://api.map.baidu.com/lbsapi/createmap/images/icon.png",new BMap.Size(20,25),{
                    imageOffset: new BMap.Size(markers[index].imageOffset.width,markers[index].imageOffset.height)
                })});
                var label = new BMap.Label(markers[index].title,{offset: new BMap.Size(25,5)});
                var opts = {
                    width: 200,
                    title: markers[index].title,
                    enableMessage: false
                };
                var infoWindow = new BMap.InfoWindow(markers[index].content,opts);
                marker.setLabel(label);
                addClickHandler(marker,infoWindow);
                map.addOverlay(marker);
            };
        }
        //向地图添加控件
        function addMapControl(){
            var scaleControl = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
            scaleControl.setUnit(BMAP_UNIT_IMPERIAL);
            map.addControl(scaleControl);
            var navControl = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
            map.addControl(navControl);
            var overviewControl = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:true});
            map.addControl(overviewControl);
        }
        var map;
        initMap();
    });
})