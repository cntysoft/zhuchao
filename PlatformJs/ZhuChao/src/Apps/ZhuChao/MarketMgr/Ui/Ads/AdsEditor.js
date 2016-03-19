/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Ui.Ads.AdsEditor',{
   extend : 'Ext.form.Panel',
   requires : [
      'ZhuChao.Kernel.Component.Uploader.SimpleUploader'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.MarketMgr',
   formItemsRef : null,
   formRef : null,
   fileRefs : null,
   image : null,
   constructor : function(config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('ADS.ADSEDITOR');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function(config)
   {
      Ext.apply(config,{
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function()
   {
      var CONST = App.ZhuChao.MarketMgr.Const;
      Ext.apply(this,{
         items : this.getFormItemsConfig(),
         buttons : [{
               text : this.LANG_TEXT.RESET,
               listeners : {
                  click : this.resetButtonClickHandler,
                  scope : this
               }
         },{
               text : this.LANG_TEXT.ADD,
               listeners : {
                  click : this.submitButtonClickHandler,
                  scope : this
               }
            }],
         listeners : {
            afterrender : function (form){
               this.formRef = form;
               if(CONST.ADS_TYPE_MODIFY == this.type){
                  var data = this.record.getData();
                  form.getForm().setValues(data);
                  this.image = data.image;
                  this.fileRefs = data.fileRefs;
                  this.imageRef.setSrc(ZC.getZhuChaoImageUrl(data.image));
               } else{
                  var data = this.record.getData();
                  var pid = {
                     locationId : data.id,
                     sort : 1
                  };
                  form.getForm().setValues(pid);
               }
            },
            scope : this
         }
      });
      this.callParent();
   },
   /**
    * 获取form表单内容
    * 
    * @returns {Array}
    */
   getFormItemsConfig : function()
   {
      if(null == this.formItemsRef){
         this.formItemsRef = [{
               xtype : 'textfield',
               name : 'locationId',
               hidden : true,
               allowBlank : false
         },{
               xtype : 'textfield',
               name : 'name',
               fieldLabel : this.LANG_TEXT.NAME,
               width : 400,
               margin : '30 0 0 30',
               allowBlank : false
         },{
               xtype : 'textfield',
               name : 'contentUrl',
               fieldLabel : this.LANG_TEXT.CONTENTURL,
               width : 400,
               margin : '10 0 0 30',
               allowBlank : false
         },{
               xtype : 'colorfield',
               name : 'bgcolor',
               editable : true,
               fieldLabel : this.LANG_TEXT.BGCOLOR,
               width : 400,
               value : '#FFFFFF',
               margin : '10 0 0 30'
         },{
               xtype : 'datefield',
               name : 'startTime',
               editable : false,
               fieldLabel : this.LANG_TEXT.STARTTIME,
               width : 400,
               format : 'Y-m-d',
               margin : '10 0 0 30'
         },{
               xtype : 'datefield',
               name : 'endTime',
               editable : false,
               fieldLabel : this.LANG_TEXT.ENDTIME,
               width : 400,
               format : 'Y-m-d',
               margin : '10 0 0 30'
         },{
               xtype : 'numberfield',
               name : 'sort',
               fieldLabel : this.LANG_TEXT.SORT,
               width : 400,
               minValue : 1,
               margin : '10 0 0 30',
               allowBlank : true
         },{
            xtype : 'fieldcontainer',
            fieldLabel : this.LANG_TEXT.IMAGE,
            margin : '10 0 0 30',
            layout : {
               type : 'hbox',
               align : 'bottom'
            },
            items : [{
                  xtype : 'image',
                  width : 400,
                  height : 200,
                  style : 'border:1px solid #EDEDED',
                  listeners : {
                     afterrender : function (comp){
                        this.imageRef = comp;
                     },
                     scope : this
                  }
               }, {
                  xtype : 'zhuchaosimpleuploader',
                  uploadPath : this.mainPanelRef.appRef.getUploadFilesPath(),
                  createSubDir : false,
                  fileTypeExts : ['gif', 'png', 'jpg', 'jpeg'],
                  margin : '0 0 0 5',
                  maskTarget : this,
                  enableFileRef : true,
                  buttonText : this.LANG_TEXT.UP,
                  listeners : {
                     fileuploadsuccess : this.uploadSuccessHandler,
                     scope : this
                  }
               }]
         }];
      }
      return this.formItemsRef;
   },
   uploadSuccessHandler : function (file, uploadBtn)
   {
      var file = file.pop();
      this.fileRefs = parseInt(file.rid);
      this.image = file.filename;
      this.imageRef.setSrc(ZC.getZhuChaoImageUrl(file.filename));
   },
   /**
    * 取消按钮处理
    * 
    * @returns {undefined}
    */
   resetButtonClickHandler : function()
   {
      this.mainPanelRef.renderPanel('DirectForUse');
   },
   /**
    * 提交按钮处理
    * 
    * @returns {undefined}
    */
   submitButtonClickHandler : function()
   {
      var CONST = App.ZhuChao.MarketMgr.Const;
      if(null == this.fileRefs || null == this.image){
         Cntysoft.showErrorWindow(this.LANG_TEXT.NOIMAGE);
      } else{
         var form = this.formRef.getForm();
         if(form.isValid()){
            if(CONST.ADS_TYPE_MODIFY == this.type){
               var values = form.getValues();
               delete(values.pidText);
               values.image = this.image;
               values.fileRefs = this.fileRefs;
               values.id = this.record.getId();
               this.mainPanelRef.appRef.modifyAds(values, function (response){
                  if(!response.status){
                     Cntysoft.showErrorWindow(response.msg);
                  } else{
                     this.mainPanelRef.renderPanel('ListView', {
                        locationId : this.record.getData().locationId
                     });
                     Cntysoft.showAlertWindow(this.LANG_TEXT.MODIFYSUCCESS);
                  }
               }, this);
            } else {
               var values = form.getValues();
               delete(values.pidText);
               values.image = this.image;
               values.fileRefs = this.fileRefs;
               this.mainPanelRef.appRef.addAds(values, function (response){
                  if(!response.status){
                     Cntysoft.showErrorWindow(response.msg);
                  } else{
                     this.mainPanelRef.renderPanel('ListView', {
                        locationId : this.record.getId()
                     });
                     Cntysoft.showAlertWindow(this.LANG_TEXT.ADDSUCCESS);
                  }
               }, this);
            }
         }
      }
      
   },
   destroy : function()
   {
      delete this.formItemsRef;
      delete this.formRef;
      delete this.image;
      delete this.fileRefs;
      delete this.imageRef;
      this.callParent();
   }
});



