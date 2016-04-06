/**
 * Created by wangzan on 2016/3/12.
 */
define(['webuploader', 'jquery', 'zh_CN', 'Core', 'Front', 'app/common','layer'], function (WebUploader){
   $(function (){
      var uploadIndex = -1;
      var images = new Array();
      var uploaderConfig, uploadProductImg;
      init();
      function init()
      {
         //如果上传按钮没隐藏时
         if(!$('.img_uploading li.hide').length){
            createProductUpload();
         }
         var $uploaded = $('.img_uploading .img_wrap_div');
         if($uploaded.length > 0){
            $.each($uploaded, function (index, item){
               var $img = $(item).find('img'), $input = $(item).find('input');
               images.push([$img.attr('src').split('@.src')[0], $img.attr('fh-id'), $input.val()]);
            });
         }
      }
      
      //提交 submit为保存,draft为生成草稿
      $('#submit').click(function (){
         var params = {};
         var imageLen = images.length;
         var banners = $('.img_wrap_div');
         
         if(imageLen > 0){
            $.each(banners, function(index, dom){
               images[index][2] = $(dom).find('input').val();
            });
         }
         params['banner'] = images;
         var keywords = $('#keywords').val();
         var description = $('#description').val();
         if(keywords.length < 1 || description.length < 1){
             layer.msg('请输入相关信息后再进行保存！');
             return false;
         }
         params['keywords'] = keywords;
         params['description'] = description;
         params['product'] = $('.product_nav').prop('checked');
         params['case'] = $('.case_nav').prop('checked');
         params['news'] = $('.news_nav').prop('checked');
         params['zhaopin'] = $('.zhaopin_nav').prop('checked');
         params['aboutus'] = $('.aboutus_nav').prop('checked');
         Cntysoft.Front.callApi('Site', 'modifySetting', params, function (response){
            if(response.status){
               layer.msg('店铺设置修改成功！', {
                  success : function (){
                     var redirect = function (){
                        window.location = '/site/setting.html';
                     };
                     setTimeout(redirect, 300);
                  }
               });
            } else{
               layer.msg('店铺设置修改失败，请核对您的信息！');
            }
         });
      });

      //删除商品图片
      $('.img_uploading').delegate('.deleteImg', 'click', function (){
         var imgWrap = $(this).parents('.img_wrap_div');
         images.splice($(imgWrap).index(), 1);
         showImg();
         if(uploadProductImg == undefined){
            createProductUpload();
         }
      });
      //展示上传的图片
      function showImg(){
         $('#uploadBtn').siblings('.img_wrap_div').remove();
         $.each(images, function (index, item){
            $('#uploadBtn').before('<div class="img_wrap_div"><li><img fh-id="'+item[1]+'" src="' + item[0] + '"><em class="deleteImg">删除</em></li><input type="text" placeholder="请输入图片对应的正确网址" value="'+item[2]+'"></div>');
         });
         if(images.length != 4){
            $('#uploadBtn').show();
         } else{
            $('#uploadBtn').hide();
         }
      }
      //初始化商品图片上传
      function createProductUpload(){
         uploaderConfig = {
            chunked : false,
            auto : true,
            dnd : '#uploadBtn',
            threads : 1,
            accept : {
               title : 'Images',
               extensions : 'gif,jpg,jpeg,bmp,png',
               mimeTypes : 'image/*'
            },
            compress : {
               // 图片质量，只有type为`image/jpeg`的时候才有效。
               quality : 90,
               // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
               allowMagnify : false,
               // 是否允许裁剪。
               crop : false,
               // 是否保留头部meta信息。
               preserveHeaders : true,
               // 如果发现压缩后文件大小比原来还大，则使用原来图片
               // 此属性可能会影响图片自动纠正功能
               noCompressIfLarger : false,
               // 单位字节，如果图片大小小于此值，不会采用压缩。
               compressSize : 1024 * 1024
            },
            server : '/front-api-entry',
            formData : {
               REQUEST_META : Cntysoft.Json.encode({
                  cls : "Uploader",
                  method : "process"
               }),
               REQUEST_DATA : Cntysoft.Json.encode({
                  uploadDir : "/Data/UploadFiles/Apps/ZhuChao/YunZhan",
                  overwrite : true,
                  randomize : true,
                  createSubDir : true,
                  enableFileRef : true,
                  useOss : true
               }),
               REQUEST_SECURITY : Cntysoft.Json.encode({})
            }
         };
         //处理上传
         uploadProductImg = WebUploader.create($.extend(uploaderConfig, {
            pick : '#uploadBtn'
         }));
         //上传商品图片
         uploadProductImg.on('beforeFileQueued', function (){
            if(images.length == 4){
               layer.msg('最多上传4张图片');
               return false;
            }
         });
         //商品图片上传成功
         uploadProductImg.on('uploadSuccess', function (file, response){
            if(response.status){
               images.push([response.data[0].filename, response.data[0].rid, '']);
               showImg();
            }
         });
      }

   });
});