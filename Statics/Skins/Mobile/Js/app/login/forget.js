define(['validate', 'zepto', 'layer', 'Core', 'Front'], function (validate){
   $(function (){
      var imgCodeUrl = '/forgetchkcode?v_';
      var codeType = 2, phone = '', phoneChecked = false, phoneExist = false;
      var regPhone = new RegExp(/^(1[0-9]{10})$/), regImage = new RegExp(/^[\w]{4}$/);
      $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
      $('#codeImg').tap(function (){
         $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
      });

      $('#phone').change(function (){
         var val = $(this).val();
         if(val && regPhone.test(val)){
            Cntysoft.Front.callApi('User', 'checkPhoneExist', {
               phone : val
            }, function (response){
               if(response.status && response.data[0] === false){
                  layer.open({
                     content : '输入的手机号码不存在！',
                     time : 1
                  });
                  phoneExist = false;
               } else{
                  phoneExist = true;
               }
            });
         }
      });
      $('#submit_first').tap(function (event){
         event.preventDefault();
         var imageCode = $('#imgCode').val();
         phone = $('#phone').val();
         if(!phone || !regPhone.test(phone)){
            layer.open({
               content : '请输入正确的手机号码！',
               time : 1
            });
            return false;
         }
         if(!imageCode || !regImage.test(imageCode)){
            layer.open({
               content : '请输入正确的图片验证码！',
               time : 1
            });
            return false;
         }
         if(!phoneExist){
            layer.open({
               content : '手机号码不存在！',
               time : 1
            });
            return false;
         }
         Cntysoft.Front.callApi('User', 'checkPicCode', {
            code : imageCode,
            type : codeType,
            phone : phone
         }, function (response){
            if(!response.status){
               if(10002 == response.errorCode){
                  layer.open({
                     content : '图片验证码错误！',
                     time : 1
                  });
                  $('#changeCodeImg').tap();
               } else if(10001 == response.errorCode){//错误
                  layer.open({
                     content : '图片验证码已过期！',
                     time : 1
                  });
               }
            } else{
               phoneChecked = true;
               $('.check_form,.success').hide();
               $('.reset_form').show();
            }
         }, true);
      });

      $('#submit_second').tap(function (event){
         event.preventDefault();
         var validateMsg = validate.checkFields($('#password,#password2,#phoneAuthCode'));
         if(validateMsg.length || !phoneExist){
            return false;
         }
         if($('#password').val() != $('#password2').val()){
            layer.open({
               content : validate.message.passwordNotEqual,
               time : 1
            });
            return false;
         }

         if(!phoneChecked){
            layer.open({
               content : '请先验证手机号！',
               time : 1
            });
            return false;
         }

         Cntysoft.Front.callApi('User', 'findPassword', {
            phone : phone,
            password : Cntysoft.Core.sha256($('#password').val()),
            code : $('#phoneAuthCode').val()
         }, function (response){
            if(!response.status){
               if(10011 == response.errorCode){//不存在
                  layer.alert('手机号码错误，请重新验证！');
               }
            } else{
               $('.check_form,.reset_form').hide();
               $('.success').show();
            }
         }, true);
      });
   });
});