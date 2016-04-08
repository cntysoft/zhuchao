/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/mall_nav', 'module/totop', 'Front'], function (){
   $(function (){
      if($('.main_content').attr('newsid')){
         Cntysoft.Front.callApi('Utils', 'addArticleHits',
         {
            id : $('.main_content').attr('newsid')
         }, function (response){
         }
         , this);
      }
   });
});