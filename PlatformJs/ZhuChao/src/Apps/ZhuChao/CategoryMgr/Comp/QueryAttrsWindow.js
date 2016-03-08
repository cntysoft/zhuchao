/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 筑巢商品分类管理，查询属性窗口类
 */
Ext.define('App.ZhuChao.CategoryMgr.Comp.QueryAttrsWindow', {
   extend : 'WebOs.Component.Window',
   mixins : {
      fcm : 'Cntysoft.Mixin.ForbidContextMenu',
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.CategoryMgr',
   mode : 1,
   categoryId : -1,
   /**
    * @property {Ext.form.field.ComboBox} attrNameComboRef 
    */
   attrNameComboRef : null,
   /**
    * @property {Ext.form.Panel} formRef 
    */
   formRef : null,
   /**
    * @property {Ext.grid.Panel} attrValueGridRef
    */
   attrValueGridRef : null,
   /**
    * @property {Ext.menu.Menu} contextMenuRef 
    */
   contextMenuRef : null,
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('COMP.QUERY_ATTRS_WINDOW');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function (config)
   {
      this.callParent([config]);
      Ext.apply(config, {
         layout : {
            type : 'fit'
         },
         bodyPadding : 10,
         resizable : false,
         width : 800,
         height : 450,
         modal : true,
         closeAction : 'hide',
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function ()
   {
      Ext.apply(this, {
         buttons : [{
               text : Cntysoft.GET_LANG_TEXT('UI.BTN.SAVE'),
               listeners : {
                  click : this.saveHandler,
                  scope : this
               }
            }, {
               text : Cntysoft.GET_LANG_TEXT('UI.BTN.CANCEL'),
               listeners : {
                  click : function ()
                  {
                     this.close();
                  },
                  scope : this
               }
            }],
         items : {
            xtype : 'form',
            items : [
               this.getCategoryAttrNameConfig(),
               this.getOptValueConfig(),
               this.getValueGridConfig()
            ],
            listeners : {
               afterrender : function (comp){
                  this.formRef = comp;
               },
               scope : this
            }
         }
      });
      this.addListener({
         show : function (){
            if(this.categoryId != -1){
               this.loadQueryAttrNames();
            }
         },
         close : this.closeHandler,
         scope : this
      });
      this.callParent();
   },
   setTargetCategory : function (categoryId)
   {
      this.categoryId = categoryId;
   },
   loadQueryAttrNames : function ()
   {
      this.appRef.getCategoryAttrNames(this.categoryId, function (response){
         if(!response.status){
            Cntysoft.Kernel.Utils.processApiError(response);
         } else{
            response.data.unshift({
               name : '价格'
            });
            this.attrNameComboRef.store.loadData(response.data);
         }
      }, this);

   },
   loadQueryAttrValues : function (values)
   {
      if(!this.rendered){
         this.addListener('afterrender', function (){
            this.loadQueryAttrValues(values);
         }, this);
         return;
      }
      this.formRef.getForm().setValues(values);
      //为表格赋值
      var optValues = values.optValues.split(',');
      var items = [];
      Ext.each(optValues, function (name){
         items.push({
            name : name
         });
      });

      this.attrValueGridRef.store.loadData(items);
   },
   gotoNewMode : function ()
   {
      if(this.mode != ZhuChao.Const.NEW_MODE){
         this.attrNameComboRef.setDisabled(false);
         this.mode = ZhuChao.Const.NEW_MODE;
      }
   },
   gotoModifyMode : function ()
   {
      if(this.mode != ZhuChao.Const.MODIFY_MODE){
         this.attrNameComboRef.setDisabled(true);
         this.mode = ZhuChao.Const.MODIFY_MODE;
      }
   },
   saveHandler : function ()
   {
      var form = this.formRef.getForm();
      if(form.isValid()){
         if(this.hasListeners.saverequest){
            this.attrNameComboRef.setDisabled(false);
            var formValues = form.getValues();
            var optValue = [];
            var store = this.attrValueGridRef.store;
            store.each(function (record){
               optValue.push(record.get('name'));
            });
            var values = {
               name : formValues.name
            };
            values.optValues = optValue.join(',');
            this.fireEvent('saverequest', values, this.mode);
         }
      }

      this.close();
   },
   closeHandler : function ()
   {
      this.attrNameComboRef.store.removeAll();
      this.attrValueGridRef.store.removeAll();
      this.formRef.getForm().reset();
      this.gotoNewMode();
   },
   addAttrValueHandler : function ()
   {
      var win = this.getAttrValueWin();
      win.center();
      win.show();
   },
   getCategoryAttrNameConfig : function ()
   {
      var FIELDS = this.LANG_TEXT.FIELDS;
      return {
         xtype : 'combo',
         name : 'name',
         editable : false,
         fieldLabel : FIELDS.ATTR_NAME,
         store : new Ext.data.Store({
            fields : [
               {name : 'name', type : 'string', persist : false}
            ]
         }),
         queryMode : 'local',
         displayField : 'name',
         valueField : 'name',
         allowBlank : false,
         listeners : {
            afterrender : function (comp)
            {
               this.attrNameComboRef = comp;
            },
            scope : this
         }
      };
   },
   getOptValueConfig : function ()
   {
      var FIELDS = this.LANG_TEXT.FIELDS;
      return {
         xtype : 'fieldcontainer',
         width : 700,
         layout : 'hbox',
         fieldLabel : FIELDS.ATTR_VALUES,
         items : [{
               xtype : 'textfield',
               margin : '0 10 0 0',
               width : 400
            }, {
               xtype : 'button',
               text : FIELDS.ADD,
               listeners : {
                  click : function (btn)
                  {
                     var text = btn.previousSibling();
                     var value = text.getValue().trim();
                     if(!Ext.isEmpty(value)){
                        //属性值不能重复
                        var store = this.attrValueGridRef.store;
                        if(!store.findRecord('name', value, 0, false, false, true)){
                           store.add({
                              name : value
                           });
                        }
                     }
                     text.reset();
                  },
                  scope : this
               }
            }]
      };
   },
   getValueGridConfig : function ()
   {
      var L = this.LANG_TEXT.GRID;
      return {
         xtype : 'grid',
         height : 250,
         autoScroll : true,
         border : true,
         columns : [
            {text : L.NAME, dataIndex : 'name', flex : 1, resizable : false, sortable : false, menuDisabled : true}
         ],
         store : Ext.create('Ext.data.Store', {
            fields : [
               {name : 'name', type : 'string', persist : false}
            ]
         }),
         listeners : {
            afterrender : function (grid)
            {
               this.attrValueGridRef = grid;
            },
            itemcontextmenu : this.gridItemContextMenuHandler,
            scope : this
         }
      };
   },
   gridItemContextMenuHandler : function (grid, record, item, index, event)
   {
      var menu = this.getContextMenu();
      menu.record = record;
      var pos = event.getXY();
      event.stopEvent();
      menu.showAt(pos[0], pos[1]);
   },
   getContextMenu : function ()
   {
      if(null == this.contextMenuRef){
         this.contextMenuRef = new Ext.menu.Menu({
            ignoreParentClicks : true,
            items : [{
                  text : this.LANG_TEXT.GRID.DELETE
               }],
            listeners : {
               click : function (menu)
               {
                  this.attrValueGridRef.store.remove(menu.record);
               },
               scope : this
            }
         });
      }
      return this.contextMenuRef;
   },
   destroy : function ()
   {
      delete this.attrNameComboRef;
      delete this.formRef;
      delete this.attrValueGridRef;
      if(this.contextMenuRef){
         this.contextMenuRef.destroy();
      }
      delete this.contextMenuRef;
      this.callParent();
   }
});
