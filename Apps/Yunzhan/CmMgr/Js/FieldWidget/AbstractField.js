/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField', {
   extend : 'Ext.form.FieldContainer',
   requires : [
      'App.Yunzhan.CmMgr.Lib.Const'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider',
      formTooltip : 'Cntysoft.Mixin.FormTooltip'
   },
   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.Yunzhan.CmMgr',
   /**
    * 组件是否处于预览状态
    *
    * @property {Boolean} isPreviewMode
    */
   isPreviewMode : false,
   /**
    * 组件UI渲染选项
    *
    * @property {Object} renderOptions
    */
   renderOptions : null,
   /**
    * 编辑器对象引用
    *
    * @property {App.Yunzhan.CmMgr.Lib.Editor.AbstractEditor} editorRef
    */
   editorRef : null,
   /**
    * 这个组件在表单中字段的名称
    *
    * @property {String} fieldName
    */
   fieldName : null,
   /**
    * 语言变量的键
    *
    * @property {String} langTextKey
    */
   langTextKey : null,
   /**
    * @property {Object} ABSTRACT_LANG_TEXT
    */
   ABSTRACT_LANG_TEXT : null,
   /**
    * 组件的值是否只有一个，例如ImageGroup中就有两个值，images 和 defaultPicUrl
    *
    * @property {Boolean} isSingle
    */
   isSingle : true,
   /**
    * 构造函数
    *
    * @property {Object} config
    */
   constructor : function(config)
   {
      if(!config.isPreviewMode && !(config.editorRef instanceof App.Yunzhan.CmMgr.Lib.Editor.AbstractEditor)){
         Cntysoft.raiseError(
            Ext.getClassName(this),
            'constructor',
            'must have editorRef instance'
         );
      }
      if(this.langTextKey){
         this.LANG_TEXT = this.GET_LANG_TEXT(this.langTextKey);
      }
      this.ABSTRACT_LANG_TEXT = this.GET_LANG_TEXT('FIELD_WIDGET');
      this.applyConstraintConfig(config);
      this.mixins.formTooltip.constructor.call(this);
      this.callParent([config]);
   },
   applyConstraintConfig : function(config)
   {
      var renderOpt = config.renderOptions;
      var size = this.getWrapperSize(renderOpt);

      var fieldLabel = renderOpt.alias;
      if(renderOpt.require){
         fieldLabel += Cntysoft.Utils.HtmlTpl.RED_STAR;
      }
      Ext.apply(config,{
         fieldLabel : fieldLabel,
         toolTipText : Ext.isEmpty(renderOpt.description) ? null : renderOpt.description,
         fieldName : renderOpt.name,
         width : size.width,
         height : size.height
      });
   },
   initComponent : function()
   {
      this.addListener('afterrender',function(formItem){
         this.mixins.formTooltip.setupTooltipTarget.call(this, formItem);
         if(!this.isPreviewMode){
            if(this.editorRef.mode == 2){
               this.modifyApplyHandler();
            }else{
               this.setupDefaultValueHandler();
            }
         }
      }, this);
      this.callParent();
   },
   /**
    * 获取包裹容器大小
    *
    * @protected
    * @param {Object} renderOpt
    * @return {Object}
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 600,
         height : 25
      };
   },
   /**
    * 非预览模式并且编辑器处于编辑模式， 字段进行的相关操作, 默认行为
    */
   modifyApplyHandler : function()
   {
      var data = this.editorRef.loadedValue;
      this.setFieldValue(data[this.fieldName]);
   },
   /**
    * 设置表单的默认值
    *
    * @template
    */
   setupDefaultValueHandler : function()
   {
      if(this.renderOptions.defaultValue){
         this.setFieldValue(this.renderOptions.defaultValue);
      }
   },
   /**
    * 获取字段的值
    *
    * @template
    * @return {Object}
    */
   getFieldValue : Ext.emptyFn,
   /**
    * 设置字段的值
    *
    * @template
    * @param {Object} value
    */
   setFieldValue : Ext.emptyFn,
   /**
    * 判断当前字段的值是否合法，默认为合法
    *
    * @template
    * @return {Boolean}
    */
   isFieldValueValid : function()
   {
      return true;
   },
   /**
    * 标记非空错误
    */
   markEmptyError : function()
   {
      this.markInvalid(this.ABSTRACT_LANG_TEXT.BLANK_ERROR);
   },
   /**
    * 标记当前字段不合法
    *
    * @param {String} msg
    */
   markInvalid : function(msg)
   {
      if(!this.rendered){
         this.addListener('afterrender', function(){
            this.markInvalid(msg);
         }, this, {
            single : true
         });
      } else{
         if(!this.$_mark_invalid_el_$){
            this.$_mark_invalid_el_$ = this.el.down('.x-form-item-body');
         }
         this.$_mark_invalid_el_$.applyStyles({
            border : '1px solid red'
         });
         this.setActiveError(msg);
         this.updateLayout();
      }
   },
   /**
    * 清除错误
    */
   clearInvalid : function()
   {
      if(!this.rendered){
         this.addListener('afterrender', function(){
            this.clearInvalid();
         }, this, {
            single : true
         });
      } else{
         if(!this.$_mark_invalid_el_$){
            this.$_mark_invalid_el_$ = this.el.down('.x-form-item-body');
         }
         this.$_mark_invalid_el_$.applyStyles({
            border : 'none'
         });
         this.unsetActiveError();
         this.updateLayout();
      }
   },
   /**
    * 相应字段值的改变
    *
    * @param {String} fieldName 字段的名称
    * @param {Object} value 变化的值
    */
   replyFieldValueChangeHandler : Ext.emptyFn,
   /**
    * 值变化通知接口
    */
   valueChangeHandler : function()
   {
      this.editorRef.notifyFieldWidgetValueChange(this.fieldName, this.getFieldValue());
   },
   destroy : function()
   {
      this.mixins.langTextProvider.destroy.call(this);
      delete this.renderOptions;
      delete this.editorRef;
      delete this.$_mark_invalid_el_$;
      this.mixins.formTooltip.destroy.call(this);
      delete this.ABSTRACT_LANG_TEXT;
      this.callParent();
   }
});