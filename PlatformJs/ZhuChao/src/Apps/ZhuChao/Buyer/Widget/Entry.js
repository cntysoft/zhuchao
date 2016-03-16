/*
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license   http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 采购商管理程序
 */
Ext.define('App.ZhuChao.Buyer.Widget.Entry', {
   extend : 'WebOs.OsWidget.TreeNavWidget',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('ENTRY');
   }
});

