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
Ext.define('App.ZhuChao.Provider.Main', {
   extend: 'WebOs.Kernel.ProcessModel.App',
   requires: [
      'App.ZhuChao.Provider.Lang.zh_CN',
      'App.ZhuChao.Provider.Const'
   ],
   /**
    * @inheritdoc
    */
   id: 'ZhuChao.Provider',
   /**
    * @inheritdoc
    */
   widgetMap: {
      Entry: 'App.ZhuChao.Provider.Widget.Entry'
   },
   createProvider : function(values, callback, scope)
   {
       this.callApp('Mgr/addProvider', values, callback, scope);
   },
   updateProvider : function(id, values, callback, scope)
   {
       this.callApp('Mgr/updateProvider', {
           id : id,
           values : values
       }, callback, scope);
   },
   changProviderStatus : function(id, status, callback, scope)
   {
       this.callApp('Mgr/changProviderStatus', {
           id : id,
           status : status
       }, callback, scope);
   },
   getProviderInfo : function(id, callback, scope)
   {
       this.callApp('Mgr/getProviderInfo', {
           id : id
       }, callback, scope);
   }
});
