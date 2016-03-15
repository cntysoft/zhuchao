/*
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 节点管理程序
 */
Ext.define('App.ZhuChao.Provider.Widget.Entry', {
   extend : 'WebOs.OsWidget.TreeNavWidget',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('ENTRY');
   }
});