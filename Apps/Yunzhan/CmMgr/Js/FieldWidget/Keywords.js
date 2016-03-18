/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.Keywords', {
   extend : 'App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.KEYWORDS',
   /**
    * @private
    * @property {Ext.form.field.Text} textRef
    */
   textRef : null,
   //private
   labelRef : null,
   initComponent : function()
   {
      var ITEMS = this.LANG_TEXT.ITEMS;
      Ext.apply(this, {
         layout : 'hbox',
         items : [{
            xtype : 'textfield',
            width : 400,
            listeners : {
               afterrender : function(text)
               {
                  this.textRef = text;
               },
               scope : this
            }
         }, {
            xtype : 'button',
            text : ITEMS.BTN,
            margin : '0 0 0 4',
            listeners : {
               click : this.getKeywordButtonHandler,
               scope : this
            }
         }, {
            xtype : 'label',
            margin : '4 0 0 4',
            text : ITEMS.LABEL,
            hidden : true,
            listeners : {
               afterrender : function(label){
                  this.labelRef = label;
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
      this.textRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      return this.textRef.getValue();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 740,
         height : 32
      };
   },
   getKeywordButtonHandler : function()
   {
      //主动请求
      if(!this.isPreviewMode){
         var title = this.editorRef.getFieldWidgetValue('title');
         if(!Ext.isEmpty(title)){ //只有标题不为空的时候才发请求
            this.labelRef.setVisible(true);
            this.editorRef.callSaverApi('getKeywords', {title : title}, function(response){
               this.labelRef.setVisible(false);
               if(!response.status){
                  Cntysoft.processApiError(response);
               } else{
                  this.textRef.setValue(response.data.keywords);
               }
            }, this);
         }
      }
   },
   destroy : function()
   {
      delete this.textRef;
      this.callParent();
   }
});