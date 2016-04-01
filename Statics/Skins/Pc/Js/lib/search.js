/*
 * Cntysoft Cloud Software Team
 * 
 * @author Shuai <ln6265431@163.com>
 * @copyright  Copyright (c) 2010-2015 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   http://www.cntysoft.com/license/new-bsd     New BSD License
 */
define(['jquery', 'Front', 'Core'], function ($){
    $(function (){
        var getSuggest = false;
        var searchValue = '';
        var getSuggest = false;
        var $search = $('.search_key');
        var $searchSuggest = $('#searchSuggest');
        var searchValue = $search.val();
        var currentItem = 0;
        var baseUrl = '';
        //检测enter键
        $(".search_key").keyup(
         function (event){
             if(13 == event.keyCode && $(this).val()){
                 var key = $(this).val();
                 window.location.href = getUrl() + Cntysoft.toQueryString({'keyword' : key}, true);
             }
         }
        );

        //检测enter键搜索
        $('.search_button').click(function (){
            if($(".search_key").val() !== ""){
                var key = $(".search_key").val();
                window.location.href = getUrl() + Cntysoft.toQueryString({'keyword' : key}, true);
            }
        });

        $('.search_key').focusin(function (){
            getSuggest = true;
            $searchSuggest.find('a').removeClass('current_item');
            sendSuggestion(searchValue);
        });

        $search.click(function (event){
            getSuggest = true;
            $searchSuggest.find('a').removeClass('current_item');
            if(window.event){
                event.cancelBubble = true;
            }
            if(event.preventDefault){
                event.stopPropagation();
            }
        });

        $(window).scroll(function (){
            getSuggest = false;
            $searchSuggest.hide();
        });
        $('.search_key').keyup(function (event){
            if(event.keyCode === 38 || event.keyCode === 40){
                return false;
            }
            $searchSuggest.find('a').removeClass('current_item');
            if($search.val() !== searchValue){
                searchValue = $search.val();
                sendSuggestion(searchValue);
            }
            searchValue = $search.val();
            $search.attr('text', searchValue);
        });

        $search.keydown(function (event){
            getSuggest = false;
            if(event.keyCode === 38 || event.keyCode === 40){
                var $items = $searchSuggest.find('a');
                if($searchSuggest.is(':visible') && $items.length && event.keyCode === 40){
                    currentItem = $items.index($items.filter('.current_item'));
                    currentItem = currentItem + 1 > $items.length ? 0 : currentItem + 1;
                    $items.removeClass('current_item');
                    $items.eq(currentItem).addClass('current_item');
                    $search.val($items.eq(currentItem).html() ? $items.eq(currentItem).html() : $search.attr('text'));
                }
                else if($searchSuggest.is(':visible') && $items.length && event.keyCode === 38){
                    currentItem = $items.index($items.filter('.current_item'));
                    currentItem = currentItem - 1;

                    currentItem = currentItem === -2 ? $items.length - 1 : currentItem;
                    currentItem = currentItem === -1 ? $items.length : currentItem;
                    $items.removeClass('current_item');
                    $items.eq(currentItem).addClass('current_item');
                    $search.val($items.eq(currentItem).html() ? $items.eq(currentItem).html() : $search.attr('text'));
                }
                return false;
            }
        });

        //获得search处理url
        function getUrl(){
            var url = baseUrl + '/query/1.html?';
            return url;
        }

        //获得提示处理url
        function getSuggestUrl(){
            var url = 'getGoodsQuerySuggests';
            return url;
        }

        //发送ajax获得提示信息
        function sendSuggestion(searchValue){
            if(searchValue === ''){
                return;
            }
            Cntysoft.Front.callApi('User', getSuggestUrl(), {
                query : searchValue
            }, function (response){
                if(response.status){
                    var out = '';
                    var suggestions = response.data.suggestions;
                    $.each(suggestions, function (index){
                        out += '<a>' + suggestions[index].suggestion + '</a>';
                    });
                    $($searchSuggest).empty();
                    $($searchSuggest).append(out);
                    $searchSuggest.find('a').click(function (event){
                        if(window.event){
                            event.cancelBubble = true;
                        }
                        if(event.preventDefault){
                            event.stopPropagation();
                        }
                        window.location.href = getUrl() + Cntysoft.toQueryString({keyword : $(this).html()}, true);
                    });
                    if($($searchSuggest).find('a').length){
                        $searchSuggest.removeClass('hide');
                        $searchSuggest.addClass('current');
                    } else{
                     $searchSuggest.removeClass('current');
                     $searchSuggest.addClass('hide');
                    }
                }
            });
        }
    });
});