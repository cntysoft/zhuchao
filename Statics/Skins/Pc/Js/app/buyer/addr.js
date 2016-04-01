/**
 * Created by Administrator on 2016/3/19.
 */
define(['module/address', 'jquery', 'layer', 'Core', 'Front','app/common'], function (address){
   $(function (){
      $('.search_btn').click(function(){
         var key = $('.search_key').val();
         var baseUrl = $('.logo_img').attr('href');
         if(key){
            window.location.href = baseUrl + '/query/1.html?keyword=' + key;
         }
      });
      var reg = new RegExp(/^1[0-9]{10}$/);
      var type = 'add', flag = true;
      $('.submit_address').click(function(event){
         event.preventDefault();
         $('#name,#address,#phone').each(function(){
            var $this = $(this);
            if(!$this.val()){
               layer.tips($this.next('.tip').text(), '#'+$this.attr('id'), {
                tipsMore : true,
                tips : [2, '#63bf82']
            });
               return false;
            }
         });
         $('#province,#city,#district').each(function(){
            var $this = $(this);
            var regExp = new RegExp($this.attr('reg'));
            if(!regExp.test($this.val())){
               layer.tips($this.attr('tip-value'), $this.attr('tip-target'),{
                tipsMore : true,
                tips : [2, '#63bf82']
            });
               flag = false;
               return false;
            }
         });
         if(!flag){
            flag = true;
            return false;
         }
         if($('#phone').val() && !reg.test($('#phone').val())){
            layer.tips('请输入正确的手机号码！', '#phone',{
                tipsMore : true,
                tips : [2, '#63bf82']
            });
               return false;
         }
         
         var params = {};
         params['username'] = $('#name').val();
         params['address'] = $('#address').val();
         params['phone'] = $('#phone').val();
         params['postCode'] = $('#postCode').val();
         params['province'] = $('#province').val();
         params['city'] = $('#city').val();
         params['district'] = $('#district').val();
         params['isDefault'] = $('#new_defult').is(':checked') ? 1 : 0;
         
         if('add' == type){
            Cntysoft.Front.callApi('User', 'addAddress', params, function(response){
               if(!response.status){
                  if(10019 == response.errorCode){
                     layer.alert('收货地址数量已经最大值，不能在添加');
                  }
               }else{
                  layer.msg('收货地址添加成功', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.reload();
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         }else{
            params['id'] = $('.new_address_content').attr('fh-index');
            Cntysoft.Front.callApi('User', 'updateAddress', params, function(response){
               if(!response.status){
                  layer.alert('地址修改失败，请稍后再试！');
               }else{
                  layer.msg('收货地址修改成功', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.reload();
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         }
      });
      $('.address_ele').delegate('.defult_btn', 'click', function(){
         var id = $(this).parents('.address_ele').attr('fh-index');
         Cntysoft.Front.callApi('User', 'setDefaultAddress', {
            id : id
         }, function(response){
            if(!response.status){
               layer.alert('设置默认地址失败！', {
                  btn : '',
                  success : function(){
                     var redirect = function(){
                        window.location.reload();
                     };
                     setTimeout(redirect, 300);
                  }
               });
            }else{
               layer.msg('设置默认地址成功！', {
                  btn : '',
                  success : function(){
                     var redirect = function(){
                        window.location.reload();
                     };
                     setTimeout(redirect, 300);
                  }
               });
            }
         });
      });
      $('.address_ele').delegate('.del_btn', 'click', function(){
         var id = $(this).parents('.address_ele').attr('fh-index');
         layer.confirm('您确定要删除选中的收货地址？', function(){
            Cntysoft.Front.callApi('User', 'deleteAddress', {
               id : id
            }, function(response){
               if(!response.status){
                  layer.alert('删除收货地址失败！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.reload();
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }else{
                  layer.msg('删除收货地址成功！', {
                     btn : '',
                     success : function(){
                        var redirect = function(){
                           window.location.reload();
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      });
      $('.address_ele').delegate('.edit_btn', 'click', function(){
         var id = $(this).parents('.address_ele').attr('fh-index');
         Cntysoft.Front.callApi('User', 'getAddress', {
            id : id
         }, function(response){
            if(!response.status){
               layer.alert('地址错误，请稍后再试！');
            }else{
               if('edit' == type){
                  resetForm();
               }
               type = 'edit';
               var data = response.data;
               $('.new_address_content').attr('fh-index', id);
               $('#name').val(data.username);
               $('#address').val(data.address);
               $('#phone').val(data.phone);
               $('#province').attr('fh-value', data.province);
               $('#city').attr('fh-value', data.city);
               $('#district').attr('fh-value', data.district);
               $('#postCode').val(data.postCode);
               if(1 == data.isDefault){
                  $('#new_defult').attr('checked', 'checked');
               }
               address.init();
            }
         });
      });
      
      $('.addr_reset').click(function(){
         type = 'add';
         resetForm();
      });
      function resetForm()
      {
         $('#name').val('');
         $('#address').val('');
         $('#phone').val('');
         $('#province').val(0);
         $('#city').val(0);
         $('#district').val(0);
         $('#postCode').val('');
         $('#new_defult').removeAttr('checked');
         $('.new_address_content').removeAttr('fh-index');
      }
   });
});