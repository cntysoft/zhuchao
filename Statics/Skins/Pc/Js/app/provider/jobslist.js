define(['app/common', 'validate', 'webuploader', 'Core', 'Front','app/common'], function (common){
    $(function (){
        common.createPage(getPageUrl);

        function getPageUrl(page){
            var baseUrl = '/site/job/1.html';
            return baseUrl.replace('1', page);
        }
    });
});