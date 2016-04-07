/**
 * Created by Administrator on 2016/3/19.
 */
define(['validate', 'jquery', 'layer', 'Core', 'Front','app/common'], function(validate){
   $(function (){ 
      $('.search_btn').click(function(){
         var key = $('.search_key').val();
         var baseUrl = $('.logo_img').attr('href');
         if(key){
            window.location.href = baseUrl + '/query/1.html?keyword=' + key;
         }
      });
      $('.action_btn .submit').click(function(){
         var validateMsg = validate.checkFields($('#oldpassword,#newpassword,#repassword'));
         if(validateMsg.length){
            validateMsg[0].ele.focus();
             return false;
         }
         
         if($('#newpassword').val() != $('#repassword').val()){
            layer.tips('两次输入的密码不一致！', '#repassword',{
                tipsMore : true,
                tips : [2, '#63bf82']
            });
            return false;
         }

         Cntysoft.Front.callApi('User', 'resetPassword', {
            oldPassword : Cntysoft.Core.sha256($('#oldpassword').val()),
            newPassword : Cntysoft.Core.sha256($('#newpassword').val())
         }, function(response){
            if(!response.status){
               if(10013 == response.errorCode){
                  layer.msg('旧密码错误，请核对您输入的密码！', {
                     time : 1000
                  });
               }else{
                  layer.msg('密码修改失败，请稍后重试！', {
                     time : 1000
                  });
               }
            }else{
               layer.alert('密码修改成功',{
                  btn : '',
                  success : function(){
                     var redirect = function(){
                        window.location.reload();
                     };
                     setTimeout(redirect, 300);
                  }
               });
            }
         });
      });
      $('.action_btn .reset').click(function(){
         $('#oldpassword,#newpassword,#repassword').each(function(){
            $(this).val('');
         });
      });
   });
});