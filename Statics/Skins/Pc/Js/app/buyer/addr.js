/**
 * Created by Administrator on 2016/3/19.
 */
define(['jquery', 'layer', 'Core', 'Front'], function (){
   $(function (){
      area();
      function area(){
         Cntysoft.Front.callApi('User', 'getProvinces', null,
         function (response){
            if(response.status){
               var data = response.data;
               $.each(data, function (index, item){
                  $('#province').siblings('.drop_list').append('<li index="' + index + '">' + item + '</li>');
               });
             }
         }, this);
      }
      $('#province').change(function (){
         var code = parseInt($('#province').attr('index'));console.log(code)
         if(code != 0){
            Cntysoft.Front.callApi('Cart', 'getChildArea', code,
            function (response){
               if(response.status){
                  var data = response.data;
                  $('#city').siblings('.drop_list').children().remove();
                  $('#city').append('<li index="0">--直辖市--</li>');
                  $.each(data, function (index, item){
                     $('#city').siblings('.drop_list').append('<li index="' + index + '">' + item + '</li>');
                     $('#city').attr('index', 0);
                     $('#country').attr('index', 0);
                  });
               }
            }, this);
         }
      });
      $('#city').change(function (){
         var code = parseInt($('#city').attr('index'));
         if(code != 0){
            Cntysoft.Front.callApi('Cart', 'getChildArea', code,
            function (response){
               if(response.status){
                  var data = response.data;                  $('#country').siblings('.drop_list').children().remove();
                  $('#country').append('<li index="0">--区/县--</li>');
                  $.each(data, function (index, item){
                    $('#country').siblings('.drop_list').append('<li index="' + index + '">' + item + '</li>');
                     $('#country').attr('index', 0);
                  });
               }
            }, this);
         }
      });
      $('.new_address input[required]').blur(function(){
         var val = $(this).val();
         if(!val){
            layer.msg($(this).next('.tip').text());
         }else{
            if('phone' == $(this).attr('id')){
               var reg = new RegExp(/^(1[0-9]{10})$/);
               if(!reg.test(val)){
                  layer.msg('请输入正确的手机号码！')
               }
            }
         }
      });
      $('.submit_address').click(function(event){
         event.preventDefault();
         
      });
   });
});