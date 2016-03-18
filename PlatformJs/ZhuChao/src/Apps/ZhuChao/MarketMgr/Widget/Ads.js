/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Widget.Ads',{
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   requires : [
      'App.ZhuChao.MarketMgr.Ui.Ads.AdsLocationTree',
      'SenchaExt.Data.Proxy.ApiProxy',
      'App.ZhuChao.MarketMgr.Const'
   ],
   mixins : {
      multiTabPanel : 'SenchaExt.Mixin.MultiTabPanel'
   },
   panelClsMap : {
      AdsEditor : 'App.ZhuChao.MarketMgr.Ui.Ads.AdsEditor',
      ListView : 'App.ZhuChao.MarketMgr.Ui.Ads.ListView',
      DirectForUse : 'App.ZhuChao.MarketMgr.Ui.Ads.DirectForUse'
   },
   initPanelType : 'DirectForUse',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('ADS');
   },
   applyConstraintConfig : function(config)
   {
      this.callParent([config]);
      Ext.apply(config, {
         resizable : false,
         height : 700,
         width : 1200,
         maximizable : true,
         maximized : false,
         layout : {
            type : 'border'
         }
      });
   },
   initComponent : function()
   {
      Ext.apply(this, {
         items : [{
               xtype : 'adslocationtree',
               width : 250,
               collapsible : true,
               region : 'west',
               rootVisible : false,
               mainPanelRef : this
            },this.getTabPanelConfig()]
      });
      this.callParent();
   }
   
});

