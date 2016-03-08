/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.CoverImage', {
   extend: 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   requires: [
      'App.Site.CmMgr.Comp.InternetPicWin',
      'App.Site.CmMgr.Lib.FieldWidget.SelectLocalFileWin',
      'Cntysoft.Component.Uploader.SimpleUploader',
      'WebOs.Kernel.StdPath'
   ],
   /**
    * @inheritdoc
    */
   langTextKey: 'FIELD_WIDGET.COVER_IMAGE',
   /**
    * @private
    * @property {Ext.Img} coverImageRef
    */
   coverImageRef: null,
   /**
    * 添加网络图片窗口
    *
    * @private
    * @propery {App.Site.CmMgr.Comp.InternetPicWin} internetWinRef
    */
   internetWinRef: null,
   /**
    * 文件引用id
    *
    * @property {Array} curfileRefs
    */
   curfileRefs: [],
   constructor: function(config)
   {
      this.callParent([config]);
   },
   initComponent: function()
   {
      var BTNS = this.LANG_TEXT.BTNS;
      var STD_PATH = WebOs.Kernel.StdPath;
      var basePath = ZC.getAppUploadFilesPath('Site', 'Content');
      Ext.apply(this, {
         items: [{
               xtype: 'image',
               height: 160,
               width: 120,
               border: 1,
               cls: 'coverImage',
               style: {
                  borderColor: '#3892D3',
                  borderStyle: 'solid'
               },
               margin: '0 0 5 0',
               listeners: {
                  afterrender: this.imageAfterrenderHandler,
                  scope: this
               }
            }, {
               xtype: 'container',
               layout: 'hbox',
               margin: '5 0 0 0',
               items: [{
                     xtype: 'cmpsimpleuploader',
                     uploadPath: basePath,
                     createSubDir: true,
                     fileTypeExts: ['gif', 'png', 'jpg', 'jpeg'],
                     enableFileRef: true,
                     requestUrl: WebOs.Kernel.Const.API_GATE_SYS,
                     apiRequestMeta: {
                        name: 'WebUploader',
                        method: 'process'
                     },
                     enableNail: true,
                     margin: '0 5 0 0',
                     maskTarget: this.editorRef,
                     buttonText: BTNS.UPLOAD,
                     listeners: {
                        fileuploadsuccess: this.uploadSuccessHandler,
                        scope: this
                     }
                  }, {
                     xtype: 'button',
                     text: BTNS.INTERNET,
                     listeners: {
                        click: this.addInternetImageHandler,
                        scope: this
                     }
                  }]
            }]
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize: function(renderOpt)
   {
      return {
         width: 450,
         height: 210
      };
   },
   /**
    * @inhritdoc
    */
   getFieldValue: function()
   {
      if (this.coverImageRef) {
         return [this.coverImageRef.src, this.curfileRefs];
      }
      return null;
   },
   /**
    * @inhritdoc
    */
   setFieldValue: function(data)
   {
      if (null != data[0]) {
         this.coverImageRef.setSrc(ZC.getZhuChaoImageUrl(data[0]));
         this.coverImageRef.src = data[0];
         this.curfileRefs = data[1];
      }
   },
   /**
    * 添加网络图片按钮点击事件监听函数
    */
   addInternetImageHandler: function()
   {
      if (null == this.internetWinRef) {
         this.internetWinRef = new App.Site.CmMgr.Comp.InternetPicWin({
            mutil: false
         });
         this.internetWinRef.addListener({
            saverequest: this.remoteImageSelectedHandler,
            scope: this
         });
      }
      this.internetWinRef.show();
   },
   uploadSuccessHandler: function(data)
   {
      var rid = parseInt(data[0].rid);
      if (this.curfileRefs.length) {
         //必须先删除当前的然后在变换
         Ext.Array.forEach(this.curfileRefs, function(ref) {
            Ext.Array.remove(this.editorRef.fileRefs, ref);
         }, this);
      }
      this.editorRef.fileRefs.push(rid);
      //我们只使用缩略图
      this.coverImageRef.setSrc(ZC.getZhuChaoImageUrl(data[0].filename));
      this.coverImageRef.src = data[0].filename;
      this.curfileRefs = [rid];
   },
   /**
    * 图片渲染完成事件监听函数
    */
   imageAfterrenderHandler: function(image)
   {
      this.coverImageRef = image;
      this.coverImageRef.src = null;
   },
   /**
    * 远程图片添加处理函数
    *
    **
    * <code>
    * {
    *      saveRemoteImage : 'Boolean',
    *      imageUrls : []
    * }
    * </code>
    * @param {Object} imageSelecedInfo
    */
   remoteImageSelectedHandler: function(imageSelecedInfo)
   {
      var imageUrls = imageSelecedInfo.imageUrls;
      var url = imageUrls.shift();
      if (!imageSelecedInfo.saveRemoteImage) {
         this.coverImageRef.setSrc(url);
         this.coverImageRef.src = url;
      } else {
         this.editorRef.setLoading(Ext.String.format(Cntysoft.GET_LANG_TEXT('MSG.SAVE_REMOTE_IMAGE'), url));
         this.editorRef.callSaverApi('downloadRemoteFile', {
            fileUrl: url,
            useOss: true,
            targetDir: '/Data/UploadFiles/Apps/Site/Content',
         }, function(response) {
            //没成功就不管了 很可能是网络错误
            if (response.status) {
               //替换文件内容
               var data = response.data;
               var rid = parseInt(data.rid);
               if (this.curfileRefs.length) {
                  //先清除当前的
                  //必须先删除当前的然后在变换
                  Ext.Array.forEach(this.curfileRefs, function(ref) {
                     Ext.Array.remove(this.editorRef.fileRefs, ref);
                  }, this);
               }
               this.editorRef.fileRefs.push(rid);
               this.coverImageRef.setSrc(ZC.getZhuChaoImageUrl(data.attachment));
               this.coverImageRef.src = data.attachment;
               this.curfileRefs = [rid];
            }
            this.editorRef.loadMask.hide();
         }, this);
      }
   },
   isFieldValueValid : function()
   {
      var MSG = this.LANG_TEXT.INVALID_TEXT;
      if(!this.coverImageRef.src){
         this.markInvalid(MSG.EMPTY);
         return false;
      }else{
         return true;
      }
   },
   destroy: function()
   {
      delete this.coverImageRef;
      if (this.internetWinRef) {
         this.internetWinRef.destroy();
      }
      this.curfileRefs = [];
      delete this.internetWinRef;
      this.callParent();
   }
});
