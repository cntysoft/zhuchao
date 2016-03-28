/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 产品列表
 */
Ext.define('App.ZhuChao.Product.Ui.Product.ListView', {
   extend : 'Ext.grid.Panel',
   requires : [
      'App.ZhuChao.Product.Const'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /*
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.ZhuChao.Product',
   /*
    * @inheritdoc
    */
   panelType : 'ListView',
   currentPhone : null,
   /*
    * @property Ext.menu.Menu
    */
   contextMenuRef : null,
   statics : {
      A_CODES : {
         MODIFY : 1,
         VERIFY : 2,
         REJECTION : 3,
         SHELF : 4
      }
   },
   /*
    * 构造函数
    * 
    * @param {Object} config
    */
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('UI.PRODUCT.LIST_VIEW');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function (config)
   {
      Ext.apply(config, {
         border : true,
         autoScroll : true,
         title : this.LANG_TEXT.TITLE,
         emptyText : this.LANG_TEXT.EMPTY_TEXT
      });
   },
   initComponent : function ()
   {
      var COLS = this.LANG_TEXT.COLS;
      var store = this.createDataStore();
      Ext.apply(this, {
         columns : [
            {text : COLS.ID, dataIndex : 'id', width : 80, resizable : false, menuDisabled : true},
            {text : COLS.NAME, dataIndex : 'name', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.NUMBER, dataIndex : 'number', width : 300, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.PRICE, dataIndex : 'price', width : 200, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.GRADE, dataIndex : 'grade', width : 240, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.INPUT_TIME, dataIndex : 'inputTime', width : 240, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.STATUS, dataIndex : 'status', width : 140, resizable : false, sortable : false, menuDisabled : true, renderer : Ext.bind(this.statusRenderer, this)}
         ],
         store : store,
         bbar : Ext.create('Ext.PagingToolbar', {
            store : store,
            displayInfo : true,
            emptyMsg : Cntysoft.GET_LANG_TEXT('MSG.EMPTY_TEXT'),
            height : 50
         }),
         tbar : this.getTbarConfig()
      });
      this.addListener({
         afterrender : this.viewAfterrenderHandler,
         itemcontextmenu : this.itemRightClickHandler,
         itemdblclick : this.itemDbClickHandler,
         scope : this
      });
      this.callParent();
   },
   getTbarConfig : function ()
   {
      var L = this.LANG_TEXT.TBAR;
      return [{
            xtype : 'button',
            text : L.ADD,
            listeners : {
               click : function ()
               {
                  this.mainPanelRef.renderNewTabPanel('Editor', {
                     mode : WebOs.Const.NEW_MODE,
                     appRef : this.mainPanelRef.appRef
                  });
               },
               scope : this
            }
         }, {
            xtype : 'tbfill'
         }, {
            xtype : 'textfield',
            width : 400,
            name : 'name',
            emptyText : L.TIP
         }, {
            xtype : 'button',
            text : L.QUERY,
            listeners : {
               click : this.tbarQueryButtonClickHandler,
               scope : this
            }
         }];
   },
   itemDbClickHandler : function (view, record)
   {
      this.mainPanelRef.renderNewTabPanel('Editor', {
         mode : WebOs.Kernel.Const.MODIFY_MODE, /*修改模式*/
         targetLoadId : record.get('id')
      });
   },
   createDataStore : function ()
   {
      return new Ext.data.Store({
         autoLoad : false,
         fields : [
            {name : 'id', type : 'integer', persist : false},
            {name : 'name', type : 'string', persist : false},
            {name : 'number', type : 'string', persist : false},
            {name : 'price', type : 'string', persist : false},
            {name : 'grade', type : 'string', persist : false},
            {name : 'inputTime', type : 'string', persist : false},
            {name : 'status', type : 'integer', persist : false}
         ],
         proxy : {
            type : 'apigateway',
            callType : 'App',
            invokeMetaInfo : {
               module : 'ZhuChao',
               name : 'Product',
               method : 'Product/getProductList'
            },
            reader : {
               type : 'json',
               rootProperty : 'items',
               totalProperty : 'total'
            }
         },
         listeners : {
            beforeload : function (store, operation){
               if(!operation.getParams()){
                  operation.setParams({
                     phone : this.currentPhone
                  });
               }
            },
            scope : this
         }
      });
   },
   /*
    * 查询的条件键值对
    *
    * @property {Object} cond
    */
   loadUsers : function (cond)
   {
      if(this.currentPhone !== cond){
         var store = this.getStore();
         store.load({
            params : {
               name : cond
            }
         });
         this.currentPhone = cond;
      }
   },
   /*
    * 重新加载用户
    */
   reloadUsers : function ()
   {
      var store = this.getStore();
      Cntysoft.Utils.Common.reloadGridPage(store, {
         phone : this.currentPhone
      });
   },
   getContextMenu : function (record)
   {
      var CODE = this.self.A_CODES;
      var L = this.LANG_TEXT.MENU;
      var C = App.ZhuChao.Product.Const;
      if(null == this.contextMenuRef){
         var items = [{
               text : L.MODIFY,
               code : CODE.MODIFY
            }, {
               text : L.VERIFY,
               code : CODE.VERIFY
            }, {
               text : L.REJECTION,
               code : CODE.REJECTION
            }, {
               text : L.SHELF,
               code : CODE.SHELF
            }];

         this.contextMenuRef = new Ext.menu.Menu({
            ignoreParentClicks : true,
            items : items,
            listeners : {
               click : this.menuItemClickHandler,
               scope : this
            }
         });
      }

      var status = record.get('status');
      if(C.PRODUCT_STATUS_DRAFT == status || C.PRODUCT_STATUS_DELETE == status || C.PRODUCT_STATUS_SHELF == status){
         this.contextMenuRef.items.getAt(1).setDisabled(true);
         this.contextMenuRef.items.getAt(2).setDisabled(true);
         this.contextMenuRef.items.getAt(3).setDisabled(true);
      } else if(C.PRODUCT_STATUS_PEEDING == status){
         this.contextMenuRef.items.getAt(1).setDisabled(false);
         this.contextMenuRef.items.getAt(2).setDisabled(false);
         this.contextMenuRef.items.getAt(3).setDisabled(false);
      } else if(C.PRODUCT_STATUS_VERIFY == status || C.PRODUCT_STATUS_REJECTION == status){
         this.contextMenuRef.items.getAt(1).setDisabled(true);
         this.contextMenuRef.items.getAt(2).setDisabled(true);
         this.contextMenuRef.items.getAt(3).setDisabled(true);
      }

      this.contextMenuRef.record = record;
      return this.contextMenuRef;
   },
   /*
    * 更改用户的状态
    */
   changStatus : function (record, status)
   {
      this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
      this.mainPanelRef.appRef.changProductStatus({id : record.get('id'), 'status' : status}, function (response){
         this.loadMask.hide();
         if(response.status){
            record.set('status', status);
         } else{
            Cntysoft.Kernel.Utils.processApiError(response, this.LANG_TEXT.ERROR_MAP);
         }
      }, this);
   },
   loadCategoryProduct : function (cid)
   {
      if(cid != this.loadedCid){
         this.loadedCid = cid;
         var store = this.getStore();
         //将仓库当前页复位
         store.currentPage = 1;
         store.load({
            params : {
               cid : cid
            }
         });
         store.loadedCid = cid;
      }
   },
   menuItemClickHandler : function (menu, item)
   {
      if(item){
         var C = this.self.A_CODES, CONST = App.ZhuChao.Product.Const;
         var code = item.code;
         switch (code) {
            case C.MODIFY:
               this.mainPanelRef.renderNewTabPanel('Editor', {
                  mode : WebOs.Kernel.Const.MODIFY_MODE,
                  targetLoadId : menu.record.get('id')
               });
               break;
            case C.VERIFY:
               this.changStatus(menu.record, CONST.PRODUCT_STATUS_VERIFY);
               break;
            case C.REJECTION:
               this.changStatus(menu.record, CONST.PRODUCT_STATUS_REJECTION);
               break;
            case C.SHELF:
               this.changStatus(menu.record, CONST.PRODUCT_STATUS_SHELF);
               break;
         }
      }
   },
   itemRightClickHandler : function (grid, record, htmlItem, index, event)
   {
      var menu = this.getContextMenu(record);
      var pos = event.getXY();
      event.stopEvent();
      menu.showAt(pos[0], pos[1]);
   },
   tbarQueryButtonClickHandler : function (btn)
   {
      var condRef = btn.previousSibling('textfield');
      if(condRef.isValid()){
         this.loadUsers(condRef.getValue());
      }
   },
   viewAfterrenderHandler : function ()
   {
      this.loadUsers();
   },
   statusRenderer : function (value)
   {
      var U_TEXT = this.LANG_TEXT.STATUS;
      var C = App.ZhuChao.Product.Const;
      switch (value) {
         case C.PRODUCT_STATUS_DRAFT:
            return '<span>' + U_TEXT.DRAFT + '</span>';
         case C.PRODUCT_STATUS_PEEDING:
            return '<span>' + U_TEXT.PEEDING + '</span>';
         case C.PRODUCT_STATUS_VERIFY:
            return '<span>' + U_TEXT.VERIFY + '</span>';
         case C.PRODUCT_STATUS_REJECTION:
            return '<span>' + U_TEXT.REJECTION + '</span>';
         case C.PRODUCT_STATUS_SHELF:
            return '<span>' + U_TEXT.SHELF + '</span>';
         case C.PRODUCT_STATUS_DELETE:
            return '<span>' + U_TEXT.DELETE + '</span>';
      }
   },
   sexRenderer : function (value)
   {
      var U_TEXT = this.LANG_TEXT.SEX;
      var C = App.ZhuChao.Product.Const;
      switch (value) {
         case C.SEX_MAN:
            return '<span style = "color:green">' + U_TEXT.MAN + '</span>';
         case C.SEX_WOMAN:
            return '<span style = "color:red">' + U_TEXT.WOMAN + '</span>';
         case C.SEX_SECRET:
            return '<span>' + U_TEXT.SECRET + '</span>';
      }
   },
   destroy : function ()
   {
      if(this.contextMenuRef){
         this.contextMenuRef.destroy();
      }
      delete this.contextMenuRef;
      this.callParent();
   }
});
