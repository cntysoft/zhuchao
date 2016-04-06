/**
 * Created by jiayin on 2016/3/18.
 */
define(['zepto', 'module/totop', 'Core', 'Front'], function (){
   $(function (){
      if($('.main_content').attr('article')){
         Cntysoft.Front.callApi('Utils', 'addArticleHits',
         {
            id : $('.main_content').attr('article')
         }, function (response){
         }
         , this);
      }
   });
});