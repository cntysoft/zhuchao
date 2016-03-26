define(['validate', 'jquery', 'Core', 'Front', 'app/common'], function (validate){
    $(function (){
        
        var reg = '/inquirycontent/([1-9][0-9]*).html'
        var inquiryId = window.location.href.match(reg)[1];
        $('#submit').click(function (){
            var validation = validate.checkFields($('.checkfield'));
            if(validation.length){
                validation[0].ele.focus();
                return false;
            }
            var params = {
                lowPrice : $('#lowPrice').val(),
                highPrice : $('#highPrice').val(),
                content : $('#content').val(),
                inquiryId:inquiryId
            };
            Cntysoft.Front.callApi('Inquiry', 'replyInquiry', params, function (response){
                if(response.status){
                    layer.msg('回复成功!');
                    setTimeout(function(){
                        window.location.reload();
                    },1000);
                }else{
                    layer.alert('回复失败!');
                }
            });
        });
    });
});