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
   currentName : null,
   loadedCid : 0,
   companyId : 0,
   status : 0,
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
      if('targetLoadedCid' in config){
         this.loadedCid = config.targetLoadedCid;
      }
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
            {text : COLS.COMPANY_NAME, dataIndex : 'companyName', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.NUMBER, dataIndex : 'number', width : 220, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.PRICE, dataIndex : 'price', width : 200, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.GRADE, dataIndex : 'grade', width : 70, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.INPUT_TIME, dataIndex : 'inputTime', width : 200, resizable : false, sortable : false, menuDisabled : true},
            {text : COLS.STATUS, dataIndex : 'status', width : 100, resizable : false, sortable : false, menuDisabled : true, renderer : Ext.bind(this.statusRenderer, this)}
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
            xtype : 'combo',
            fieldLabel : '状态',
            store : Ext.create('Ext.data.Store', {
               fields : ['text', 'status'],
               data : [
                  { text : '不限', status : '0'},
                  { text : '审核中', status : '2'},
                  { text : '已审核', status : '3'},
                  { text : '已拒绝', status : '4'},
                  { text : '已下架', status : '5'},
                  { text : '已删除', status : '6'}
               ]
            }),
            value : 0,
            queryMode : 'local',
            displayField : 'text',
            valueField : 'status',
            listeners : {
               afterrender : function(status)
               {
                  this.statusRef = status;
               },
               select : function(combo, record){
                  this.status = record.get('status');
               },
               scope : this
            }
         }, {
            xtype : 'combo',
            fieldLabel : '商家名称',
            store : this.createCompanyStore(),
            queryMode : 'local',
            displayField : 'text',
            valueField : 'companyId',
            value : 0,
            listeners : {
               focus : function (combo)
               {
                  combo.expand();
               },
               afterrender : function(company)
               {
                  this.companyRef = company;
               },
               select : function(combo, record){
                  this.companyId = record.get('companyId');
               },
               scope : this
            }
         }, {
            xtype : 'textfield',
            width : 400,
            name : 'name',
            emptyText : L.TIP,
            listeners : {
               change : function(textfield, newValue){
                  this.currentName = newValue;
               },
               scope : this
            }
         }, {
            xtype : 'button',
            text : L.QUERY,
            listeners : {
               click : this.tbarQueryButtonClickHandler,
               scope : this
            }
         }];
   },
   
   createCompanyStore : function ()
   {
      return new Ext.data.Store({
         autoLoad : true,
         fields : [
            {name : 'companyId', type : 'integer', persist : false},
            {name : 'text', type : 'string', persist : false}
         ],
         proxy : {
            type : 'apigateway',
            callType : 'App',
            invokeMetaInfo : {
               module : 'ZhuChao',
               name : 'Product',
               method : 'Product/getCompanyList'
            },
            reader : {
               type : 'json',
               rootProperty : 'items',
               totalProperty : 'total'
            }
         }
      });
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
                  operation.setParams(this.getCondParams());
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
   loadProducts : function (cond)
   {
      var store = this.getStore();
      store.load({
         params : this.getCondParams()
      });
   },
   
   getContextMenu : function (record)
   {
      var CODE = this.self.A_CODES;
      var L = this.LANG_TEXT.MENU;

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

      this.contextMenuRef.record = record;
      return this.contextMenuRef;
   },
   
   /*
    * 更改状态
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
            params : this.getCondParams()
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
   
   tbarQueryButtonClickHandler : function ()
   {
      this.loadProducts();
   },
   
   getCondParams : function()
   {
      var params = {};
      
      if(this.currentName){
         params.name = this.currentName;
      }
      
      if(this.companyId){
         params.companyId = this.companyId;
      }
      
      if(this.status){
         params.status = this.status;
      }
      
      if(this.loadedCid){
         params.cid = this.loadedCid;
      }
      
      return params;
   },
   
   viewAfterrenderHandler : function ()
   {
      this.loadProducts();
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
