/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 */
define(['jquery', 'app/company/common'], function (){
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


