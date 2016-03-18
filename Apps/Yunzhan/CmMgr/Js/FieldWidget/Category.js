/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 内容模型管理器发布信息的时候目标节点的选择下拉框，模型不对或者没有权限的时候节点不能进行选择
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.Category', {
   extend : 'App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField',
   requires : [
      'App.Yunzhan.Category.Comp.CategoryCombo',
      'App.Yunzhan.CmMgr.Lib.Const'
   ],
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.CATEGORY',
   /**
    * @property {App.Yunzhan.Category.Comp.CategoryCombo} treeComboRef
    */
   treeComboRef : null,
   initComponent : function()
   {
      Ext.apply(this, {
         items : this.getCategoryComboConfig()
      });
      this.addListener('afterrender', this.setupStoreHandler, this);
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 300,
         height : 32
      };
   },
   /**
    * @inheritdoc
    */
   modifyApplyHandler : function()
   {
      var data = this.editorRef.loadedValue;
      this.treeComboRef.setRawValue(data.nodeText);
      this.setFieldValue(data[this.fieldName]);
      this.treeComboRef.setExpandPath(data.nodePath);
   },
   /**
    * @inheritdoc
    */
   setupDefaultValueHandler : function()
   {
      var targetNode = this.editorRef.targetNode;
      if(targetNode){
         this.treeComboRef.setRawValue(targetNode.get('text'));
         this.setFieldValue(targetNode.get('id'));
         this.treeComboRef.setExpandPath(Cntysoft.Kernel.Utils.getTreePath(targetNode));
      }
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.treeComboRef.setValue(value);
   },
   /**
    * @return {Number}
    */
   getFieldValue : function()
   {
      return this.treeComboRef.getValue();
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      return this.treeComboRef.isValid();
   },
   setupStoreHandler : function()
   {
      var tree = this.treeComboRef.getPicker();
      var store = tree.getStore();
      store.addListener('load', this.onStoreLoadHandler, this);
   },
   /**
    * 判断相关模型
    */
   onStoreLoadHandler : function(store, node, records)
   {
      var record;
      if(!this.isPreviewMode){
         var target = this.editorRef.modelId;
         var application = this.editorRef.cmAppRef;
      }
      var MSG = this.LANG_TEXT.MSG;
      var C = App.Yunzhan.CmMgr.Lib.Const;
      for(var i = 0; i < records.length; i++) {
         record = records[i];
         if(!this.isPreviewMode){
            if(!application.hasCategoryPermission(record.get('id'), C.DP_INPUT)){
               record.set('disabled', true);
               record.set('qtip', this.LANG_TEXT.NO_PERMISSION);
            }
            if(record.isRoot() || !this.validateModelType(target, record.get('contentModels'))){
               record.set('disabled', true);
               record.set('qtip', MSG);
            }
         }
      }
   },
   /**
    * @return {Boolean}
    */
   validateModelType : function(target, models)
   {
      var item;
      var exist = false;
      for(var i = 0; i < models.length; i++) {
         item = models[i];
         if(target == item.id){
            exist = true;
            break;
         }
      }
      return exist;
   },
   getCategoryComboConfig : function()
   {
      return {
         xtype : 'sitecategorycompcategorycombo',
         width : 200,
         allowBlank : false,
         allowTypes : [3],
         isClickAllowed : function(view, record)
         {
            //主要的作用是禁止不匹配的模型的节点的点击
            if(record.isRoot() || record.get('disabled')){
               return false;
            } else{
               return true;
            }
         },
         listeners : {
            afterrender : function(panel)
            {
               this.treeComboRef = panel;
            },
            categoryselect : function(record)
            {
               if(!this.isPreviewMode){
                  this.editorRef.notifyFieldWidgetValueChange(this.fieldName, record.get('id'));
               }

            },
            scope : this
         }
      };
   },
   destroy : function()
   {
      delete this.treeComboRef;
      delete this.targetNode;
      this.callParent();
   }
});