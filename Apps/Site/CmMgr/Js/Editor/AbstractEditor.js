/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 系统抽象编辑器类, 负责处理一些必要的操作
 */
Ext.define('App.Site.CmMgr.Lib.Editor.AbstractEditor', {
   extend : 'WebOs.Component.Window',
   requires : [
      'Cntysoft.Utils.HtmlTpl',
      'Cntysoft.Kernel.Utils',
      'Ext.layout.container.Fit',
      'Ext.form.field.Date'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },

   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.Site.CmMgr',

   inheritableStatics : {
      ERROR : 'error'
   },
   /**
    * 关联的内容管理应用程序
    *
    * @property {WebOs.Kernel.ProcessModel.App} cmAppRef
    */
   cmAppRef : null,

   /**
    * 当mode为2的时候进行加载的目标ID
    *
    * @property {Number} targetLoadId
    */
   targetLoadId : null,

   /**
    * 原始加载的表单数据，这个用于做dirty判断
    *
    * @property {Object} loadedValue
    */
   loadedValue : null,

   /**
    * 当前内容模型的ID
    *
    * @property {Number} modelId
    */
   modelId : null,

   /**
    * 编辑器的模式
    *
    * @property {Number} mode
    */
   mode : WebOs.Const.NEW_MODE,

   /**
    * 语言对象引用
    *
    * @property {Object} ABSTRACT_EDITOR_LANG_TEXT
    */

   ABSTRACT_EDITOR_LANG_TEXT : null,

   /**
    * @property {Ext.form.Panel} basicFormRef
    */
   basicFormRef : null,
   /**
    * @property {Ext.form.Panel} propertyFormRef
    */
   propertyFormRef : null,
   /**
    * 当前field组件是否加载好
    *
    * @private
    * @property {Boolean} fieldWidgetReady
    */
   fieldWidgetReady : false,
   /**
    * 当前已经加载的组件数量
    *
    * @private
    * @property {Number} currentLoadedNum
    */
   currentLoadedNum : 0,
   /**
    * 权限添加模式下目标添加节点
    *
    * @private
    * @property {Object} targetNode
    */
   targetNode : null,
   /**
    * 本编辑器的文件引用
    *
    * @private
    * @property {Array} fileRefs
    */
   fileRefs : null,
   constructor : function(config)
   {
      config = config || {};
      if(!(config.cmAppRef instanceof WebOs.Kernel.ProcessModel.App)){
         Cntysoft.raiseError(
            Ext.getClassName(this),
            'constructor',
            'must have content manager application instance'
         );
      }
      config.modelId = parseInt(config.modelId);
      if(!Ext.isNumber(config.modelId)){
         Cntysoft.raiseError(
            Ext.getClassName(this),
            'constructor',
            'must specify content model id, because editor need retrieve field widget'
         );
      }
      this.ABSTRACT_EDITOR_LANG_TEXT = this.GET_LANG_TEXT('ABSTRACT_EDITOR');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },

   /**
    * @inheritdoc
    */
   applyConstraintConfig : function(config)
   {
      config = config || {};
      Ext.apply(config, {
         autoShow : true,
         layout : 'fit',
         constrain : true,
         constrainTo : Ext.getBody(),
         maximized : true,
         maximizable : true,
         height : 400,
         minHeight : 400,
         width : 1100,
         minWidth : 1100,
         bodyPadding : 1,
         closeAction : 'destroy',
         title : this.ABSTRACT_EDITOR_LANG_TEXT.WIN_TITLE
      });
   },

   /**
    * @event beforesaverequest
    *
    * @param {Object} data
    * @param {App.Site.CmMgr.Lib.Editor.AbstractEditor} editor
    */
   /**
    * @event saverequest
    *
    * @param {Object} data
    * @param {App.Site.CmMgr.Lib.Editor.AbstractEditor} editor
    */
   /**
    * @event fieldwidgetready
    * 当所有的field组建加载好之后派发
    */
   initComponent : function()
   {
      this.fileRefs = [];
      var BTNS = Cntysoft.GET_LANG_TEXT('UI.BTN');
      Ext.apply(this, {
         items : {
            xtype : 'tabpanel',
            bodyBorder : false,
            border : false,
            items : this.getEditorPanels(),
            buttons : [{
               text : BTNS.SAVE,
               listeners : {
                  click : this.saveHandler,
                  scope : this
               }
            }, {
               text : BTNS.CANCEL,
               listeners : {
                  click : function(){
                     this.close();
                  },
                  scope : this
               }
            }]
         }
      });
      if(this.mode == WebOs.Const.MODIFY_MODE){
         //检查目标加载ID是否设置
         if(null == this.targetLoadId){
            Cntysoft.raiseError(
               Ext.getClassName(this),
               'constructor',
               'targetLoadId must not be null'
            );
         }
         this.addListener('afterrender', this.prepareLoadInfoHandler, this);
      } else{
         this.addListener('afterrender', this.renderFieldWidgetsHandler, this);
      }
      this.callParent();
   },

   /**
    * 有的时候编辑器想自己关闭
    *
    * @template
    * @return {Boolean}
    */
   selfDestroy : function()
   {
      return false;
   },

   /**
    * 获取编辑器面板
    *
    * @template
    * @return {Array}
    */
   getEditorPanels : function()
   {
      return [this.getBasicFormConfig(), this.getPropertyFormConfig()];
   },
   /**
    * 获取指定的fieldwidget值
    *
    * @param {String} fieldName
    * @return {Object}
    */
   getFieldWidgetValue : function(fieldName)
   {
      var ret = null;
      //都在Basic里面查询
      this.basicFormRef.items.each(function(item){
         if(item.fieldName === fieldName){
            ret = item.getFieldValue();
         }
      }, this);
      return ret;
   },
   /**
    * 调用内容管理程序接口
    *
    * @param {String} name API的名称
    * ... 其余按照API而定
    */
   callCmApi : function()
   {
      var args = Array.prototype.slice.call(arguments, 0);
      var name = args.shift();
      if(!this.cmAppRef[name] || !Ext.isFunction(this.cmAppRef[name])){
         Cntysoft.raiseError(
            Ext.getClassName(this),
            'callCmApi',
            'Content Manager have no Api : ' + name
         );
      }
      this.cmAppRef[name].apply(this.cmAppRef, args);
   },
   /**
    * 调用保存器函数
    *
    * @param {String} name API函数名称
    * @param {Array} params API调用的参数
    */
   callSaverApi : function(name, params, callback, scope)
   {
      this.cmAppRef.callModelSaverApi(this.modelId, name, params, callback, scope);
   },
   /**
    * 通知所有组件特定的字段组件值已经变化
    *
    * @param {String} fieldName
    * @param {Object} value
    */
   notifyFieldWidgetValueChange : function(fieldName, value)
   {
      //可以所有的组件还没有加载好
      if(!this.fieldWidgetReady){
         this.addListener('fieldwidgetready', function(){
            this.notifyFieldWidgetValueChange(fieldName, value);
         }, this);
         return;
      }
      var fields = this.basicFormRef.items;
      fields.each(function(field){
         //挨个通知变化
         field.replyFieldValueChangeHandler(fieldName, value);
      });
   },
   /**
    * 获取基本字段
    */
   getBasicFieldValues : function()
   {
      if(!this.fieldWidgetReady){
         return {};
      } else{
         var values = {};
         this.basicFormRef.items.each(function(field){
            if(field.isSingle){ //返回值是单个值的时候，直接插入
               values[field.fieldName] = field.getFieldValue();
            } else{//返回值多个的时候
               Ext.apply(values, field.getFieldValue());
            }
         });
         return values;
      }
   },

   getPropertyFormValues : function()
   {
      var propertyValues = this.propertyFormRef.getForm().getValues();
      propertyValues.updateTime = Date.parse(propertyValues.updateTime) / 1000;
      return propertyValues;
   },

   prepareLoadInfoHandler : function()
   {
      this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD'));
      this.cmAppRef.readInfo(this.targetLoadId, function(response){
         this.loadMask.hide();
         if(!response.status){
            Cntysoft.processApiError(response);
         } else{
            //缓存数据
            this.loadedValue = response.data;
            this.fileRefs = [];//清空
            if(Ext.isArray(this.loadedValue.fileRefs)){
               Ext.Array.forEach(this.loadedValue.fileRefs, function(ref){
                  this.fileRefs.push(parseInt(ref));
               }, this);
            }
            //渲染组件，这里只负责对BasicForm中的数据进行赋值
            this.renderFieldWidgetsHandler();
            //对属性信息表单赋值
            this.setPropertyFormValues();
         }
      }, this);
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
         }
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
      }
   },


   /**
    * 数据保存之后钩子函数, 这个函数由保存逻辑调用
    *
    * @template
    * @param {Object} values
    */
   afterDataSavedHandler : Ext.emptyFn,
   /**
    * 重新打开进入编辑模型
    *
    * @template
    * @return {Boolean}
    */
   reOpenForModify : function()
   {
      return false;
   },
   /**
    * 字段是否合法
    *
    * @return {Boolean}
    */
   isBasicFieldsValid : function()
   {
      var flag = true;
      this.basicFormRef.items.each(function(field){
         if(!field.isDisabled() && !field.isFieldValueValid()){
            flag = false;
         }
      }, this);
      return flag;
   },
   getBasicFormConfig : function()
   {
      return {
         xtype : 'form',
         title : this.ABSTRACT_EDITOR_LANG_TEXT.FORM.BASIC.TITLE,
         bodyPadding : 10,
         autoScroll : true,
         layout : 'anchor',
         listeners : {
            afterrender : function(panel)
            {
               this.basicFormRef = panel;
            },
            scope : this
         }
      };
   },
   getPropertyFormConfig : function()
   {
      var STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
      var FORM_TEXT = this.ABSTRACT_EDITOR_LANG_TEXT.FORM.PROPERTY;
      var FIELDS_TEXT = FORM_TEXT.FIELDS;
      this.propertyFormRef = new Ext.form.Panel({
         bodyBorder : false,
         bodyPadding : 10,
         border : false,
         title : FORM_TEXT.TITLE,
         items : [{
            xtype : 'numberfield',
            fieldLabel : FIELDS_TEXT.HITS + STAR,
            width : 240,
            value : 1000,
            minValue : 100,
            name : 'hits'
         }, {
            xtype : 'datefield',
            fieldLabel : FIELDS_TEXT.UPDATE_TIME + STAR,
            format : 'Y-m-d H:i:s',
            name : 'updateTime',
            listeners : {
               added : function(self){
                  if(this.mode == WebOs.Const.NEW_MODE){
                     //如果为新创建，则初始化未当前时间
                     self.setValue(new Date());
                  }
               },
               scope : this
            },
            width : 300
         }]
      });
      return this.propertyFormRef;
   },
   /**
    * 渲染编辑器的组件
    *
    * @template
    */
   renderFieldWidgetsHandler : Ext.emptyFn,
   /**
    * 对属性表单赋值
    */
   setPropertyFormValues : function()
   {
      var data = this.loadedValue;
      this.propertyFormRef.getForm().setValues(data);
   },

   destroy : function()
   {
      delete this.ABSTRACT_EDITOR_LANG_TEXT;
      this.fileRefs = [];
      delete this.fileRefs;
      delete this.loadedValue;
      delete this.targetNode;
      delete this.cmAppRef;
      delete this.basicFormRef;
      delete this.propertyFormRef;
      delete this.fieldWidgetReady;
      if(this.loadMask){
         this.loadMask.destroy();
         delete this.loadMask;
      }
      this.callParent();
   }
});