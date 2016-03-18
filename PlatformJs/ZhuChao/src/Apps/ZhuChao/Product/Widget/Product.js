/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 产品管理入口WIDGET
 */
Ext.define('App.ZhuChao.Product.Widget.Product', {
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   requires : [
      'App.ZhuChao.Product.Ui.Product.ListView',
      'App.ZhuChao.Product.Ui.Product.Editor',
      'App.ZhuChao.Product.Comp.GCategoryTree',
      'App.ZhuChao.Product.Comp.SearchAttrMapGenerator'
   ],
   mixins : {
      multiTabPanel : 'SenchaExt.Mixin.MultiTabPanel'
   },
   panelClsMap : {
      ListView : 'App.ZhuChao.Product.Ui.Product.ListView',
      Editor : 'App.ZhuChao.Product.Ui.Product.Editor'
   },
   /**
    * {@link WebOs.Mixin.MultiTabPanel#initPanelType initPanelType}
    * @property {String} initPanelType
    */
   initPanelType : 'ListView',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('ENTRY');
   },
   initLangTextRef : function ()
   {
      this.LANG_TEXT = this.GET_LANG_TEXT('ENTRY');
   },
   applyConstraintConfig : function (config)
   {
      this.callParent([config]);
      Ext.apply(config, {
         layout : 'border',
         width : 1000,
         minWidth : 1000,
         minHeight : 500,
         height : 500,
         resizable : true,
         bodyStyle : 'background:#ffffff',
         maximizable : true,
         maximized : true
      });
   },
   initComponent : function ()
   {
      Ext.apply(this, {
         items : [
            this.getCategoryTreePanelConfig(),
            this.getTabPanelConfig()
         ]
      });
      this.callParent();
   },
   getCategoryTreePanelConfig : function ()
   {
      return {
         xtype : 'zhuchaoproductcompgcategorytree',
         region : 'west',
         width : 260,
         margin : '0 3 0 0',
         collapsible : true,
         listeners : {
            afterrender : function (comp)
            {
               this.categoryTreeRef = comp;
            },
            itemcontextmenu : this.itemContextMenuHandler,
            itemclick : this.itemClickHandler,
            scope : this
         }
      };
   },
   itemContextMenuHandler : function (tree, record, item, index, event)
   {
      var menu = this.getContextMenu(record);
      menu.record = record;
      var pos = event.getXY();
      event.stopEvent();
      menu.showAt(pos[0], pos[1]);
   },
   getContextMenu : function (record)
   {
      var L = this.LANG_TEXT.MENU;
      if(null == this.contextMenuRef){
         this.contextMenuRef = new Ext.menu.Menu({
            ignoreParentClicks : true,
            items : [{
                  text : L.GENERATE,
                  listeners : {
                     click : function (item)
                     {
                        var record = item.parentMenu.record;
                        var builder = new App.ZhuChao.Product.Comp.SearchAttrMapGenerator({
                           appRef : this.appRef,
                           listeners : {
                              buildcomplete : function (cid)
                              {
                              },
                              deletecomplete : function (cid)
                              {
                              },
                              scope : this
                           }
                        });
                        builder.center();
                        builder.setCategoryName(record.get('text'));
                        builder.setCategoryId(record.get('id'));
                        builder.show();
                     },
                     scope : this
                  }
               }]
         });
      }
      return this.contextMenuRef;
   },
   itemClickHandler : function (tree, record)
   {
      this.renderPanel('ListView', {
         targetLoadedCid : record.get('id')
      });
   },
   panelExistHandler : function (panel, config)
   {
      if(panel.panelType == 'ListView'){
         panel.loadCategoryProduct(config.targetLoadedCid);
      }
   },
   destroy : function ()
   {
      this.callParent();
   }
});
