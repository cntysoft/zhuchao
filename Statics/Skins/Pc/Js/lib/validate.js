define(['exports', 'jquery', 'layer'], function (exports){
    $(function (){
        var reg = {
            email : /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
            phone : /^(1[0-9]{10})$/,
            tel : /[1-9][0-9]{6,7}/,
            fax : /[1-9][0-9]{6,7}/,
            qq : /^[1-9][0-9]{4,10}$/,
            nickname : /^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[a-zA-Z0-9]){3,8}$/,
            name : /^[\w]{5,11}$/,
            num : /^[\d]*$/,
            password : /^[\w~`\!@#\$%\^&\*\(\)_\-\=\+\[\]\{\}\:"\|;'\\<>\?,\.\/"]{6,15}$/,
            phoneAuthCode : /^[\d]{6}$/,
            imgCode : /^[\w]{4}$/,
            number : /^[0-9]*[1-9][0-9]*$/,
            float : /^\d+(\.\d+)?$/,
            chinese : /^[\u0391-\uFFE5]+$/,
            subname : /[a-zA-Z0-9]{3,32}/
        },
        message = {
            email : '请输入正确的邮箱',
            phone : '请输入正确的手机号',
            tel : '座机号为首位不为0的7-8位数字',
            fax : '传真为首位不为0的7-8位数字',
            qq : '请输入正确的qq',
            nickname : '请输入3-8位昵称',
            name : '请输入手机号或用户名',
            num : '请输入数字',
            password : '请输入6-15位密码',
            phoneAuthCode : '请输入6位数字验证码',
            imgCode : '请输入4位图片验证码',
            passwordNotEqual : '两次密码输入不一致!',
            imgCodeError : '图片验证码错误!',
            imgCodeExpire : '图片验证码过期!',
            userNotExist : '用户不存在!',
            phoneNotExist : '该手机号未注册!',
            phoneCodeError : '短信验证码错误!',
            phoneCodeExpire : '短信验证码过期!',
            number : '请输入整数',
            float : '请输入数字',
            chinese : '请输入中文',
            subname : '请输入3至32位域名'
        },
        lengthMessage = {
            eq : '请输入{1}个字符',
            rg : '请输入{1}到{2}个字符',
            gt : '至少输入{1}个字符',
            lte : '请最多输入{1}个字符'
        },
        checkedMessage = {
            eq : "请选择{1}项",
            rg : "请选择{1}到{2}项",
            gte : "请至少选择{1}项",
            lte : "请最多选择{1}项"
        },
        notEqualMessage = '该内容值不能重复!';
        var errorArray = new Array();
        function checkFields($fields){
            layer.closeAll();
            errorArray = new Array();
            $.each($fields, function (index, item){
                var $item = $(item);
                if($item.attr('required')){
                    checkFieldValue(index, $item);
                } else if(!$item.attr('required') && $item.val() != ''){
                    checkFieldValue(index, $item);
                }
            });
            return errorArray;
        }
        function checkFieldValue(index, $item){
            var val = $item.val();
            var tipMessage = '';
            var tipTarget = $($item);
            if($($item).attr('tip-value')){
                tipMessage = $($item).attr('tip-value');
            } else if(message.hasOwnProperty(type)){
                tipMessage = message[type];
            }
            if($item.attr('tip-target')){
                tipTarget = $($item.attr('tip-target'));
            }
            if($item.attr('fh-type')){
                var type = $item.attr('fh-type');
                if(reg.hasOwnProperty(type)){
                    if(!reg[type].test(val)){
                        errorArray.push({
                            ele : $item,
                            msg : message[type]
                        });
                        tips(message[type], tipTarget);
                    }
                } else if(type == 'length'){
                    var min = parseInt($($item).attr('minlength'));
                    var max = parseInt($($item).attr('maxlength'));
                    if(min == min && max == max){
                        if(val.length < min || val.length > max){
                            var msg = lengthMessage.rg.replace('{1}', min);
                            msg = msg.replace('{2}', max);
                            errorArray.push({
                                ele : $item,
                                msg : msg
                            });
                            tips(msg, tipTarget);
                        }
                    }
                    else if(min == min && max != max){
                        var msg = lengthMessage.gt.replace('{1}', min);
                        msg = msg.replace('{2}', max);
                        errorArray.push({
                            ele : $item,
                            msg : msg
                        });
                        tips(tipMessage, tipTarget);
                    } else if(max == max && min != min){
                        var msg = lengthMessage.lte.replace('{1}', min);
                        msg = msg.replace('{2}', max);
                        errorArray.push({
                            ele : $item,
                            msg : msg
                        });
                        tips(tipMessage, tipTarget);
                    }
                } else if(type === 'select'){

                }
                else{
                    throw new Error('no fh-type of ' + $item.attr('fh-type'));
                }
            } else if($item.attr('reg')){
                var reg2 = new RegExp($item.attr('reg'));
                if(!reg2.test(val)){
                    var tipMessage = '';
                    var tipTarget = $($item);
                    if($($item).attr('tip-value')){
                        tipMessage = $($item).attr('tip-value');
                    } else if(message.hasOwnProperty(type)){
                        tipMessage = message[type];
                    }
                    if($item.attr('tip-target')){
                        tipTarget = $($item.attr('tip-target'));
                    }
                    tips(tipMessage, tipTarget);
                    errorArray.push({
                        ele : $item,
                        msg : message[type]
                    });
                }
            } else{
                throw new Error('need param fh-type or reg');
            }
        }

        function notEqual(fields){
            var valArray = new Array();
            var hasEqual = false;
            $.each($(fields), function (index, item){
                var val = $(item).val();
                if($.inArray(val, valArray) > -1){
                    hasEqual = true;
                    tips(notEqualMessage, $(item));
                }
                valArray.push($(item).val());
            });
            return hasEqual;
        }

        function tips(msg, $item){
            layer.tips(msg, $item, {
                tipsMore : true,
                tips : [2, '#63bf82']
            });
        }

        function getInputValue(fields){
            var params = {};
            $.each($(fields), function (index, item){
                params[$(item).attr('id')] = $(item).val();
            });
            return params;
        }
        exports.reg = reg;
        exports.message = message;
        exports.checkFields = checkFields;
        exports.notEqual = notEqual;
        exports.tips = tips;
        exports.getInputValue = getInputValue;
    });
});