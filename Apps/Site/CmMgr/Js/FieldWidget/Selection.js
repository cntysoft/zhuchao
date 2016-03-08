/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.Selection', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   /**
    * @inheritdoc
    */
   langTextKey : 'FIELD_WIDGET.SELECTION',
   statics : {
      COMBO : 1,
      LIST : 2,
      RADIO : 3,
      CHECKBOX : 4
   },
   /**
    * @private
    * @property {Ext.form.field.ComboBox} comboRef
    */
   comboRef : null,
   /**
    * @private
    * @property {Ext.grid.Panel} gridRef
    */
   gridRef : null,
   /**
    * @private
    * @property {Ext.form.RadioGroup} radioGroupRef
    */
   radioGroupRef : null,
   /**
    * @private
    * @property {Ext.form.CheckboxGroup} checkboxGroupRef
    */
   checkboxGroupRef : null,
   initComponent : function()
   {
      var S = this.self;
      var renderOpt = this.renderOptions;
      var uiOption = renderOpt.uiOption;
      var selType = uiOption.selectionType  ||  S.COMBO;
      var item;
      switch (selType) {
         case  S.COMBO:
            item = this.getComboTypeConifg();
            break;
         case S.LIST:
            item = this.getListViewTypeConfig();
            break;
         case S.RADIO:
            item = this.getGroupTypeConfig(selType);
            break;
         case S.CHECKBOX:
            item = this.getGroupTypeConfig(selType);
            break;
      }
      Ext.apply(this, {
         items : item
      });
      if(selType === S.RADIO || selType === S.CHECKBOX || selType === S.LIST){
         //重新设置高度 宽度
         this.addListener('afterrender', function(){
            var target = this.items.getAt(0);
            if(selType === S.RADIO || selType === S.CHECKBOX){
               var colSize = parseInt(uiOption.colSize);
               var width = target.getWidth() * colSize;
               this.setHeight(target.getHeight());
               this.setWidth(width + 100);
            }else{
               this.setWidth(target.getWidth() + 107);
               this.setHeight(target.getHeight());
            }
            if(selType !== S.LIST){
               target.setWidth(width);
            }
         }, this);
      }
      this.callParent();
   },
   /**
    * @inheritdoc
    */
   getWrapperSize : function(renderOpt)
   {
      return {
         width : 300,
         height : 32
      };
   },
   /**
    * @return {Ext.data.Store}
    */
   createItemStore : function()
   {
      return new Ext.data.Store({
         fields : [
            {name : 'name', type : 'string', persist : false},
            {name : 'value',type : 'string', persist : false}
         ],
         data : this.getDataItems()
      });
   },
   /**
    * 获取选项数据结果
    *
    * @return {Array}
    */
   getDataItems : function()
   {
      var items = this.renderOptions.uiOption.items || [];
      var len = items.length;
      var data = [];
      var parts;
      for(var i = 0; i < len; i++) {
         parts = items[i].split('|');
         data.push({
            name : parts[0],
            value : parts[1]
         });
      }
      return data;
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      var selType = this.renderOptions.uiOption.selectionType;
      var S = this.self;
      if(selType === S.COMBO){
         this.comboRef.setValue(value);
      }else if(selType === S.LIST){
         var selModel = this.gridRef.getSelectionModel();
         var values = value.split('|');
         var selItems = [];
         this.gridRef.store.each(function(record){
            if(Ext.Array.contains(values, record.get('value'))){
               selItems.push(record);
            }
         });
         selModel.select(selItems);
      }else if(selType === S.RADIO){
         this.radioGroupRef.items.each(function(item){
            if(item.inputValue == value){
               item.setValue(true);
            }
         }, this);
      }else if(selType === S.CHECKBOX){
         var values = value.split('|');
         var selItems = [];
         this.checkboxGroupRef.items.each(function(item){
            if(Ext.Array.contains(values, item.inputValue)){
               item.setValue(true);
            }
         }, this);
      }
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      var selType = this.renderOptions.uiOption.selectionType;
      var S = this.self;
      if(selType === S.COMBO){
         return this.comboRef.getValue();
      }else if(selType === S.LIST){
         var selModel = this.gridRef.getSelectionModel();
         var sels =  selModel.getSelection();
         var ret = [];
         for(var i = 0; i < sels.length; i++){
            ret.push(sels[i].get('value'));
         }
         return ret.join('|');
      }else if(selType === S.RADIO){
         var ret = this.radioGroupRef.getValue();
         return ret.paginationType;
      }else if(selType === S.CHECKBOX){
         var ret = this.checkboxGroupRef.getValue();
         if(ret.paginationType){
            return ret.paginationType.join('|');
         }else{
            return this.renderOptions.defaultValue;
         }
      }
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      var S = this.self;
      var selType = this.renderOptions.uiOption.selectionType || S.COMBO;
      var require = this.renderOptions.require;
      var A_T = this.ABSTRACT_LANG_TEXT;
      if(selType === S.COMBO){
         return this.comboRef.isValid();
      }else if(selType === S.LIST){
         var selModel = this.gridRef.getSelectionModel();
         if(require){
            if(selModel.getCount() === 0){
               this.markEmptyError();
               return false;
            }else{
               this.clearInvalid();
               return true;
            }
         }
         return true;
      }else if(selType === S.RADIO){
         if(require){
            var valid = false;
            this.radioGroupRef.items.each(function(f){
               if(f.getValue()){
                  valid = true;
               }
            }, this);
            if(!valid){
               this.markEmptyError();
            }else{
               this.clearInvalid();
            }
            return valid;
         }else{
            return true;
         }
      }else if(selType === S.CHECKBOX){
         if(require){
            var valid = false;
            this.checkboxGroupRef.items.each(function(f){
               if(f.getValue()){
                  valid = true;
               }
            }, this);
            if(!valid){
               this.markEmptyError();
            }else{
               this.clearInvalid();
            }
            return valid;
         }else{
            return true;
         }
      }
   },
   /**
    * 解析默认值
    *
    * @param {String} defaultValue
    * @return {Array}
    */
   parseMultiDefaultValue : function(defaultValue)
   {
      var items = defaultValue.split(';');
      var data = [];
      var len = items.length;
      var parts;
      for(var i = 0; i < len; i++){
         parts = items[i].split('|');
         data.push({
            name : parts[0],
            value : parts[1]
         });
      }
      return data;
   },
   getComboTypeConifg : function()
   {
      return {
         xtype : 'combo',
         queryMode : 'local',
         displayField : 'name',
         valueField : 'value',
         width : 250,
         editable : false,
         store : this.createItemStore(),
         allowBlank : !this.renderOptions.require,
         listeners : {
            afterrender : function(self){
               this.comboRef = self;
            },
            scope : this
         }
      };
   },
   getListViewTypeConfig : function()
   {
      var GRID = this.LANG_TEXT.GRID;
      var L = this.LANG_TEXT;
      return {
         xtype : 'grid',
         height : 150,
         width : 400,
         autoScroll : true,
         border : false,
         toolTipText : L.T_TEXT.GRID,
         style : 'border : 1px solid #CCC',
         emptyText : GRID.EMPTY,
         columns : [
            {text : GRID.NAME, dataIndex : 'name', flex : 1, menuDisabled : true, resizable : false, sortable : false},
            {text : GRID.VALUE, dataIndex : 'value', flex : 2, menuDisabled : true, resizable : false, sortable : false}
         ],
         store : new Ext.data.Store({
            fields : [
               {name : 'name', type : 'string', persist : false},
               {name : 'value', type : 'string', persist : false}
            ],
            data : this.getDataItems()
         }),
         selModel : {
            allowDeselect : true,
            mode : 'MULTI'
         },
         listeners : {
            afterrender : function(grid)
            {
               this.mixins.formTooltip.setupTooltipTarget.call(this, grid);
               this.gridRef = grid;
            },
            selectionchange : function()
            {
               this.isFieldValueValid();
            },
            scope : this
         }
      };
   },
   getGroupTypeConfig : function(type)
   {
      var xtype;
      var uiOption = this.renderOptions.uiOption;
      var S = this.self;
      if(type === S.RADIO){
         xtype = 'radiogroup';
      } else{
         xtype = 'checkboxgroup';
      }
      var dataItems = this.getDataItems();
      var len = dataItems.length;
      var items = [];
      var dataItem;
      var name = this.renderOptions.name;
      for(var i = 0; i < len; i++) {
         dataItem = dataItems[i];
         items.push({
            name : name,
            boxLabel : dataItem.name,
            inputValue : dataItem.value
         });
      }
      return {
         xtype : xtype,
         columns : parseInt(uiOption.colSize),
         vertical : true,
         allowBlank : !this.renderOptions.require,
         items : items,
         listeners : {
            afterrender : function(group)
            {
               if(type === S.RADIO){
                  this.radioGroupRef = group;
               }else{
                  this.checkboxGroupRef = group;
               }
            },
            change : function()
            {
               this.isFieldValueValid();
            },
            scope : this
         }
      };
   },
   destroy : function()
   {
      delete this.comboRef;
      delete this.gridRef;
      delete this.radioGroupRef;
      delete this.checkboxGroupRef;
      this.callParent();
   }
});