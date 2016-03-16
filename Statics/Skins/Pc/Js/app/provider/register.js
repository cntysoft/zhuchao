define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
    $(function (){
        var sendMessageCode = false;
        $('#codeImg').attr('src', Cntysoft.Front.imgCodeUrl + (new Date()).getTime());
        $('#changeCodeImg').click(function (){
            $('#codeImg').attr('src', Cntysoft.Front.imgCodeUrl + (new Date()).getTime());
        });
        $('#sendMessage').click(function (){
            var validateMsg = validate.checkFields($('#phone,#password,#password2,#imgCode'));
            if(validateMsg.length){
                $.each(validateMsg, function (index, item){
                    layer.tips(item.msg,item.ele,{
                        tipsMore:true
                    });
                });
                return false;
            }
            if($('#password').val() != $('#password2').val()){
                layer.tips(validate.message.passwordNotEqual,$('#password2'));
                return false;
            }
            if(sendMessageCode){
                return false;
            }
            Cntysoft.Front.callApi('','',{},function(){
                
            });
        });
        $('#submit').click(function (event){
            event.preventDefault();

        });
    });
});