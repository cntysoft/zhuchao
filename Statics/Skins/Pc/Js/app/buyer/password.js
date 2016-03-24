/**
 * Created by Administrator on 2016/3/19.
 */
define(['validate', 'jquery', 'layer', 'Core', 'Front', 'search','app/common'], function(validate){
   $(function (){ 
      $('.action_btn .submit').click(function(){
         var validateMsg = validate.checkFields($('#oldpassword,#newpassword,#repassword'));
         if(validateMsg.length){
            validateMsg[0].ele.focus();
             return false;
         }

         Cntysoft.Front.callApi('User', 'resetPassword', {
            oldPassword : Cntysoft.Core.sha256($('#oldpassword').val()),
            newPassword : Cntysoft.Core.sha256($('#newpassword').val())
         }, function(response){
            if(!response.status){
               if(10013 == response.errorCode){
                  layer.alert('旧密码错误，请核对您输入的密码！');
               }else{
                  layer.alert('密码修改失败，请稍后重试！');
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
   });
});