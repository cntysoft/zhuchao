/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.CategoryTree', {
   extend : 'App.Site.CmMgr.Comp.CategoryTree',
   alias : 'widget.cmfcategorytreewidget',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.Site.CmMgr',
   constructor : function(config)
   {
      this.LANG_TEXT = this.GET_LANG_TEXT('FIELD_WIDGET.CATEGORY_TREE');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function(config)
   {
      this.callParent([config]);
      Ext.apply(config, {
         /**
          * 允许出现的节点类型
          */
         allowTypes : [3],
         extraFields : [],
         width : 200,
         style : 'border-right : 1px #157FCC solid;',
         margin : 1,
         collapsible : true,
         title : this.LANG_TEXT.TITLE
      });
   }
});