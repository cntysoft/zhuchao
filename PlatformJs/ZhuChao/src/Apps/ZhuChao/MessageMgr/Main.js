/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 商品分类管理应用入口程序
 */
Ext.define('App.ZhuChao.MessageMgr.Main', {
   extend: 'WebOs.Kernel.ProcessModel.App',
   requires: [
      'App.ZhuChao.MessageMgr.Lang.zh_CN',
      'App.ZhuChao.MessageMgr.Const'
   ],
   /**
    * @inheritdoc
    */
   id: 'ZhuChao.MessageMgr',
   /**
    * @inheritdoc
    */
   widgetMap: {
      Entry : 'App.ZhuChao.MessageMgr.Widget.Entry',
      Offer : 'App.ZhuChao.MessageMgr.Widget.Offer'
   }
});
