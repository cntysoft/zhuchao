/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 采购会员管理入口WIDGET
 */
Ext.define('App.ZhuChao.Buyer.Widget.Buyer', {
    extend : 'WebOs.Kernel.ProcessModel.AbstractWidget',
    requires : [
        'App.ZhuChao.Buyer.Ui.Buyer.ListView',
        'App.ZhuChao.Buyer.Ui.Buyer.Editor'
    ],
    mixins : {
        multiTabPanel : 'SenchaExt.Mixin.MultiTabPanel'
    },
    panelClsMap : {
        ListView : 'App.ZhuChao.Buyer.Ui.Buyer.ListView',
        Editor : 'App.ZhuChao.Buyer.Ui.Buyer.Editor'
    },
    /**
     * {@link WebOs.Mixin.MultiTabPanel#initPanelType initPanelType}
     * @property {String} initPanelType
     */
    initPanelType : 'ListView',
    initPmTextRef : function ()
    {
        this.pmText = this.GET_PM_TEXT('ENTRY');
    },
    initLangTextRef : function ()
    {
        this.LANG_TEXT = this.GET_LANG_TEXT('ENTRY');
    },
    applyConstraintConfig : function (config)
    {
        this.callParent([config]);
        Ext.apply(config, {
            layout : 'border',
            width : 1000,
            minWidth : 1000,
            minHeight : 500,
            height : 500,
            resizable : true,
            bodyStyle : 'background:#ffffff',
            maximizable : true,
            maximized : true
        });
    },
    initComponent : function ()
    {
        Ext.apply(this, {
            items : [
                this.getTabPanelConfig()
            ]
        });
        this.callParent();
    },
    destroy : function ()
    {
        this.callParent();
    }
});
