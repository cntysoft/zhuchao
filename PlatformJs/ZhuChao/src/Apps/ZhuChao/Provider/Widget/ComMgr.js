/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license   Expression $license is undefined on line 6, column 17 in Templates/ClientSide/javascript.js.
 * 供应商企业管理
 */
Ext.define('App.ZhuChao.Provider.Widget.ComMgr', {
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   requires : [
      'App.ZhuChao.Provider.Ui.Company.ListView',
      'App.ZhuChao.Provider.Ui.Company.Editor'
   ],
   mixins : {
      multiTabPanel : 'SenchaExt.Mixin.MultiTabPanel'
   },
   panelClsMap : {
      ListView : 'App.ZhuChao.Provider.Ui.Company.ListView',
      Editor : 'App.ZhuChao.Provider.Ui.Company.Editor'
   },
   /**
    * {@link WebOs.Mixin.MultiTabPanel#initPanelType initPanelType}
    * @property {String} initPanelType
    */
   initPanelType : 'ListView',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('COMPANY');
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


