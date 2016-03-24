/**
 * Created by wangzan on 2016/3/12.
 */
define(['validate', 'jquery', 'Core', 'Front'], function (validate){
    $(function (){
        $('#submit').click(function() {
            var validateMsg = validate.checkFields($('#subattr'));
            if(validateMsg.length){
                return false;
            }
            
            Cntysoft.Front.callApi('Provider', 'openSite', {
                subAttr : $('#subattr').val()
            }, function(response) {
                if(response.status) {
                    layer.msg('站点创建成功!', {
                        time : 2000
                    }, function() {
                        window.location.reload();
                    });
                }else {
                    var errorCode = response.errorCode;
                    if(10002 == errorCode) {
                        layer.msg('企业信息不存在');
                    }else if(10003 == errorCode) {
                        layer.msg('域名已经存在');
                    }
                    
                    return false;
                }
            });
        });
    });
});