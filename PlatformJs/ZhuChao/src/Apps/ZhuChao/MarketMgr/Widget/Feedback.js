Ext.define('App.ZhuChao.MarketMgr.Widget.Feedback', {
    extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
    requires : [
        'App.ZhuChao.MarketMgr.Ui.Feedback.Feedback'
    ],
    initPmTextRef : function ()
    {
        this.pmText = this.GET_PM_TEXT('FEEDBACK');
    },
    initLangTextRef : function ()
    {
        this.LANG_TEXT = this.GET_LANG_TEXT('FEEDBACK');
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
                xtype : 'appzhuchaomarketmgruifeedbackfeedback',
                appRef : this.appRef
            }
        });
        this.callParent();
    }
});


