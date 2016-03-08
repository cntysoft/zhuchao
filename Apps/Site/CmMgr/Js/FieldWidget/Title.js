/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Title', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.TITLE',
   /**
    * 标题的长度
    *
    * @property {Number} maxLen
    */
   maxLen : 512,
   /**
    * @private
    * @property {Ext.form.field.Text} textRef
    */
   textRef : null,
   /**
    * @private
    * @property {Ext.form.Label} lenLabelRef
    */
   lenLabelRef : null,
   initComponent : function()
   {
      var ITEMS = this.LANG_TEXT.ITEMS;
      var MSG = this.LANG_TEXT.MSG;
      var renderOpt = this.renderOptions;
      var width = renderOpt.uiOption.width || 520;
      Ext.apply(this, {
         layout : 'hbox',
         items : [{
            xtype : 'textfield',
            width : width,
            itemId : 'titleTextField',
            name : renderOpt.name,
            allowBlank : false,
            blankText : ITEMS.BLANK_TEXT,
            msgTarget : 'side',
            listeners : {
               afterrender : function(text){
                  this.textRef = text;
               },
               change : this.lenCheckHandler,
               scope : this
            }
         }, {
            xtype : 'button',
            text : ITEMS.BTN,
            margin : '0 0 0 4',
            listeners : {
               click : this.checkUniqueHandler,
               scope : this
            }
         }, {
            xtype : 'label',
            text : Ext.String.format(MSG.LAST, this.maxLen),
            margin : '4 0 0 4',
            listeners : {
               afterrender : function(label){
                  this.lenLabelRef = label;
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
   isFieldValueValid : function()
   {
      return this.textRef.isValid();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      var width = renderOpt.uiOption.width || 500;
      width += 460;
      return {
         width : width
      };
   },
   checkUniqueHandler : function()
   {
      if(!this.isPreviewMode){
         var needCheck = true;
         var MSG = this.LANG_TEXT.MSG;
         var orgText = this.lenLabelRef.text;
         var newTitle = this.textRef.getValue();
         if(WebOs.Const.MODIFY_MODE == this.editorRef.mode){
            if(newTitle == this.editorRef.loadedValue.title){
               needCheck = false;
            }
         }
         if(needCheck){
            var nid = this.editorRef.getFieldWidgetValue('nodeId');
            var mid = this.editorRef.modelId;
            this.lenLabelRef.setText(MSG.CHECKING);
            this.editorRef.callCmApi('infoIsExist',
               nid,
               mid,
               newTitle, function(response){
                  this.lenLabelRef.setText(orgText);
                  if(response.status){
                     if(response.data.exist){
                        this.textRef.markInvalid(MSG.INVALID);
                     }
                  } else{
                     Cntysoft.processApiError(response);
                  }
               }, this);
         } else{
            this.lenLabelRef.setText(MSG.NOT_DIRTY);
         }
      }
   },
   lenCheckHandler : function(text, value)
   {
      var MSG = this.LANG_TEXT.MSG;
      if(value.length > this.maxLen){
         this.textRef.setValue(value.substr(0, this.maxLen));
         this.lenLabelRef.setText(Ext.String.format(MSG.LAST, 0));
      } else{
         this.lenLabelRef.setText(Ext.String.format(MSG.LAST, this.maxLen - value.length));
      }
   },
   destroy : function()
   {
      delete this.textRef;
      delete this.lenLabelRef;
      this.callParent();
   }
});