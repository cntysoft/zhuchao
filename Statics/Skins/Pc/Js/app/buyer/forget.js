define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
    $(function (){
       var imgCodeUrl = '/forgetchkcode?v_';
       var codeType = 2, phone = '', phoneChecked = false;
        var sendMessageCode = false;
        $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        });
        $('#sendMessage').click(function(){
            var $this = $(this);
            var validateMsg = validate.checkFields($('#phone,#imgCode'));
            if(validateMsg.length){
                return false;
            }
            if(sendMessageCode){
                return false;
            }
            Cntysoft.Front.callApi('User', 'checkPicCode', {
               phone : $('#phone').val(),
               code : $('#imgCode').val(),
               type : codeType
            }, function(response){
               if(!response.status){
                  if(10001 == response.errorCode){//过期
                     layer.alert('图片验证码已经过期！');
                  }else if(10002 == response.errorCode){//错误
                     layer.alert('图片验证码错误！');
                  }else if(10003 == response.errorCode){//发送短信失败
                     layer.alert('短信发送失败！');
                  }
               }else{
                  sendMessageCode = true;
                  $this.html('重新发送(120)');
                  var n = 120;
                  setTime = setInterval(function (){
                     n -= 1;
                     $this.html('重新发送(' + n + ')');
                     if(n == 0){
                        clearInterval(setTime);
                        $this.html('重新发送');
                        sendMessageCode = false;
                     }
                  }, 1000);
               }
            });
        });

        $('#submit_first').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#phone,#imgCode'));
            if(validateMsg.length){
                return false;
            }
            
            if(!sendMessageCode){
               return false;
            }
            
            Cntysoft.Front.callApi('User', 'checkSmsCode', {
               phone : $('#phone').val(),
               code : $('#phoneAuthCode').val(),
               type : codeType
            }, function(response){
               if(!response.status){
                  if(10004 == response.errorCode){//过期
                     layer.alert('短信验证码已经过期！');
                  }else if(10005 == response.errorCode){//错误
                     layer.alert('短信验证码错误！');
                  }
                  sendMessageCode = false;
               }else{
                  phone = $('#phone').val();
                  phoneChecked = true;
                  $('.login_form.check_form').hide();
                  $('.login_form.reset_form').show();
               }
            });
        });
        
        $('#submit_second').click(function(event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#password', '#password2'));
            if(validateMsg.length){
                return false;
            }
            
            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual,$('#password2'));
                return false;
            }
            
            if(!phoneChecked){
               layer.alert('请重新验证手机号码！');
               return false;
            }
            
            Cntysoft.Front.callApi('User', 'findPassword', {
               phone : phone,
               password : Cntysoft.Core.sha256($('#password').val())
            }, function(response){
               if(!response.status){
                  if(10011 == response.errorCode){//不存在
                     layer.alert('手机号码错误，请重新验证！');
                  }
                  sendMessageCode = false;
               }else{
                  layer.alert('成功找回密码，即将跳转到登陆页面！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location = '/login.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
        });
    });
});