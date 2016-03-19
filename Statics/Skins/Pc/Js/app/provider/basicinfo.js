define(['validate', 'jquery', 'Core', 'Front', 'layer'], function (validate){
    $(function (){
        $('#submit').click(function (){
            var validation = validate.checkFields($('#name,#realName,#department,#position,#email,#showPhone,#qq,#telNum,#telCountry,#telArea,#faxNum,#faxCountry,#faxArea'));
            if(validation.length){
                validation[0].ele.focus();
                layer.msg('请正确填写相应信息');
                return false;
            }
            if(validate.reg.num.test($('#name').val())){
                validate.tips('用户名不能为纯数字','#name');
            }
            var params = {};
            $.each($('#name,#realName,#department,#position,#email,#showPhone,#qq'), function (index, item){
                params[$(item).attr('id')] = $(item).val();
            });
            params.sex = getRadioValueByName('sex');
            params.tel = $('#telCountry').val() + '-' + $('#telArea').val() + '-' + $('#telNum').val();
            params.fax = $('#faxCountry').val() + '-' + $('#faxArea').val() + '-' + $('#faxNum').val();
            Cntysoft.Front.callApi('Provider', 'updateUserInfo', params, function (response){
                if(response.status){
                    layer.msg('信息更新成功');
                } else{
                    layer.alert('信息更新失败!');
                }
            });
        });
        //根据name获得radio的值
        function getRadioValueByName(name){
            var val = null;
            $.each($('input[name=' + name + ']'), function (index, item){
                if($(item).prop('checked')){
                    val = $(item).val();
                    return false;
                }
            });
            return val;
        }
    });
});