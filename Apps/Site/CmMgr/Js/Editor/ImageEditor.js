/*
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2015 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.Editor.ImageEditor', {
   extend : 'App.Site.CmMgr.Lib.Editor.StdEditor',
   
   constructor : function(config)
   {
      this.callParent([config]);
   },

   initComponent : function()
   {
      this.callParent();
   },

   /**
    * 保存编辑器里面内容
    */
   saveHandler : function()
   {
      if(this.isBasicFieldsValid()){
         //首先获取基本信息
         var values = this.getBasicFieldValues();
         //获取属性信息，并将其合并到一块
         Ext.apply(values, this.getPropertyFormValues());
         //添加模型ID
         Ext.apply(values, {
            modelId : this.modelId,
            fileRefs : this.fileRefs
         });
         if(this.mode == WebOs.Const.NEW_MODE){
            values.modelId = this.modelId;
         } else{
            values.id = this.loadedValue.id;
            values.qid = this.loadedValue.qid;
         }
         this.doSaveImage(values, this.mode);
      }
   },

   /**
    * 保存效果图相关信息
    */
   doSaveImage : function(values)
   {
      if(this.hasListeners.beforesaverequest){
         if(this.fireEvent('beforesaverequest', values, this.mode, this)){
            if(this.hasListeners.saverequest){
               this.fireEvent('saverequest', values, this.mode, this);
            }
         }
      } else{
         if(this.hasListeners.saverequest){
            this.fireEvent('saverequest', values, this.mode, this);
         }
      }
   },

/**
    * 按照内容模型，获取相关的内容模型字段，这些字段通过进程间通信获取
    */
   renderFieldWidgetsHandler : function()
   {
      this.cmAppRef.getModelFields(this.modelId, function(response){
         if(!response.status){
            Cntysoft.processApiError(response);
         } else{
            var cfgs = Ext.clone(response.data);
            var baseCls = 'App.Site.CmMgr.Lib.FieldWidget.';
            var items = [];
            var len = cfgs.length;
            var item;
            var clses = [];
            for(var i = 0; i < len; i++) {
               item = cfgs[i];
               if(this.mode == WebOs.Const.MODIFY_MODE && ((1 == this.loadedValue.imageType && ('space' == item.name || 'part' == item.name || 'singlePic' == item.name)) || (2 == this.loadedValue.imageType && ('houseType' == item.name || 'area' == item.name || 'images' == item.name)))){
                  item.display = 0;
               }
               if('1' == item.display){
                  clses.push(baseCls + item.fieldType);
               }
            }

            var total = clses.length;
            this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD_SCRIPT'));
            Ext.require(clses, function(){
               this.loadMask.hide();
               for(var i = 0; i < len; i++) {
                  item = cfgs[i];
                  
                  if('1' == item.display){
                     if('imageType' == item.name){
                        items.push(Ext.create(baseCls + item.fieldType, {
                           renderOptions : item,
                           isPreviewMode : false,
                           editorRef : this,
                           listeners : {
                              afterrender : function(fc){
                                 fc.comboRef.addListener('change', function(combo, newValue){
                                    var formRef = Ext.clone(this.basicFormRef), single = ['space', 'part', 'singlePic'], images = ['houseType', 'area', 'images'];
                                    if(1 == newValue){ //组图
                                       Ext.Array.each(images, function(name){
                                          if(formRef.down('[fieldName='+name+']')){
                                             formRef.down('[fieldName='+name+']').setDisabled(false).show();
                                          }
                                       }, this);
                                       Ext.Array.each(single, function(name){
                                          if(formRef.down('[fieldName='+name+']')){
                                             formRef.down('[fieldName='+name+']').setDisabled(true).hide();
                                          }
                                       }, this);
                                    }else if(2 == newValue){   //单图
                                       Ext.Array.each(single, function(name){
                                          if(formRef.down('[fieldName='+name+']')){
                                             formRef.down('[fieldName='+name+']').setDisabled(false).show();
                                          }
                                       }, this);
                                       Ext.Array.each(images, function(name){
                                          if(formRef.down('[fieldName='+name+']')){
                                             formRef.down('[fieldName='+name+']').setDisabled(true).hide();
                                          }
                                       }, this);
                                    }
                                 }, this);
                              },
                              scope : this
                           }
                        }));
                     }else{
                        items.push(Ext.create(baseCls + item.fieldType, {
                           renderOptions : item,
                           isPreviewMode : false,
                           editorRef : this
                        }));
                     }  
                  }
               }
               this.fieldWidgetItemsBeforeAddedHandler(items);
               this.basicFormRef.removeAll();
               this.fieldWidgetReady = false;
               this.currentLoadedNum = 0;
               this.basicFormRef.addListener('add', function(){
                  this.currentLoadedNum++;
                  if(this.currentLoadedNum === total){
                     this.fieldWidgetReady = true;
                     if(this.mode == WebOs.Const.MODIFY_MODE){
                        this.basicFormRef.down('[fieldName=imageType]').setDisabled(true);
                     }
                     if(this.hasListeners.fieldwidgetready){
                        this.fireEvent('fieldwidgetready');
                     }
                  }
               }, this);
               this.basicFormRef.add(items);
               Ext.Array.clean(cfgs);
            }, this);
         }
      }, this);
   },
   
   destroy : function()
   {
      this.callParent();
   }
});