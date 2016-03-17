define(['validate', 'jquery', 'layer', 'Core', 'Front'], function (validate){
    $(function (){
        $('#name, #password').blur(function(){
           validate.checkFields($(this));
        });

        $('#submit').click(function (event){
            event.preventDefault();
            var validateMsg = validate.checkFields($('#name,#password'));
            if(validateMsg.length){
                return false;
            }
            
            Cntysoft.Front.callApi('User', 'login', {
               key : $('#name').val(),
               password : Cntysoft.Core.sha256($('#password').val()),
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
                  layer.alert('登陆成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           var query = Cntysoft.fromQueryString(window.location.search, true);
                           if(query.returnUrl){
                              window.location = query.returnUrl;
                           } else{
                              window.location = '/';
                           }
                        };
                        setTimeout(redirect, 300);
                     }
                  });
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