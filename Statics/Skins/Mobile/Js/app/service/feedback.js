/**
 * Created by  on 2016/3/16.
 */
define(['zepto', 'layer', 'Core', 'Front'], function (){
   $(function (){
      var $type = $('.feedback_box li'), $text = $('.write_chars'), $name = $('#name');
      $type.tap(function(){
         var $this = $(this);
         if(!$this.hasClass('main_bg')){
            $this.siblings('.main_bg').removeClass('main_bg');
            $this.addClass('main_bg');
         }
      });

      $('.submit_btn').click(function(){
         var type = $type.filter('.main_bg').attr('index'), text = $text.val(), name = $name.val();
         var params = {};
         
         if(!type){
            msg('请选择反馈信息的类型！');
            return false;
         }
         
         if(!text){
            msg('请输入要反馈的信息！');
            return false;
         }
         
         if(!name){
            msg('请输入您的姓名，便于我们联系您');
            return false;
         }
         
         params.type = type;
         params.text = text;
         params.name = name;
         
         if($('#email').val()){
            params.email = $('#email').val();
         }
         if($('#phone').val()){
            params.phone = $('#phone').val();
         }
         if($('#qq').val()){
            params.qq = $('#qq').val();
         }
         
         Cntysoft.Front.callApi('Service', 'addFeedback', params, function(response){
            if(response.status){
               layer.open({
                  content : '反馈信息添加成功！',
                  success : function (){
                      var redirect = function (){
                          var query = Cntysoft.fromQueryString(window.location.search, true);
                          if(query.returnUrl){
                              window.location = query.returnUrl;
                          } else if(query.from){
                              window.location = query.from;
                          } else{
                              window.location = '/';
                          }
                      };
                      setTimeout(redirect, 1000);
                  }
              }); 
            }else{
               if(10001 == response.errorCode){
                  msg('请勿频繁添加反馈，稍后再尝试添加！');
               }else{
                  msg('反馈信息添加失败，请稍后再试', {
                     time : 1000
                  });
               }
            }
         });
      });
       
      function msg(msg)
      {
         layer.open({
            content : msg,
            time : 1
         });
      }
   });
});