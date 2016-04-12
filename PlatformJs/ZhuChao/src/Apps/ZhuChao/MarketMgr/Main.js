/*
 * Cntysoft Cloud Software Team
 *
 *@author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Main', {
   extend : 'WebOs.Kernel.ProcessModel.App',
   requires : [
      'App.ZhuChao.MarketMgr.Lang.zh_CN',
      'App.ZhuChao.MarketMgr.Const',
      'App.ZhuChao.MarketMgr.Widget.Entry'
   ],
   id : 'ZhuChao.MarketMgr',
   widgetMap : {
      Entry : 'App.ZhuChao.MarketMgr.Widget.Entry',
      Ads : 'App.ZhuChao.MarketMgr.Widget.Ads',
      Feedback : 'App.ZhuChao.MarketMgr.Widget.Feedback'
   },
   addAds : function(values, callback, scope)
   {
      this.callApp('Ads/addAds',values,callback,scope);
   },
   modifyAds : function(values, callback, scope)
   {
      this.callApp('Ads/modifyAds',values,callback,scope);
   },
   deleteAds : function(adsId, callback,scope)
   {
      this.callApp('Ads/deleteAds',{id : adsId},callback,scope);
   },
   changFeedbackStatus : function(values,callback,scope){
       this.callApp('Feedback/changFeedbackStatus',values,callback,scope);
   }
});