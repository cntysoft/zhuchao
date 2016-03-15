/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 产品管理应用入口程序
 */
Ext.define('App.ZhuChao.Product.Main', {
   extend: 'WebOs.Kernel.ProcessModel.App',
   requires: [
      'App.ZhuChao.Product.Lang.zh_CN',
      'App.ZhuChao.Product.Const',
      'App.ZhuChao.Product.Widget.Product'
   ],
   /**
    * @inheritdoc
    */
   id: 'ZhuChao.Product',
   /**
    * @inheritdoc
    */
   widgetMap: {
      Entry: 'App.ZhuChao.Product.Widget.Entry',
      ProductMgr : 'App.ZhuChao.Product.Widget.Product'
   },
   addProductInfo : function(values, callback, scope)
   {
       this.callApp('Product/addProduct', values, callback, scope);
   },
   updateProductInfo : function(values, callback, scope)
   {
       this.callApp('Product/updateProduct', values, callback, scope);
   },
   getProductInfo : function(id, callback, scope)
   {
       this.callApp('Product/getProductInfo', {
           id : id
       }, callback, scope);
   },
   getCategoryAttrs : function(cid, callback, scope)
   {
      this.callApp('Product/getCategoryAttrs', {categoryId : cid}, callback, scope);
   },
   getCompanyList : function(callback, scope)
   {
      this.callApp('Product/getCompanyList', {}, callback, scope);
   }
});
