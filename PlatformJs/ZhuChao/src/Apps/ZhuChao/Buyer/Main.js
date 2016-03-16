/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 采购会员管理应用入口程序
 */
Ext.define('App.ZhuChao.Buyer.Main', {
   extend: 'WebOs.Kernel.ProcessModel.App',
   requires: [
      'App.ZhuChao.Buyer.Lang.zh_CN',
      'App.ZhuChao.Buyer.Const',
      'App.ZhuChao.Buyer.Widget.Entry',
      'App.ZhuChao.Buyer.Widget.Buyer'
   ],
   /**
    * @inheritdoc
    */
   id: 'ZhuChao.Buyer',
   /**
    * @inheritdoc
    */
   widgetMap: {
      Entry: 'App.ZhuChao.Buyer.Widget.Entry',
      BuyerMgr: 'App.ZhuChao.Buyer.Widget.Buyer'
   },
   createBuyer : function(values, callback, scope)
   {
       this.callApp('Buyer/addBuyer', values, callback, scope);
   },
   updateBuyer : function(id, values, callback, scope)
   {
       this.callApp('Buyer/updateBuyer', {
           id : id,
           values : values
       }, callback, scope);
   },
   changBuyerStatus : function(data, callback, scope)
   {
       this.callApp('Buyer/changBuyerStatus', data, callback, scope);
   },
   getBuyerInfo : function(id, callback, scope)
   {
       this.callApp('Buyer/getBuyerInfo', {
           id : id
       }, callback, scope);
   }
});
