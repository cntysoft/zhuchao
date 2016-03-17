define(['validate', 'jquery', 'Core', 'Front', 'layer'], function (validate){
    $(function (){
        var curPhone = null, phoneExist = false;
        Cntysoft.Front.forgetCodeUrl = '/forgetchkcode?v_';
        $('#codeImg').attr('src', Cntysoft.Front.forgetCodeUrl + (new Date()).getTime());

        $('#username').change(function (){
            var validation = validate.checkFields($('#username'));

            if(!validation.length){
                Cntysoft.Front.callApi('Provider', 'checkPhoneExist', {
                    phone : $('#username').val()
                }, function (response){
                    if(response.status && response.data[0] === false){
                        layer.tips(validate.message.phoneNotExist, $('#username'), {
                            tips : [2, '#63bf82']
                        });
                        phoneExist = false;
                    } else{
                        phoneExist = true;
                    }
                });
            }
        });

        //第一步，发送短信验证
        $('#nameFormSubmit').click(function (event){
            event.preventDefault();
            var checkArea = '#username,#imgCode';
            var validation = validate.checkFields($(checkArea));
            if(validation.length){
                return false;
            }
            if(!phoneExist){
                layer.tips(validate.message.phoneNotExist, $('#username'), {
                    tips : [2, '#63bf82']
                });
                return false;
            }
            var name = $('#username').val();
            var chkcode = $('#imgCode').val();
            var params = {
                phone : name,
                chkcode : chkcode
            };
            Cntysoft.Front.callApi('Provider', 'checkForgetAuthCode', params, function (response){
                if(response.status){
                    showForm('#passwordForm');
                    curPhone = name;
                } else{
                    if(response.errorCode === 10009){
                        layer.alert('验证码过期!');
                    }
                    if(response.errorCode === 10008){
                        layer.alert('图片验证码错误!');
                    }
                    if(response.errorCode === 10001){
                        layer.alert('帐号不存在!');
                    }
                    $('#changeCodeImg').click();
                }
            });
        });

        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', Cntysoft.Front.forgetCodeUrl + (new Date()).getTime());
        });

        //第二步，输入密码
        $('#passwordFormSubmit').click(function (event){
            event.preventDefault();
            var checkArea = '#phoneAuthCode,#password,#passwordAgain';
            var validation = validate.checkFields($(checkArea));
            if(validation.length){
                return false;
            }

            var code = $('#phoneAuthCode').val();
            var password = $('#password').val();
            var repassword = $('#passwordAgain').val();
            if(password !== repassword){
                layer.tips('两次密码输入不一致', '#passwordAgain', {
                    tipsMore : true,
                    tips : [2, '#63bf82']
                });
                return false;
            }
            var params = {
                code : code,
                password : Cntysoft.Core.sha256(password),
                phone : curPhone
            };
            Cntysoft.Front.callApi('Provider', 'resetPasswordWithCode', params, function (response){
                if(response.status){
                    showForm('#successForm');
                } else{
                    if(response.errorCode === 10010){
                        layer.alert('短信验证码过期!');
                    }
                    if(response.errorCode === 10011){
                        layer.alert('短信验证码错误!');
                    }
                    if(response.errorCode === 10004){
                        layer.alert('帐号不存在!');
                    }
                }
            });
        });


        //显示指定的面板
        function showForm(form)
        {
            $('#nameForm,#passwordForm,#successForm').hide();
            $(form).show();
        }
    });
});