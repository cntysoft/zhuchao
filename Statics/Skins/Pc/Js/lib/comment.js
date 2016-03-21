define(['jquery', 'Front'], function (){
    $(document).ready(function (){
        $('.logout').click(function (){
            Cntysoft.Front.callApi('User', 'logout', {
            }, function (response){
                if(response.status){
                    window.location.reload();
                }
            }, true);
        });
    });
});


