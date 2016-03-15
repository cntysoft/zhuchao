/*
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MessageMgr.Widget.Offer', {
   extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
   requires : [
      'App.ZhuChao.MessageMgr.Ui.Offer.Offer'
   ],
   initPmTextRef : function ()
   {
      this.pmText = this.GET_PM_TEXT('OFFER');
   },
   initLangTextRef : function ()
   {
      this.LANG_TEXT = this.GET_LANG_TEXT('WIDGET.OFFER');
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
         items : {
            xtype : 'appzhuchaomessagemgruiofferoffer'
         }
      });
      this.callParent();
   }
});