/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 使用说明
 * 
 * @param {type} param1
 * @param {type} param2
 */
Ext.define('App.ZhuChao.MarketMgr.Ui.Ads.DirectForUse',{
   extend : 'Ext.panel.Panel',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.MarketMgr',
   constructor : function(config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('ADS.DIRECTFORUSE');
      this.applyConstraintConfig(config);
      this.callParent([config]);
   },
   applyConstraintConfig : function(config)
   {
      Ext.apply(config,{
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function()
   {
      Ext.apply(this,{
         html : this.LANG_TEXT.HTML
      });
      this.callParent();
   },
   destroy : function()
   {
      this.callParent();
   }
});


