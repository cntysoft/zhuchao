/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldWidget.DictSelection', {
   extend : 'App.Yunzhan.CmMgr.Lib.FieldWidget.AbstractField',
   requires : [
      'WebOs.Component.KvDict.View'
   ],
   /**
    * @private
    * @property {Ext.form.field.Text} text
    */
   textRef : null,
   /**
    * @private
    * @property {Array} selectedItems
    */
   selectedItems : [],
   /**
    * @private
    * @property {Cntysoft.Component.KvDict.View} kvDictViewRef
    */
   kvDictViewRef : null,
   initComponent : function()
   {
      var renderOpt = this.renderOptions;
      var uiOpt = renderOpt.uiOption;
      Ext.apply(this,{
         layout : 'hbox',
         items : [{
            width : parseInt(uiOpt.textWidth),
            xtype : 'textfield',
            allowBlank : !renderOpt.require,
            listeners : {
               afterrender : function(text)
               {
                  this.textRef = text;
               },
               scope : this
            }
         },{
            xtype : 'button',
            text : uiOpt.btnText,
            margin : '0 0 0 4',
            listeners : {
               click : this.selectValueHandler,
               scope : this
            }
         }]
      });
      this.addListener('afterrender', function(){
         this.setWidth(this.textRef.getWidth() + this.down('button').getWidth()+ 110);
      }, this);
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      //需要重新设置
      return {
         width : 300,
         height : 32
      };
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
   setFieldValue : function(value)
   {
      this.textRef.setValue(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function(value)
   {
      return this.textRef.getValue();
   },
   selectValueHandler : function()
   {
      if(this.kvDictViewRef === null){
         var uiOpt = this.renderOptions.uiOption;
         this.kvDictViewRef = new WebOs.Component.KvDict.View({
            targetMapKey : uiOpt.kvDictKey,
            allowMulti : uiOpt.multiSelect,
            listeners : {
               itemselected : this.itemSelectedHandler,
               scope : this
            }
         });
      }else{
         this.kvDictViewRef.selectItems(this.selectedItems);
      }
      this.kvDictViewRef.center();
      this.kvDictViewRef.show();
   },
   itemSelectedHandler : function(items)
   {
      this.selectedItems = items;
      var text = [];
      for(var i = 0; i < items.length; i++){
         text.push(items[i].get('value'));
      }
      this.setFieldValue(text.join(','));
   },
   destroy : function()
   {
      delete this.textRef;
      delete this.kvDictViewRef;
      this.callParent();
   }
});