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
Ext.define('App.ZhuChao.Product.Widget.Entry', {
   extend : 'WebOs.OsWidget.TreeNavWidget',
   initPmTextRef : function()
   {
      this.pmText = this.GET_PM_TEXT('ENTRY');
   }
});
