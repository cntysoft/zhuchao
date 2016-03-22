define(['exports', 'jquery', 'Front', 'Core', 'layer', 'lazyload'], function (exports, COOKIE){
    $(function (){
        //实例化页码
        function createPage(getPageUrl){
            if(window.pageInfo == undefined || pageInfo.pageSize >= pageInfo.total){
                return false;
            }
            var out = '<p>';
            pageInfo.pageNum = Math.ceil(pageInfo.total / pageInfo.pageSize);
            if(pageInfo.currentPage != 1){
                out += '<a class = "prev_page" href = "' + getPageUrl(parseInt(pageInfo.currentPage) - 1) + '" > 上一页 </a>';
                if(pageInfo.pageNum < 7){
                    for(var i = 1; i <= pageInfo.pageNum; i++) {
                        var cls = '';
                        if(pageInfo.currentPage == i){
                            cls = 'main_bg';
                            out += '<a class = "' + cls + '">' + i + '</a>';
                        } else{
                            out += '<a class = "' + cls + '" href = "' + getPageUrl(i) + '" >' + i + '</a>';
                        }
                    }
                }
                else{
                    var endPage = Math.min(pageInfo.currentPage + 5, pageInfo.pageNum);
                    var startPage = endPage - 6;
                    for(var i = startPage; i <= endPage; i++) {
                        var cls = '';
                        if(pageInfo.currentPage == i){
                            cls = 'main_bg';
                            out += '<a class ="' + cls + '">' + i + '</a>';
                        } else{
                            out += '<a class = "' + cls + '" href = "' + getPageUrl(i) + '" >' + i + '</a>';
                        }
                    }
                }
            } else{
                out += '<a class="prev_page"> 上一页 </a>';
                var showPage = Math.min(pageInfo.pageNum, 6);
                for(var i = 1; i <= showPage; i++) {
                    var cls = '';
                    if(pageInfo.currentPage == i){
                        cls = 'main_bg';
                        out += '<a class ="' + cls + '">' + i + '</a>';
                    } else{
                        out += '<a class = "' + cls + '" href = "' + getPageUrl(i) + '" >' + i + '</a>';
                    }
                }
            }
            if(pageInfo.currentPage < pageInfo.pageNum){
                out += '<a class = "next_page no_marginR" href = "' + getPageUrl(parseInt(pageInfo.currentPage) + 1) + '" > 下一页 </a>';
            } else{
                out += '<a class = "next_page no_marginR"> 下一页 </a>';
            }
            out += '<span> &nbsp; 共' + pageInfo.pageNum + '页 &nbsp; 到第&nbsp; </span>';
            out += '<input><span>&nbsp; 页 </span><span class = "page_submit mainbg_hover"> 确认 </span></p>';
            $('.page_list').append(out);
            $('.page_submit').click(function (){
                var page = parseInt($(this).siblings('input').val());
                if(page > pageInfo.pageNum){
                    layer.alert('超出最大页码数量!');
                    return false;
                } else if(page != page){
                    layer.alert('请输入正确的页码!');
                    return false;
                }
                window.location = getPageUrl(page);
            });
        }
        exports.createPage = createPage;
        //延迟加载
        $(".lazy").lazyload({
            threshold : 10,
            effect : "fadeIn",
            placeholder : "/Statics/Skins/Images/pc/lazyicon.png"
        });
    });
});
