Ext.define('App.ZhuChao.MessageMgr.Widget.Offer', {
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('OFFER');
   },
   initLangTextRef : function ()
   {
      this.LANG_TEXT = this.GET_LANG_TEXT('OFFER');
   },
   applyConstraintConfig : function (config)
   {
      this.callParent([config]);
      Ext.apply(config, {
         width : 1300,
         height : 700
      });
   },
   initComponent : function ()
   {
      Ext.apply(this, {
         
      });
      this.callParent();
   }
});