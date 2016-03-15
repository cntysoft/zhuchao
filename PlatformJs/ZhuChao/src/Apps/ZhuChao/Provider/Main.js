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
   extend : 'WebOs.Kernel.ProcessModel.App',
   requires : [
      'App.ZhuChao.Provider.Lang.zh_CN',
      'App.ZhuChao.Provider.Const',
      'App.ZhuChao.Provider.Widget.ProviderMgr',
      'App.ZhuChao.Provider.Widget.ComMgr'
   ],
   /**
    * @inheritdoc
    */
   id : 'ZhuChao.Provider',
   /**
    * @inheritdoc
    */
   widgetMap : {
      Entry : 'App.ZhuChao.Provider.Widget.Entry',
      ProviderMgr : 'App.ZhuChao.Provider.Widget.ProviderMgr',
      ComMgr : 'App.ZhuChao.Provider.Widget.ComMgr'
   },
   createProvider : function (values, callback, scope)
   {
      this.callApp('Mgr/addProvider', values, callback, scope);
   },
   updateProvider : function (id, values, callback, scope)
   {
      this.callApp('Mgr/updateProvider', {
         id : id,
         values : values
      }, callback, scope);
   },
   changProviderStatus : function (id, status, callback, scope)
   {
      this.callApp('Mgr/changProviderStatus', {
         id : id,
         status : status
      }, callback, scope);
   },
   getProviderInfo : function (id, callback, scope)
   {
      this.callApp('Mgr/getProviderInfo', {
         id : id
      }, callback, scope);
   },
   createProviderCompany : function (values, callback, scope)
   {
      this.callApp('ComMgr/addProviderCompany', values, callback, scope);
   },
   updateProviderCompany : function (id, values, callback, scope)
   {
      this.callApp('ComMgr/updateProviderCompany', {
         id : id,
         values : values
      }, callback, scope);
   },
   changProviderCompanyStatus : function (id, status, callback, scope)
   {
      this.callApp('ComMgr/changeCompanyStatus', {
         id : id,
         status : status
      }, callback, scope);
   },
   getProviderCompany : function (id, callback, scope)
   {
      this.callApp('ComMgr/getProviderCompany', {
         id : id
      }, callback, scope);
   },
   getProvinces : function (callback, scope)
   {
      this.callApp('ComMgr/getProvinces', {}, callback, scope);
   },
   getArea : function (value, callback, scope)
   {
      this.callApp('ComMgr/getArea', {
         code : value
      }, callback, scope);
   }
});
