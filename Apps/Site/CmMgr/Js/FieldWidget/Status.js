/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Status', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.STATUS',
   statics : {
      INFO_S_DRAFT : 1,
      INFO_S_PEEDING : 2,
      INFO_S_VERIFY : 3,
      INFO_S_REJECTION : 4
   },
   /**
    * @private
    * @property {Ext.form.RadioGroup} radioGroupRef
    */
   radioGroupRef : null,
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 350,
         height : 32
      };
   },
   initComponent : function()
   {
      var ITEMS = this.LANG_TEXT.ITEMS;
      var SELF = this.self;
      Ext.apply(this, {
         items : {
            xtype : 'radiogroup',
            columns : 3,
            width : 300,
            items : [
               {boxLabel : ITEMS.DRAFT, name : 'status', inputValue : SELF.INFO_S_DRAFT},
               {boxLabel : ITEMS.REJECTION, name : 'status', inputValue : SELF.INFO_S_REJECTION},
               {boxLabel : ITEMS.VERIFY, name : 'status', inputValue : SELF.INFO_S_VERIFY, checked: true}
            ],
            listeners : {
               afterrender : function(group)
               {
                  this.radioGroupRef = group;
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
   modifyApplyHandler : function()
   {
      var data = this.editorRef.loadedValue;
      this.setFieldValue(data[this.fieldName]);
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(status)
   {
      if(!Ext.Array.contains(this.getSupportStatus(), status)){
         Cntysoft.raiseError(
            Ext.getClassName(this),
            'applyValue',
            'status : ' + status + ' is not supported'
         );
      }
      var radios = this.radioGroupRef.items;
      var SELF = this.self;
      switch (status) {
         case SELF.INFO_S_DRAFT:
            radios.getAt(0).setValue(true);
            break;
         case SELF.INFO_S_REJECTION:
            radios.getAt(1).setValue(true);
            break;
         case SELF.INFO_S_VERIFY:
            radios.getAt(2).setValue(true);
            break;
      }
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      var ret = this.radioGroupRef.getValue();
      return ret.status;
   },
   getSupportStatus : function()
   {
      var SELF = this.self;
      return [
         SELF.INFO_S_DRAFT,
         SELF.INFO_S_PEEDING,
         SELF.INFO_S_VERIFY,
         SELF.INFO_S_REJECTION
      ];
   },
   destroy : function()
   {
      delete this.radioGroupRef;
      this.callParent();
   }
});