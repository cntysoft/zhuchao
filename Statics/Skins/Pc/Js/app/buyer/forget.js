define(['validate', 'jquery', 'layer', 'Core', 'Front','app/common'], function (validate){
    $(function (){
       $('.search_btn').click(function(){
         var key = $('.search_key').val();
         var baseUrl = $('.logo_img').attr('href');
         if(key){
            window.location.href = baseUrl + '/query/1.html?keyword=' + key;
         }
      });
        var imgCodeUrl = '/forgetchkcode?v_';
        var codeType = 2, phone = '', phoneChecked = false, phoneExist = false;
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
                            tips : [2, '#0af']
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
                    tips : [2, '#0af']
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
                } else{
                    phone = $('#phone').val();
                    phoneChecked = true;
                    $('.login_form.check_form,.login_form.success').hide();
                    $('.login_form.reset_form').show();
                }
            }, true);
        });

        $('#submit_second').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#password,#password2,#phoneAuthCode'));
            if(validateMsg.length || !phoneExist){
                return false;
            }
            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual, $('#password2'), {
                    tips : [2, '#0af']
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
                } else{
                    $('.login_form.check_form,.login_form.reset_form').hide();
                    $('.login_form.success').show();
                }
            }, true);
        });
    });
});