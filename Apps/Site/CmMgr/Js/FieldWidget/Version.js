/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Version', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @private
    * @property {Ext.form.field.Number} majorRef
    */
   majorRef : null,
   /**
    * @private
    * @property {Ext.form.field.Number} minor
    */
   majorRef : null,
   /**
    * @private
    * @property {Ext.form.field.Number} patchRef
    */
   patchRef : null,
   initComponent : function()
   {
      Ext.apply(this, {
         layout : {
            type : 'hbox',
            align : 'top',
            padding : '0 0 4 0'
         },
         items : [{
            xtype : 'numberfield',
            width : 50,
            value : 0,
            minValue : 0,
            listeners : {
               afterrender : function(comp)
               {
                  this.majorRef = comp;
               },
               scope : this
            }
         },{
            xtype : 'numberfield',
            width : 50,
            value : 0,
            minValue : 0,
            margin : '0 0 0 4',
            listeners : {
               afterrender : function(comp)
               {
                  this.majorRef = comp;
               },
               scope : this
            }
         },{
            xtype : 'numberfield',
            width : 50,
            value : 0,
            minValue : 0,
            margin : '0 0 0 4',
            listeners : {
               afterrender : function(comp)
               {
                  this.patchRef = comp;
               },
               scope : this
            }
         }]
      });
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      var parts = value.split('.');
      this.majorRef.setValue(parts[0]);
      this.majorRef.setValue(parts[1]);
      this.patchRef.setValue(parts[2]);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      return this.majorRef.getValue() + '.'+this.majorRef.getValue()+'.'+this.patchRef.getValue();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 270
      };
   },
   destroy : function()
   {
      delete this.majorRef;
      delete this.majorRef;
      delete this.patchRef;
      this.callParent();
   }
});