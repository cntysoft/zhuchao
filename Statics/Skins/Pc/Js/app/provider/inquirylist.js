define(['app/common','jquery'],function(common){
    $(function(){
        var sendQuery = window.location.search;
        sendQuery = Cntysoft.fromQueryString(sendQuery, this);
       common.createPage(getPageUrl);
       
       function getPageUrl(page){
           var baseUrl = '/inquiry/1.html';
           var status = 1;
           if(sendQuery.hasOwnProperty('status')){
               status = sendQuery['status'];
           }
           return baseUrl.replace('1',page)+'?status='+status;
       }
    });
});