/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 系统标准的编辑器， 支持通用模型的加载
 */
Ext.define('App.Yunzhan.CmMgr.Lib.Editor.StdEditor', {
   extend : 'App.Yunzhan.CmMgr.Lib.Editor.AbstractEditor',
   /**
    * 按照内容模型，获取相关的内容模型字段，这些字段通过进程间通信获取
    */
   renderFieldWidgetsHandler : function()
   {
      this.cmAppRef.getModelFields(this.modelId, function(response){
         if(!response.status){
            Cntysoft.processApiError(response);
         } else{
            var cfgs = response.data;
            var baseCls = 'App.Yunzhan.CmMgr.Lib.FieldWidget.';
            var items = [];
            var len = cfgs.length;
            var item;
            var clses = [];
            for(var i = 0; i < len; i++) {
               item = cfgs[i];
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
                     items.push(Ext.create(baseCls + item.fieldType, {
                        renderOptions : item,
                        isPreviewMode : false,
                        editorRef : this
                     }));
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
                     if(this.hasListeners.fieldwidgetready){
                        this.fireEvent('fieldwidgetready');
                     }
                  }
               }, this);
               this.basicFormRef.add(items);
            }, this);
         }
      }, this);
   },
   /**
    * fieldWidget添加之前的处理函数， 可以用来添加手动自定义模型自己的字段类型
    *
    * @param {Array} items
    */
   fieldWidgetItemsBeforeAddedHandler : Ext.emptyFn
});