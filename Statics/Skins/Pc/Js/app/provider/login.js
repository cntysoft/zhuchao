define(['validate', 'jquery', 'Core', 'Front', 'layer','app/common'], function (validate){
    $(function (){
        var checkArea = '#name,#password';
        var checkImgCode = false;
        Cntysoft.Front.loginImgCodeUrl = '/loginchkcode?v_';
        $('#submit').click(function (event){
            event.preventDefault();
            var validation = validate.checkFields($(checkArea));
            if(validation.length){
                return false;
            }
            var type = 1;
            var name = $('#name').val();
            if(validate.reg.phone.test(name)){
                type = 2;
            }
            var params = {
                key : name,
                password : Cntysoft.Core.sha256($('#password').val()),
                remember : $('#remember').hasClass('icon-checked'),
                type : type
            };
            if(checkImgCode){
                params.code = $('#imgCode').val();
            }
            Cntysoft.Front.callApi('Provider', 'login', params, function (response){
                if(response.status){
                    window.location.reload();
                }
                else{
                    if(response.errorCode === 10009){
                        layer.alert('验证码过期!');
                        $('#changeCodeImg').click();
                    }
                    if(response.errorCode === 10008){
                        layer.alert('图片验证码错误!')
                    }
                    if(response.errorCode === 10005){
                        layer.alert('用户名或密码错误!');
                    }
                    if(response.errorCode === 10003){
                        layer.alert('用户名或密码错误!');
                        checkArea += '#imgCode';
                        $('#changeCodeImg').click();
                        checkImgCode = true;
                        $('.input_code').show().next('.error_tip').show();
                    }
                    if(response.errorCode === 10004){
                        layer.alert('帐号不存在!');
                    }

                }
            });
        });
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', Cntysoft.Front.loginImgCodeUrl + (new Date()).getTime());
        });
        $('#remember').click(function (){
            $(this).toggleClass('icon-checked');
        });
    });
});