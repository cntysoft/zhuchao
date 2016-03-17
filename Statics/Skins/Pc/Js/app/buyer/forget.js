define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
    $(function (){
        var imgCodeUrl = '/forgetchkcode?v_';
        var codeType = 2, phone = '', phoneChecked = false, phoneExist = false;
        var sendMessageCode = false;
        $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', imgCodeUrl + (new Date()).getTime());
        });

        $('#phone').change(function (){
            var validation = validate.checkFields($('#phone'));

            if(!validation.length){
                Cntysoft.Front.callApi('User', 'checkPhoneExist', {
                    phone : $('#phone').val()
                }, function (response){
                    if(response.status && response.data[0] === false){
                        layer.tips(validate.message.phoneNotExist, $('#phone'), {
                            tips : [2, '#63bf82']
                        });
                        phoneExist = false;
                    } else{
                        phoneExist = true;
                    }
                });
            }
        });
        $('#submit_first').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#phone,#imgCode'));
            if(validateMsg.length){
                return false;
            }
            if(!phoneExist){
                layer.tips(validate.message.phoneNotExist, $('#phone'), {
                    tips : [2, '#63bf82']
                });
                return false;
            }
            Cntysoft.Front.callApi('User', 'checkPicCode', {
                code : $('#imgCode').val(),
                type : codeType,
                phone : $('#phone').val()
            }, function (response){
                if(!response.status){
                    if(10002 == response.errorCode){
                        layer.alert(validate.message.imgCodeError);
                        $('#changeCodeImg').click();
                    } else if(10001 == response.errorCode){//错误
                        layer.alert(validate.message.imgCodeExpire);
                    }
                    sendMessageCode = false;
                } else{
                    phone = $('#phone').val();
                    phoneChecked = true;
                    $('.login_form.check_form,.login_form.success').hide();
                    $('.login_form.reset_form').show();
                }
            });
        });

        $('#submit_second').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#password,#password2,#phoneAuthCode'));
            if(validateMsg.length || !phoneExit){
                return false;
            }
            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual, $('#password2'), {
                    tips : [2, '#63bf82']
                });
                return false;
            }

            if(!phoneChecked){
                layer.alert('请先验证手机号！');
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
                    sendMessageCode = false;
                } else{
                    $('.login_form.check_form,.login_form.reset_form').hide();
                    $('.login_form.success').show();
                }
            });
        });
    });
});