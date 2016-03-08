/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 封装复选框
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Checkbox', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @private
    * @property {Ext.form.field.Checkbox} checkboxRef
    */
   checkboxRef : null,
   initComponent : function()
   {
      Ext.apply(this, {
         items : {
            xtype : 'checkbox',
            listeners : {
               afterrender : function(box)
               {
                  this.checkboxRef = box;
               },
               scope : this
            }
         }
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 120,
         height : 32
      };
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      this.checkboxRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      return this.checkboxRef.getValue();
   },
   destroy : function()
   {
      delete this.checkboxRef;
      this.callParent();
   }
});