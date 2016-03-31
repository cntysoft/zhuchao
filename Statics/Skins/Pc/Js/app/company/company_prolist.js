/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery', 'app/company/common'], function (){
   $(function (){
//      $('.classify_list h2').click(function (){
//         if($(this).hasClass('active')){
//            $(this).find('i').addClass('icon-black-right').removeClass('icon-black-down');
//            $(this).next().hide();
//            $(this).removeClass('active');
//         } else{
//            $(this).find('i').removeClass('icon-black-right').addClass('icon-black-down');
//            $(this).next().show();
//            $(this).addClass('active');
//         }
//      });

      var query = window.location.search;
      if(query.length){
         query += '&';
      } else{
         query += '?';
      }
      query = query.split('sort');
      $('.rank_btn1 a').click(function (){
         if($(this).hasClass('main')){
            return;
         }
         if($(this).attr('sort')){
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('sort');
         }
      });
      $('.rank_btn1 a.price ').click(function (){
         if($(this).hasClass('up')){
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('downsort');
         } else{
            window.location.href = window.location.pathname + query[0] + 'sort=' + $(this).attr('upsort');
         }
      });
   });
});