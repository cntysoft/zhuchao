/**
 * Created by wangzan on 2016/3/12.
 */
define(['jquery', 'app/company/common'], function (){
   $(function (){
      $('.icon-black-right').click(function (){
         var $this = $(this).parents('.classify_list');
         if($this.hasClass('active')){
            $this.find('i').addClass('icon-black-right').removeClass('icon-black-down');
            $this.find('ul').hide();
            $this.removeClass('active');
         } else{
            $this.find('i').removeClass('icon-black-right').addClass('icon-black-down');
            $this.find('ul').show();
            $this.addClass('active');
         }
      });

      var query = window.location.search;
      var querydata = Cntysoft.fromQueryString(query, this);
      if(querydata.group){
         var $nowgroup = $('div.classify_list a[groupdata=' + querydata.group + ']');
         var $thisgroup = $nowgroup.parents('div.classify_list');
         $thisgroup.find('i').removeClass('icon-black-right').addClass('icon-black-down');
         $thisgroup.find('ul').show();
         $thisgroup.addClass('active');
      }
      $('.rank_btn1 a').click(function (){
         if($(this).hasClass('main')){
            return;
         }
         if($(this).attr('sort')){
            querydata.sort = $(this).attr('sort');
            window.location.href = '/productlist/1.html?' + Cntysoft.toQueryString(querydata, true);
         }
      });
      $('.rank_btn1 a.price ').click(function (){
         if($(this).hasClass('up')){
            querydata.sort = $(this).attr('downsort');
            window.location.href = '/productlist/1.html?' + Cntysoft.toQueryString(querydata, true);
         } else{
            querydata.sort = $(this).attr('upsort');
            window.location.href = '/productlist/1.html?' + Cntysoft.toQueryString(querydata, true);
         }
      });
      $('.classify_list a ').click(function (){
         if($(this).attr('groupdata')){
            querydata.group = $(this).attr('groupdata');
            window.location.href = '/productlist/1.html?' + Cntysoft.toQueryString(querydata, true);
         }
      });
   });
});