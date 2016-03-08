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
Ext.define('App.ZhuChao.CategoryMgr.Main', {
   extend: 'WebOs.Kernel.ProcessModel.App',
   requires: [
      'App.ZhuChao.CategoryMgr.Lang.zh_CN',
      'App.ZhuChao.CategoryMgr.Const'
   ],
   /**
    * @inheritdoc
    */
   id: 'ZhuChao.CategoryMgr',
   /**
    * @inheritdoc
    */
   widgetMap: {
      Entry: 'App.ZhuChao.CategoryMgr.Widget.Entry'
   },
   addNode: function(data, callback, scope)
   {
      this.callApp('Mgr/addNode', data, callback, scope);
   },
   getNodeInfo: function(nid, callback, scope)
   {
      this.callApp('Mgr/getNodeInfo', {
         nid: nid
      }, callback, scope);
   },
   updateNodeInfo: function(data, callback, scope)
   {
      this.callApp('Mgr/updateNodeInfo', data, callback, scope);
   },
   getCategoryAttrNames: function(categoryId, callback, scope)
   {
      this.callApp('Mgr/getCategoryAttrNames', {
         cid: categoryId
      }, callback, scope);
   },
   saveCategoryQueryAttrs : function(categoryId, attrs, callback, scope)
   {
      this.callApp('Mgr/saveCategoryQueryAttrs', {
         categoryId: categoryId,
         attrs : attrs
      }, callback, scope);
   },
   getCategoryQueryAttrs : function(categoryId, callback, scope)
   {
      this.callApp('Mgr/getCategoryQueryAttrs', {
         categoryId: categoryId
      }, callback, scope);
   },
   getStdCategories : function(id, callback, scope) 
   {
      this.callApp('Mgr/getStdCategoryList', {
         id: id
      }, callback, scope);
   },
   getNodeStdAttrs : function(id, callback, scope)
   {
      this.callApp('Mgr/getCategoryStdAttrs', {
         id: id
      }, callback, scope);
   },
   saveNodeStdAttrs : function(data, callback, scope)
   {
      this.callApp('Mgr/saveCategoryStdAttrs', data, callback, scope);
   }
});