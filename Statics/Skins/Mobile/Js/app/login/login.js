define(['zepto', 'layer', 'Core', 'Front'], function (){
    $(function (){
        $('.submit_btn').tap(function (event){
            event.preventDefault();
            var $name = $('#name'), $pwd = $('#pwd');
            var regName = new RegExp(/^([\w]{1,10}|1[0-9]{10})$/), regPwd = new RegExp(/^[\w~`\!@#\$%\^&\*\(\)_\-\=\+\[\]\{\}\:"\|;'\\<>\?,\.\/"]{6,15}$/);
            if(!$name.val() || !regName.test($name.val())){
                layer.open({
                    content : '请输入正确的用户名或手机号',
                    time : 1
                });
                return false;
            }

            if(!$pwd.val() || !regPwd.test($pwd.val())){
                layer.open({
                    content : '请输入正确的用户密码',
                    time : 1
                });
                return false;
            }

            Cntysoft.Front.callApi('User', 'login', {
                key : $('#name').val(),
                password : Cntysoft.Core.sha256($('#pwd').val()),
                remember : $('.login_auto').hasClass('checked') ? 1 : 0
            }, function (response){
                if(!response.status){
                    if(10011 == response.errorCode){//过期
                        layer.open({
                            content : '用户名或手机号不存在！',
                            time : 1
                        });
                    } else if(10012 == response.errorCode){//锁定
                        layer.open({
                            content : '当前用户已被锁定，请联系客服！',
                            time : 1
                        });
                    } else if(10013 == response.errorCode){//错误
                        layer.open({
                            content : '用户名或者密码错误！',
                            time : 1
                        });
                    }
                } else{
                    layer.open({
                        content : '登陆成功！',
                        success : function (){
                            var redirect = function (){
                                var query = Cntysoft.fromQueryString(window.location.search, true);
                                if(query.returnUrl){
                                    window.location = query.returnUrl;
                                } else if(query.from){
                                    window.location = query.from;
                                } else{
                                    window.location = '/';
                                }
                            };
                            setTimeout(redirect, 500);
                        }
                    });
                }
            });
        });

        $('.login_auto').tap(function (){
            var $this = $(this);
            if($this.hasClass('checked')){
                $this.removeClass('checked');
            } else{
                $this.addClass('checked');
            }
        });
    });
});