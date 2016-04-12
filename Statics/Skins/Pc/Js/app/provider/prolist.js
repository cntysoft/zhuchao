define(['jquery', 'Core', 'Front', 'layer', 'app/common'], function (){
   $(document).ready(function (){
      init();
      //全選
      $('.prolist_operate').find('.check').click(function (){
         var $this = $(this);
         if($this.find('i').hasClass('icon-checked')){
            $('.prolist_operate .btn').addClass('btn_del');
            $('.prolist_operate').find('.check i').removeClass('icon-checked');
            $('.prolist_list ').find('.check i').removeClass('icon-checked');
         } else{
            $('.prolist_operate .btn').removeClass('btn_del');
            $('.prolist_operate').find('.check i').addClass('icon-checked');
            $('.prolist_list ').find('.check i').addClass('icon-checked');
         }
      });
      //單選
      $('.prolist_list ').find('.check').click(function (){
         var $this = $(this);
         if($this.find('i').hasClass('icon-checked')){
            $this.find('i').removeClass('icon-checked');
         } else{
            $this.find('i').addClass('icon-checked');
         }
      });
      $('.prolist_list ').find('.check').click(function (){
         var prolistLenght = $('.prolist_list ').length;
         var icheckedLenght = $('.prolist_list ').has('.icon-checked').length;

         if(prolistLenght == icheckedLenght){
            $('.prolist_operate').find('.check i').addClass('icon-checked');
         } else{
            $('.prolist_operate').find('.check i').removeClass('icon-checked');
         }
         if($('.prolist_list ').has('.icon-checked').length > 0){
            $('.prolist_operate .btn').removeClass('btn_del');
         } else{
            $('.prolist_operate .btn').addClass('btn_del');
         }
      });

      $('.search_key').blur(function (){
         var val = $(this).val(), orgin = $(this).attr('orgin-key');
         if(val != orgin){
            redirectUrl();
         }
      });

      $('.pro_status li').click(function (){
         if(!$(this).hasClass('current')){
            $(this).siblings('li').removeClass('current');
            $(this).addClass('current');
            redirectUrl();
         }
      });

      function redirectUrl()
      {
         var cond = {};
         if($('.search_key').val()){
            cond['keyword'] = $('.search_key').val();
         }

         var index = $('.pro_status li').index($('.pro_status li.current'));
         switch (index) {
            case 0:
               cond['status'] = 3;
               break;
            case 1:
               cond['status'] = 2;
               break;
            case 2:
               cond['status'] = 5;
               break;
            case 3:
               cond['status'] = 4;
               break;
         }
         
         var query = '?';
         $.each(cond, function (key, item){
            query += key + '=' + item + '&';
         });
         query = encodeURI(query.substring(0, query.length - 1));

         if(!query){
            window.location.href = location.protocol + '//' + location.hostname + location.pathname;
         } else{
            window.location.href = location.protocol + '//' + location.hostname + location.pathname + query;
         }
      }

      $('.pro_operate .delete').click(function (){
         var $delete = $(this);
         var numbers = $delete.parents('.prolist_list').attr('fh-number');
         deleteProducts(numbers);
      });

      $('.prolist_operate .delete').click(function (){
         if($(this).hasClass('btn_del')){
            return;
         }
         var checkedList = $('.prolist_list ').has('.icon-checked');
         if(0 == checkedList.length){
            return;
         }
         var numbers = [];
         checkedList.each(function (index, dom){
            numbers.push($(dom).attr('fh-number'));
         });

         deleteProducts(numbers);
      });

      $('.prolist_operate .shelf').click(function (){
         if($(this).hasClass('btn_del')){
            return;
         }
         var checkedList = $('.prolist_list ').has('.icon-checked');
         if(0 == checkedList.length){
            return;
         }
         var numbers = [];
         checkedList.each(function (index, dom){
            numbers.push($(dom).attr('fh-number'));
         });

         shelfProduct(numbers);
      });

      $('.prolist_operate .upshelf').click(function (){
         if($(this).hasClass('btn_del')){
            return;
         }
         var checkedList = $('.prolist_list ').has('.icon-checked');
         if(0 == checkedList.length){
            return;
         }
         var numbers = [];
         checkedList.each(function (index, dom){
            numbers.push($(dom).attr('fh-number'));
         });

         upshelfProduct(numbers);
      });

      function upshelfProduct(numbers)
      {
         layer.confirm('您确定要上架选中的产品吗?', function (index){
            layer.close(index);
            Cntysoft.Front.callApi('Product', 'upshelfProduct', {
               numbers : numbers
            }, function (response){
               if(!response.status){
                  layer.msg('上架失败，请稍后再试！', {
                     time : 1
                  });
               } else{
                  layer.msg('上架成功！', {
                     success : function (){
                        var redirect = function (){
                           window.location.href = '/product/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      }

      function shelfProduct(numbers)
      {
         layer.confirm('您确定要下架选中的产品吗?', function (index){
            layer.close(index);
            Cntysoft.Front.callApi('Product', 'shelfProduct', {
               numbers : numbers
            }, function (response){
               if(!response.status){
                  layer.msg('下架失败，请稍后再试！', {
                     time : 1
                  });
               } else{
                  layer.msg('下架成功！', {
                     success : function (){
                        var redirect = function (){
                           window.location.href = '/product/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      }

      function deleteProducts(numbers)
      {
         layer.confirm('您确定要删除选中的产品吗?', function (index){
            layer.close(index);
            Cntysoft.Front.callApi('Product', 'deleteProduct', {
               numbers : numbers
            }, function (response){
               if(!response.status){
                  layer.msg('删除失败，请稍后再试！');
               } else{
                  layer.msg('删除成功！', {
                     success : function (){
                        var redirect = function (){
                           window.location.href = '/product/1.html';
                        };
                        setTimeout(redirect, 300);
                     }
                  });
               }
            });
         });
      }
      
      function init()
      {
         var query = Cntysoft.fromQueryString(window.location.search, true);
         for(var key in query){
            if('status' == key){
               switch(parseInt(query[key])){
                  case 2:
                     $('.pro_status li').eq(1).addClass('current');
                     break;
                  case 3:
                     $('.pro_status li').eq(0).addClass('current');
                     break;
                  case 4:
                     $('.pro_status li').eq(3).addClass('current');
                     break;
                  case 5:
                     $('.pro_status li').eq(2).addClass('current');
                     break;
               }
            }
         }
      }
      
      $('head').append('<script type="text/javascript" charset="utf-8" async=""  src="/Statics/Skins/Pc/Js/lib/qrcode.js"></script>');
      /**
       * 在本地进行文件保存
       * @param  {String} data     要保存到本地的图片数据
       * @param  {String} filename 文件名
       */
      var saveFile = function (data, filename){
         var save_link = document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
         save_link.href = data;
         save_link.download = filename;

         var event = document.createEvent('MouseEvents');
         event.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
         save_link.dispatchEvent(event);
      };

// download
      $('.prolist_list td.pro_code2').mouseenter(function (){
         var $proerweima = $(this).find('div.code_img');
         if(!$proerweima.hasClass('loaded')){
            $proerweima.qrcode({
               render : "canvas",
               height : 120,
               width : 120,
               text : $proerweima.attr('url')
            });
            $proerweima.addClass('loaded');
         }
      });
      $('.pro_code_box div.code_img').click(function (){
         var filename = $(this).attr('title');
         var imgData = $(this).find('canvas')[0].toDataURL('image/jpeg');
         layer.confirm('您确定要下载该产品二维码吗?', function (index){
            layer.close(index);
            saveFile(imgData, filename);
         });
      });
   });
});
