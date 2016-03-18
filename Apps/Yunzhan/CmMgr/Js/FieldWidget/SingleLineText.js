/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 封装单行文本，其实就是一个单行文本的textfield
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.SingleLineText', {
   extend : 'App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @private
    * @property {Ext.form.field.Text} textRef
    */
   textRef : null,
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      var width = renderOpt.uiOption.width || 500;
      var height = renderOpt.uiOption.height || 32;
      return {
         width : parseInt(width) + 100,
         height : parseInt(height)
      };
   },
   initComponent : function()
   {
      var renderOpt = this.renderOptions;
      var uiOpt = renderOpt.uiOption;
      var width = uiOpt.width || 500;
      var height = uiOpt.height || 32;
      var item = {
         xtype : 'textfield',
         allowBlank : !renderOpt.require,
         value : renderOpt.defaultValue,
         width : parseInt(width),
         height : parseInt(height),
         validateOnChange : false,
         validateOnBlur : true,
         msgTarget : 'side',
         listeners : {
            afterrender : function(text)
            {
               this.textRef = text;
            },
            scope : this
         }
      };
      if(uiOpt.enableValidate){
         if(uiOpt.vtype !== 'self'){
            item.vtype = uiOpt.vtype;
            if(uiOpt.vTypeMsg !== ''){
               item.vtypeText = uiOpt.vTypeMsg;
            }
         } else{
            item.regex = new RegExp(uiOpt.selfRegex, 'igm');
            if(uiOpt.vTypeMsg !== ''){
               item.regexText = uiOpt.vTypeMsg;
            }
         }
      }
      Ext.apply(this,{
         items : item
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.textRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      return this.textRef.getValue();
   },

   destroy : function()
   {
      delete this.textRef;
      this.callParent();
   }
});