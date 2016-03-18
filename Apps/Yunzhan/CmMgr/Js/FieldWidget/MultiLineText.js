/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 多行文本，可以带字符长度检查
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.MultiLineText', {
   extend : 'App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.MULTI_LINE_TEXT',
   /**
    * @property {Ext.form.field.TextArea} textareaRef
    */
   textareaRef : null,
   /**
    * 当前最长的字符数目
    *
    * @private
    * @property {Number} maxLen
    */
   maxLen : null,
   /**
    * @private
    * @property {Ext.form.Label} labelRef
    */
   labelRef : null,
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      var uiOpt = renderOpt.uiOption;
      var width = parseInt(uiOpt.width) + 600;
      var height = parseInt(uiOpt.height) || 100;
      if(uiOpt.enableLenCheck){
         height += 30;//检查结果label
      }
      return {
         width : width,
         height : height
      };
   },
   initComponent : function()
   {
      var renderOpt = this.renderOptions;
      var uiOpt = renderOpt.uiOption;
      var width = parseInt(uiOpt.width) || 600;
      var height = parseInt(uiOpt.height) || 100;
      var me = this;
      var items = [{
         xtype : 'textarea',
         width : width,
         height : height,
         msgTarget : 'side',
         listeners : (function(){
            var ls = {
               afterrender : function(textareaRef)
               {
                  this.textareaRef = textareaRef;
               },
               scope : me
            };
            if(uiOpt.enableLenCheck){
               me.maxLen = uiOpt.maxLen;
               ls.change = me.checkLenHandler;
            }
            return ls;
         })(),
         allowBlank : !renderOpt.require
      }];
      if(uiOpt.enableLenCheck){
         var MSG = this.LANG_TEXT.MSG;
         items.push({
            xtype : 'label',
            text : Ext.String.format(MSG.CURRENT, this.maxLen),
            listeners : {
               afterrender : function(text){
                  this.labelRef = text;
               },
               scope : this
            }
         });
      }
      Ext.apply(this, {
         items : items
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.textareaRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      return this.textareaRef.getValue() || this.renderOptions.defaultValue;
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      return this.textareaRef.isValid();
   },
   checkLenHandler : function(text, newValue)
   {
      var MSG = this.LANG_TEXT.MSG;
      if(newValue.length > this.maxLen){
         this.textareaRef.setValue(newValue.substr(0, this.maxLen));
         this.labelRef.setText(MSG.NULL);
      } else{
         this.labelRef.setText(Ext.String.format(MSG.CURRENT, this.maxLen - newValue.length));
      }
   },
   destroy : function()
   {
      delete this.labelRef;
      delete this.textareaRefRef;
      this.callParent();
   }
});