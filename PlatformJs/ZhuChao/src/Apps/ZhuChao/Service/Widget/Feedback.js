Ext.define('App.ZhuChao.Service.Widget.Feedback', {
    extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
    requires : [
        'App.ZhuChao.Service.Ui.Feedback.Feedback'
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
                xtype : 'appzhuchaoserviceuifeedbackfeedback',
                appRef : this.appRef
            }
        });
        this.callParent();
    }
});


