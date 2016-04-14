/*
 * Cntysoft Cloud Software Team
 *
 *@author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.Service.Main', {
   extend : 'WebOs.Kernel.ProcessModel.App',
   requires : [
      'App.ZhuChao.Service.Lang.zh_CN',
      'App.ZhuChao.Service.Const',
      'App.ZhuChao.Service.Widget.Entry'
   ],
   id : 'ZhuChao.Service',
   widgetMap : {
      Entry : 'App.ZhuChao.Service.Widget.Entry',
      Ads : 'App.ZhuChao.Service.Widget.Ads',
      Feedback : 'App.ZhuChao.Service.Widget.Feedback'
   },
   changFeedbackStatus : function(values,callback,scope){
       this.callApp('Feedback/changFeedbackStatus',values,callback,scope);
   }
});