define(['validate', 'jquery', 'Core', 'Front', 'layer'], function (validate){
    $(function (){
        var sendMessageCode = false;  //标识是否发送短信
        var checkMessageCode = false;  //标识图片验证码是否通过
        var forgetImgCodeUrl = '/forgetchkcode?v_'
        $('#codeImg').attr('src', forgetImgCodeUrl + (new Date()).getTime());
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', forgetImgCodeUrl + (new Date()).getTime());
        });
        $('#phone,#password,#password2').blur(function (){
            validate.checkFields($(this));
        });
        $('#sendMessage').click(function (event){
            event.preventDefault();
            if(sendMessageCode){
                return false;
            }
            if(validate.checkFields($('#phone,#imgCode')).length){
                return false;
            }
            
            Cntysoft.Front.callApi('Provider', '', {
                phone:$('#phone').val(),
                code:$('#imgCode').val()
            }, function (response){
                if(response.status){

                } else{
                    
                }
            });
        });
    });
});