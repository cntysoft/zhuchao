define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
    $(function (){
        $('#username,#password').blur(function(){
           validate.checkFields($(this));
        });

        $('#submit').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#username,#password'));
            if(validateMsg.length){
                $.each(validateMsg, function (index, item){
                    layer.tips(item.msg,item.ele,{
                        tipsMore:true
                    });
                });
                return false;
            }
            
            Cntysoft.Front.callApi('User', 'login', {
               key : $('#username').val(),
               password : $('#password').val(),
               remember : $('.login_auto').hasClass('checked') ? 1 : 0
            }, function(response){
               if(!response.status){
                  if(10011 == response.errorCode){//过期
                     layer.alert('用户名或手机号不存在！');
                  }else if(10012 == response.errorCode){//锁定
                     layer.alert('当前用户已被锁定，请联系客服！');
                  }else if(10013 == response.errorCode){//错误
                     layer.alert('用户名或者密码错误！');
                  }
               }else{
                  layer.alert('登陆成功！');
               }
            });
        });
        
        $('.login_auto').click(function(){
           var $this = $(this);
           if($this.hasClass('checked')){
              $this.removeClass('checked');
           }else{
              $this.addClass('checked');
           }
        });
    });
});