/**
 * Created by  on 2016/3/16.
 */
define(['jquery', 'app/common', 'layer', 'Core', 'Front'], function (){
   $(function (){
      var $type = $('.check'), $text = $('.write_chars'), $name = $('#name');
      $type.click(function(){
         var $this = $(this);
         if(!$this.hasClass('checked')){
            $this.siblings('.checked').removeClass('checked');
            $this.addClass('checked');
         }
      });
      
      $text.blur(function(){
         if(!$text.val()){
            tips('请输入要反馈的信息！', '.write_chars');
         }
      });
      
      $name.blur(function(){
         if(!$name.val()){
            tips('请输入您的姓名，便于我们联系您', '#name');
         }
      });
      
      $('.action_btn').click(function(){
         var type = $type.filter('.checked').attr('index'), text = $text.val(), name = $name.val();
         var params = {};
         
         if(!type){
            tips('请选择反馈信息的类型！', '.class_li');
            return false;
         }
         
         if(!text){
            tips('请输入要反馈的信息！', '.write_chars');
            return false;
         }
         
         if(!name){
            tips('请输入您的姓名，便于我们联系您', '#name');
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
               layer.msg('反馈信息添加成功！', {
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
                  layer.msg('请勿频繁添加反馈，稍后再尝试添加！')
               }else{
                  layer.msg('反馈信息添加失败，请稍后再试', {
                     time : 1000
                  });
               }
            }
         });
      });
      
      function tips(msg, item)
      {
         layer.tips(msg, item, {
            tipsMore : true,
            tips : [2, '#0af'],
            time : 1000
         });
      }
   });
});