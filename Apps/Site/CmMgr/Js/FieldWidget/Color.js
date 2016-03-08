/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 封装颜色选择器
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Color', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.COLOR',
   /**
    * @property {Ext.form.field.Text} colorTextfieldRef
    */
   colorTextfieldRef : null,
   initComponent : function()
   {
      var ITEMS = this.LANG_TEXT.ITEMS;
      Ext.apply(this, {
         layout : 'hbox',
         items : [{
            xtype : 'textfield',
            width : 100,
            validator : Ext.bind(this.checkColorValue, this),
            value : '#000000',
            listeners : {
               afterrender : function(text)
               {
                  this.colorTextfieldRef = text;
               },
               scope : this
            }
         }, {
            xtype : 'button',
            text : ITEMS.BTN,
            margin : '0 0 0 4',
            menu : {
               xtype : 'colormenu',
               value : '000000',
               listeners : {
                  select : this.colorSelectHandler,
                  scope : this
               }
            }
         }]
      });
      this.callParent();
   },
   /**
    * @onheritdoc
    */
   setFieldValue : function(value)
   {
      this.colorTextfieldRef.setValue(value);
   },
   /**
    * @onheritdoc
    */
   getFieldValue : function()
   {
      return this.colorTextfieldRef.getValue();
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      return  this.colorTextfieldRef.isValid();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 450,
         height : 32
      };
   },
   /**
    * 检查颜色值
    *
    * @property {String} value
    */
   checkColorValue : function(value)
   {
      var MSG = this.LANG_TEXT.MSG;
      var length;
      if('#' != value.charAt(0)){
         return MSG.NOT_COLOR;
      }
      value = Ext.String.trim(value);
      length = value.length;
      if(length != 4 && length != 7){
         return MSG.WRONG_LEN;
      }
      var code;
      for(var i = 1; i < length; i++) {
         code = value.charCodeAt(i);
         if(!((code >= 48 && code <= 57) || (code >= 97 && code <= 102))){
            return Ext.String.format(MSG.NOT_0X, i + 1);
         }
      }
      return true;
   },
   /**
    * 设置颜色选择器中的颜色
    */
   colorSelectHandler : function(colorPanel, color)
   {
      this.colorTextfieldRef.setValue('#' + color.toLowerCase());
   },
   destroy : function()
   {
      delete this.colorTextfieldRef;
      this.callParent();
   }
});