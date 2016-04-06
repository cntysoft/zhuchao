/**
 * Created by Administrator on 2016/3/17.
 */
define(['zepto', 'module/company_classify', 'module/totop', 'Front'], function (){
   $(function (){
      if($('.l_main').attr('newsid')){
         Cntysoft.Front.callApi('Utils', 'addArticleHits',
         {
            id : $('.l_main').attr('newsid')
         }, function (response){
         }
         , this);
      }
   });
});