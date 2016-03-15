/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 供应商管理入口WIDGET
 */
Ext.define('App.ZhuChao.Provider.Widget.ProviderMgr', {
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   requires : [
      'App.ZhuChao.Provider.Ui.ListView',
      'App.ZhuChao.Provider.Ui.Editor'
   ],
   mixins : {
      multiTabPanel : 'SenchaExt.Mixin.MultiTabPanel'
   },
   panelClsMap : {
      ListView : 'App.ZhuChao.Provider.Ui.ListView',
      Editor : 'App.ZhuChao.Provider.Ui.Editor'
   },
   /**
    * {@link WebOs.Mixin.MultiTabPanel#initPanelType initPanelType}
    * @property {String} initPanelType
    */
   initPanelType : 'ListView',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('PROVIDER');
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
            this.getTabPanelConfig()
         ]
      });
      this.callParent();
   },
   itemContextMenuHandler : function (tree, record, item, index, event)
   {
      var menu = this.getContextMenu(record);
      menu.record = record;
      var pos = event.getXY();
      event.stopEvent();
      menu.showAt(pos[0], pos[1]);
   },
   panelExistHandler : function (panel, config)
   {

   },
   destroy : function ()
   {

      this.callParent();
   }
});
