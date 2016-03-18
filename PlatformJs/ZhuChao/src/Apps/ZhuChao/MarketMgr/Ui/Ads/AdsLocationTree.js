/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Ui.Ads.AdsLocationTree',{
   extend : 'Ext.tree.Panel',
   alias : 'widget.adslocationtree',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.MarketMgr',
   gridStoreRef : null,
   contextMenuRef : null,
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('ADS.ADSLOCATIONTREE');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function (config)
   {
      Ext.apply(config, {
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function()
   {
      Ext.apply(this,{
         store : this.getTreeStoreConfig(),
         listeners : {
            itemcontextmenu : this.createContextMenuHandler,
            itemclick : this.itemClickHandler,
            scope : this
         }
      });
      this.callParent();
   },
   /**
    * tree单击处理
    * 
    * @param {type} tree
    * @param {type} record
    */
   itemClickHandler : function (tree, record)
   {
      var id = record.getId();
      this.mainPanelRef.renderPanel('ListView', {
         locationId : id
      });
   },
   /**
    * 获取菜单处理事件
    * 
    * @param {type} tree
    * @param {type} record
    * @param {type} item
    * @param {type} index
    * @param {type} event
    * @returns {undefined}
    */
   createContextMenuHandler : function (tree, record, item, index, event)
   {
      if(record.data.leaf){
         var menu = this.createContextMenu(record);
         menu.record = record;
         var pos = event.getXY();
         event.stopEvent();
         menu.showAt(pos[0], pos[1]);
      }
   },
   /**
    * 获取菜单
    * 
    * @param {type} record
    * @returns {Ext.menu.Menu}
    */
   createContextMenu : function (record)
   {
      if(null == this.contextMenuRef){
         this.contextMenuRef = new Ext.menu.Menu({
            width : 190,
            record : record,
            items : [{
                  text : this.LANG_TEXT.ADDADS
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
      this.mainPanelRef.renderPanel('AdsEditor',{
         record : menu.record
      });
   },
   getTreeStoreConfig : function()
   {
      if(null == this.gridStoreRef){
         this.gridStoreRef = new SenchaExt.Data.TreeStore({
            root : {
               id : 0
            },
            fields : [
               {name : 'text', type : 'string', persist : false},
               {name : 'id', type : 'integer', persist : false}
            ],
            nodeParam : 'id',
            tree : this,
            proxy : {
               type : 'apigateway',
               callType : 'App',
               invokeMetaInfo : {
                  module : 'ZhuChao',
                  name : 'MarketMgr',
                  method : 'Ads/getAdsModuleTree'
               },
               reader : {
                  type : 'json',
                  rootProperty : 'data'
               }
            }
         });
      }
      return this.gridStoreRef;
   },
   destroy : function()
   {
      delete this.gridStoreRef;
      if(this.contextMenuRef){
         this.contextMenuRef.destroy();
      }
      delete this.contextMenuRef;
      this.callParent();
   }
});


