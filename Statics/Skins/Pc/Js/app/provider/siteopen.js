/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'jquery', 'Core', 'Front', 'app/common'], function (validate){
   $(function (){
      $('#submit').click(function (){
         var validateMsg = validate.checkFields($('#subattr'));
         if(validateMsg.length){
            return false;
         }
         $('.action_btn').addClass('opening');
         Cntysoft.Front.callApi('Provider', 'openSite', {
            subAttr : $('#subattr').val()
         }, function (response){
            if(response.status){
               layer.msg('站点创建成功!', {
                  time : 2000
               }, function (){
                  $('.action_btn').removeClass('opening');
                  window.location.reload();
               });
            } else{
               var errorCode = response.errorCode;
               if(10002 == errorCode){
                  layer.confirm('开通店铺前需要先填写企业信息？', {
                     btn : ['前往填写', '暂不开通']
                  }, function (){
                     window.location.href = '/account/company.html';
                  });
               } else if(10003 == errorCode){
                  layer.msg('域名已经存在');
               }

               return false;
            }
         });
      });
      window.onbeforeunload = function (){
         var n = window.event.screenX - window.screenLeft;
         var b = n > document.documentElement.scrollWidth - 20;
         if(b && window.event.clientY < 0 || window.event.altKey){
            if($('.action_btn').hasClass('opening')){
               return '你正在开通店铺,请不要关闭页面！';
            }
         } else{
            if($('.action_btn').hasClass('opening')){
               return '你正在开通店铺,请不要刷新页面！';
            }
         }
      };
   });
});