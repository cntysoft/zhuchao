/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 该类展示广告列表
 * 
 * @param {type} param1
 * @param {type} param2
 */
Ext.define('App.ZhuChao.MarketMgr.Ui.Ads.ListView',{
   extend : 'Ext.grid.Panel',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.MarketMgr',
   gridStoreRef : null,
   gridRef : null,
   contextMenuRef : null,
   constructor : function(config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('ADS.LISTVIEW');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function(config)
   {
      Ext.apply(config,{
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function()
   {
      Ext.apply(this,{
         store : this.gridStoreConfig(),
         columns : [
            {text : this.LANG_TEXT.ID, dataIndex : 'id', resizable : false, menuDisabled : true, flex : 1},
            {text : this.LANG_TEXT.NAME, dataIndex : 'name', resizable : false, menuDisabled : true, flex : 1},
            {text : this.LANG_TEXT.URL, dataIndex : 'contentUrl', resizable : false, menuDisabled : true, flex : 3},
            {text : this.LANG_TEXT.SORT, dataIndex : 'sort', resizable : false, menuDisabled : true, flex : 1}
         ],
         listeners : {
            afterrender : function(grid){
               this.gridRef = grid;
            },
            itemcontextmenu : this.itemContextMenuClickHandler,
            scope : this
         }
      });
      this.callParent();
   },
   itemContextMenuClickHandler : function(grid, record, item, index, event, eOpts)
   {
      var menu = this.createContextMenu(record);
      menu.record = record;
      var pos = event.getXY();
      event.stopEvent();
      menu.showAt(pos[0], pos[1]);
   },
   createContextMenu : function (record)
   {
      var CONST = App.ZhuChao.MarketMgr.Const;
      if(null == this.contextMenuRef){
         this.contextMenuRef = new Ext.menu.Menu({
            width : 190,
            record : record,
            items : [{
                  text : this.LANG_TEXT.MODIFY,
                  type : CONST.ADS_TYPE_MODIFY
               },{
                  text : this.LANG_TEXT.DELETE,
                  type : CONST.ADS_TYPE_DELETE
               }],
            listeners : {
                     click : this.contextMenuClickHandler,
                     scope : this
                  }
         });
      }
      return this.contextMenuRef;
   },
   contextMenuClickHandler : function(menu,items)
   {
      var CONST = App.ZhuChao.MarketMgr.Const;
      var adsId = menu.record.getId();
      if(CONST.ADS_TYPE_MODIFY == items.type){
         this.mainPanelRef.renderPanel('AdsEditor',{
            type : CONST.ADS_TYPE_MODIFY,
            record : menu.record
         });
      } else if(CONST.ADS_TYPE_DELETE == items.type){
         this.mainPanelRef.appRef.deleteAds(adsId,function(response){
            if(!response.status){
               Cntysoft.showErrorWindow(response.msg);
            } else {
               this.gridRef.store.reload();
               Cntysoft.showAlertWindow(this.LANG_TEXT.DELETESUCCESS);
            }
         },this);
      }
   },
   gridStoreConfig : function ()
   {
      if(null == this.gridStoreRef){
         this.gridStoreRef = new Ext.data.Store({
            autoLoad : true,
            pageSize : 25,
            fields : [
               {name : 'id', type : 'integer', persist : false},
               {name : 'name', type : 'string', persist : false},
               {name : 'locationId', type : 'string', persist : false},
               {name : 'contentUrl', type : 'string', persist : false},
               {name : 'startTime', type : 'string', persist : false},
               {name : 'endTime', type : 'string', persist : false},
               {name : 'gbcolor', type : 'string', persist : false},
               {name : 'image', type : 'string', persist : false},
               {name : 'fileRefs', type : 'integer', persist : false}
            ],
            proxy : {
               type : 'apigateway',
               callType : 'App',
               invokeMetaInfo : {
                  module : 'ZhuChao',
                  name : 'MarketMgr',
                  method : 'Ads/getAdsList'
               },
               pArgs : [{
                     key : 'locationId',
                     value : this.locationId
                  }],
               reader : {
                  type : 'json',
                  rootProperty : 'items',
                  totalProperty : 'total'
               }
            }
         });
      }
      return this.gridStoreRef;
   },
   destroy : function()
   {
      delete this.gridRef;
      delete this.gridStoreRef;
      if(this.contextMenuRef){
         this.contextMenuRef.destroy();
      }
      delete this.contextMenuRef;
      this.callParent();
   }
});


