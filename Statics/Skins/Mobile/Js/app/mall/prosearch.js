/**
 * Created by jiayin on 2016/3/16.
 */
define(['zepto'], function (){
    $(function (){
        //导航
        $('.header_right').tap(function (){
            var that = $('.top_nav_box');
            if(that.hasClass('in')){
                $(that).removeClass('in');
                return false;
            } else{
                $(that).addClass('in');
                return false;
            }

        });

        var search = decodeURI(window.location.search);
        if(search){
            var params = search.substring(1);
            var cond = params.split('&');
            $.each(cond, function (index, item){
                var arr = item.split('=');
                var id = arr[0];
                var data = arr[1];
                if(('keyword' == id)){
                    if(data){
                        $('.text_ellipsis').text(data);
                    } else{
                        $('.text_ellipsis').text('全部');
                    }
                }
            });
            addSort(cond);
            addQuery(cond);
        }
        function redirectUrl(){
            var cond = {};
            var query = '?';
            if(search){
                var params = search.substring(1);
                var condition = params.split('&');
                $.each(condition, function (index, item){
                    var arr = item.split('=');
                    var id = arr[0];
                    var data = arr[1];
                    if(data && ('keyword' == id)){
                        cond[id] = data;
                    }
                });
            }
            $('.shaixuan_nav li.maincolor').each(function (){
                var item = $(this);
                cond[item.attr('id')] = item.attr('data');
            });
            $('#mallSearchNav li').each(function (){
                if($(this).hasClass('maincolor')){
                    cond['sort'] = $('#mallSearchNav li.maincolor').attr('id') + '_' + $('#mallSearchNav li.maincolor').attr('sort');
                }
            });
            $.each(cond, function (index, item){
                query += index + '=' + item + '&';
            });
            query = encodeURI(query.substring(0, query.length - 1));
            if(!query){
                window.location.href = location.protocol + '//' + location.hostname + location.pathname;
            } else{
                window.location.href = location.protocol + '//' + location.hostname + location.pathname + query;
            }
        }
        $('#mallSearchNav li').tap(function (){
            var $this = $(this);
            if($this.attr('id') == '1' || $this.attr('id') == '2'){
                if(!$this.hasClass('maincolor')){
                    $this.siblings('.maincolor').removeClass('maincolor');
                    $this.addClass('maincolor');
                    $this.attr('sort', 1);
                }
                redirectUrl();
            }
            if($this.attr('id') == '4'){
                $this.siblings('.maincolor').removeClass('maincolor');
                $this.addClass('maincolor');
                if($('.sanjiao').hasClass('current')){
                    $this.find('.sanjiao').toggleClass('current');
                }
                if($('.up_sanjiao').hasClass('current')){
                    $this.attr('sort', 1);
                }
                if($('.down_sanjiao').hasClass('current')){
                    $this.attr('sort', 2);
                }
                redirectUrl();
            }
        });
        function addQuery(params){
            $.each(params, function (index, item){
                var arr = item.split('=');
                var id = arr[0];
                var data = arr[1];
                $.each($('#' + id), function (){
                    var span = $(this);
                    if(span.attr('data') == data){
                        span.addClass('maincolor');
                    }
                });
            });
        }
        function addSort(params){
            $.each(params, function (index, item){
                var arr = item.split('=');
                var id = arr[0];
                var data = arr[1];
                if(id == 'sort'){
                    var item = data.split('_');
                    if(item[0] == '4'){
                        if(item[1] == '1'){
                            $('.up_sanjiao').addClass('current')
                            $('.down_sanjiao').removeClass('current')
                        }
                        if(item[1] == '2'){
                            $('.down_sanjiao').addClass('current')
                            $('.up_sanjiao').removeClass('current')
                        }
                    } else{
                        $('#' + item[0]).attr('sort', item[1]);
                        $('#' + item[0]).addClass('maincolor');
                    }
                }
            });
        }
        //点击筛选
        $('#shaixuan').tap(function (){
            $('#shaixuanBox').removeClass('hide');
            $('html,body').css({'height' : '100%', 'overflow' : 'hidden'});
        });
        //点击取消
        $('.cancel').tap(function (){
            $('#shaixuanListNav').find('.choose_text').text('不限');
            $('.shaixuan_item').find('li.maincolor').removeClass('maincolor');
            $('#shaixuanBox').addClass('hide');
            $('html,body').css({'height' : 'auto', 'overflow' : 'visible'});
        });
        //点击保存
        $('.save').tap(function (){
            $('#shaixuanBox').addClass('hide');
            $('html,body').css({'height' : 'auto', 'overflow' : 'visible'});
            redirectUrl();
        });
        //点击条件
        $('.shaixuan_item .module_text_nav li').tap(function (){
            var index = $(this).closest('.shaixuan_item').index();
            $(this).addClass('maincolor').siblings().removeClass('maincolor');
            var textValue = $(this).children('a').text();
            $('#shaixuanListBox').hide();
            $('#shaixuanIndex').show();
            $('#shaixuanListNav li').eq(index).find('span').text(textValue);
        });
        //点击分类
        $('#shaixuanListNav li').tap(function (){
            var indexA = $(this).index();
            $('#shaixuanIndex').hide();
            $('#shaixuanListBox').show();
            $('#shaixuanListBox').find('.shaixuan_item').hide().eq(indexA).show();
        });

        //清除选择
        $('.submit_btn').tap(function (){
            $('#shaixuanListNav').find('.choose_text').text('不限');
            $('.shaixuan_item').find('li.maincolor').removeClass('maincolor');
        });
        //返回按钮
        $('#shaixuanListBox .goback').tap(function (){
            $('#shaixuanListBox').hide();
            $('#shaixuanIndex').show();
        });
    });
});