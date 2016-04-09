/**
 * Created by wangzan on 2016/4/5.
 */
define(['jquery', 'module/share', 'Front', 'app/common','search'], function (){
   $(document).ready(function (){
      var url = window.location.pathname.split('/');
      if(url[1] == 'article'){
         var itemId = url[2].split('.')[0];
         Cntysoft.Front.callApi('Utils', 'addArticleHits', {
            id : itemId
         }, function (response){
         }, this);
      }
   });
});
