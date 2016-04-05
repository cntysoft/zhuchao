define(['validate', 'jquery', 'layer', 'Core', 'Front','app/common'], function (validate){
    $(function (){
        var imgCodeUrl = '/registerchkcode?v_';
        var codeType = 1;
        var sendMessageCode = false;
        $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        });
        $('#phone,#password,#password2,#imgCode').blur(function (){
            validate.checkFields($(this));
        });
        $('#sendMessage').click(function (){
            var $this = $(this);
            var validateMsg = validate.checkFields($('#phone,#password,#password2,#imgCode'));
            if(validateMsg.length){
                return false;
            }

            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual, $('#password2'));
                return false;
            }
            if(sendMessageCode){
                return false;
            }
            var user = $('#phone').val();
            Cntysoft.Front.callApi('User', 'checkPhoneExist', {
                phone : user
            }, function (response){
                if(!response.data[0]){
                    Cntysoft.Front.callApi('User', 'checkPicCode', {
                        phone : $('#phone').val(),
                        code : $('#imgCode').val(),
                        type : codeType
                    }, function (response){
                        if(!response.status){
                            if(10001 == response.errorCode){//过期
                                layer.alert('图片验证码已经过期！');
                            } else if(10002 == response.errorCode){//错误
                                layer.alert('图片验证码错误！');
                            } else if(10003 == response.errorCode){//发送短信失败
                                layer.alert('短信发送失败！');
                            }
                        } else{
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
                } else{
                    layer.alert('该手机号已经注册，请直接登录！');
                }
            }, this);
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
            var validateMsg = validate.checkFields($('#phone,#password,#password2,#imgCode'));
            if(validateMsg.length){
                return false;
            }
            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual, $('#password2'));
                return false;
            }

            if(!sendMessageCode){
                return false;
            }

            Cntysoft.Front.callApi('User', 'register', {
                phone : $('#phone').val(),
                password : Cntysoft.Core.sha256($('#password').val()),
                smsCode : $('#phoneAuthCode').val()
            }, function (response){
                if(!response.status){
                    if(10004 == response.errorCode){//过期
                        layer.alert('短信验证码已经过期！');
                    } else if(10005 == response.errorCode){//错误
                        layer.alert('短信验证码错误！');
                    }
                    sendMessageCode = false;
                } else{
                    layer.alert('注册成功，页面即将跳转！', {
                        btn : '',
                        success : function (){
                            var redirect = function (){
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
