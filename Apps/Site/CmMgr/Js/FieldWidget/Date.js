/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 封装日期选择
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Date', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @private
    * @property {Ext.form.field.Date} dateFieldRef
    */
   dateFieldRef: null,

   initComponent : function()
   {
      var renderOpt = this.renderOptions;
      var uiOpt = renderOpt.uiOption;
      var item = {
         xtype : 'datefield',
         width : 200,
         height : 32,
         listeners : {
            afterrender : function(field)
            {
               this.dateFieldRef = field;
            },
            scope : this
         },
         value: new Date()
      };
      if(uiOpt.format){
         item.format = uiOpt.format;
      }else{
         item.format = 'Y-m-d H:i:s';
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
      return {
         width : 300,
         height : 25
      };
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      return this.dateFieldRef.isValid();
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.dateFieldRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function(value)
   {
      return this.dateFieldRef.getValue();
   },
   destroy : function()
   {
      delete this.dateFieldRef;
      this.callParent();
   }
});