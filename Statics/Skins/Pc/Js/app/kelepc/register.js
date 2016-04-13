define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
   $(function (){
      var sendMessageCode = false;  //标识是否发送短信
      var checkMessageCode = false;  //标识图片验证码是否通过
      Cntysoft.Front.imgCodeUrl = '/registerchkcode?v_';
      $('#codeImg').attr('src', Cntysoft.Front.imgCodeUrl + (new Date()).getTime());
      $('#changeCodeImg').click(function (){
         $('#codeImg').attr('src', Cntysoft.Front.imgCodeUrl + (new Date()).getTime());
      });
      $('#phone,#password,#password2').blur(function (){
         validate.checkFields($(this));
      });
      $('#sendMessage').click(function (){
         var validateMsg = validate.checkFields($('#phone,#password,#password2,#imgCode'));
         if(validateMsg.length){
            validateMsg[0].ele.focus();
            return false;
         }
         if($('#password').val() != $('#password2').val()){
            layer.tips(validate.message.passwordNotEqual, $('#password2'));
            return false;
         }
         if(sendMessageCode){
            return false;
         }
         Cntysoft.Front.callApi('Provider', 'checkRegAuthCode', {
            phone : $('#phone').val(),
            code : $('#imgCode').val()
         }, function (response){
            if(response.status){
               checkMessageCode = true;
               sendMessageCode = true;
               $('#sendMessage').text('120s后重新发送!');
               var time = 120;
               var interval = setInterval(function (){
                  time -= 1;
                  if(time !== 0){
                     $('#sendMessage').text(time + 's后重新发送!');
                  } else{
                     clearInterval(interval);
                     sendMessageCode = false;
                  }
               }, 1000);
            } else{
               if(response.errorCode === 10009){
                  layer.alert('验证码过期!');
                  $('#changeCodeImg').click();
               }
               if(response.errorCode === 10008){
                  layer.alert('图片验证码错误!')
               }
               if(response.errorCode === 10013){
                  layer.alert('发送短信次数过多,请稍候注册!');
               }
               if(response.errorCode === 10001){
                  layer.confirm('手机号已注册,去登录?', {
                     yes : function (){
                        window.location.href = '/login.html';
                     }
                  });
               }
            }
         });
      });
      $('.login_auto').click(function (){
         if($(this).hasClass('checked')){
            $(this).removeClass('checked');
            $('.submit_btn').addClass('disable').attr('disabled', '').removeClass('main_bg_light');
         } else{
            $(this).addClass('checked');
            $('.submit_btn').removeClass('disable').removeAttr('disabled').addClass('main_bg_light');
         }
      });
      $('#submit').click(function (event){
         event.preventDefault();
         if(checkMessageCode === false){console.log('a')
            layer.alert('请选发送验证码到手机!');
            return false;
         }
         var validateMsg = validate.checkFields($('#phone,#password,#password2,#phoneAuthCode'));
         if(validateMsg.length){
            return false;
         }

         Cntysoft.Front.callApi('Provider', 'register', {
            phone : $('#phone').val(),
            password : Cntysoft.Core.sha256($('#password').val()),
            code : $('#phoneAuthCode').val()
         }, function (response){
            if(response.status){
               layer.alert('注册成功去登录', {
                  yes : function (){
                     window.location.href = '/login.html';
                  }
               });
            }
         });

      });
   });
});