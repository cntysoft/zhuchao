/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 封装数字输入框
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Number', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @private
    * @property {Ext.form.field.Number} numberRef
    */
   numberRef : null,
   initComponent : function()
   {
      var renderOpt = this.renderOptions;
      var uiOpt = renderOpt.uiOption;
      var width = uiOpt.width || 200;
      var height = uiOpt.height || 32;
      var step = parseInt(uiOpt.step) || 1;
      var item = {
         xtype : 'numberfield',
         width : parseInt(width),
         height : parseInt(height),
         step :step,
         listeners : {
            afterrender : function(field)
            {
               this.numberRef = field;
            },
            scope : this
         },
         value : renderOpt.defaultValue
      };
      if(uiOpt.checkDomain){
         item.minValue = parseInt(uiOpt.min || 0);
         item.maxValue = parseInt(uiOpt.max || 30);
      }
      Ext.apply(this, {
         items : item
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      var width = renderOpt.uiOption.width || 200;
      var height = renderOpt.uiOption.height || 32;
      return {
         width : parseInt(width) + 100,
         height : parseInt(height)
      };
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.numberRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function(value)
   {
      return this.numberRef.getValue();
   },
   destroy : function()
   {
      delete this.numberRef;
      this.callParent();
   }
});