/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter', {
    extend : 'Ext.panel.Panel',
    mixins : {
        langTextProvider : 'WebOs.Mixin.RunableLangTextProvider',
        formTooltip : 'Cntysoft.Mixin.FormTooltip'
    },
    requires : [
        'Cntysoft.Utils.HtmlTpl'
    ],
    /**
     * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
     *
     * @property {String} runableLangKey
     */
    runableLangKey : 'App.Site.CmMgr',
    /**
     * 字段类型
     *
     * @property {String} fieldType
     */
    fieldType : null,
    /**
     * 修改模式下应用初始值
     *
     * @property {Object} initUiOpt
     */
    initUiOpt : null,
    /**
     * 当前的状态
     *
     * @private
     * @property {Number} mode
     */
    mode : null,
    /**
     * 语言KEY
     *
     * @property {String} langTextKey
     */
    langTextKey : null,
    /**
     * @property {Object} ABSTRACT_LANG_TEXT
     */
    ABSTRACT_LANG_TEXT : null,
    constructor : function(config)
    {
        config = config || {};
        this.LANG_TEXT = this.GET_LANG_TEXT(this.langTextKey);
        this.ABSTRACT_LANG_TEXT = this.GET_LANG_TEXT('FIELD_OPT_SETTER');
        this.mixins.formTooltip.constructor.call(this);
        this.applyConstraintConfig(config);
        this.callParent([config]);
    },
    applyConstraintConfig : function(config)
    {
        Ext.apply(config, {
            title : this.LANG_TEXT.TITLE,
            layout : 'fit',
            border : true
        });
    },
    initComponent : function()
    {
        Ext.apply(this, {
            bodyPadding : 10,
            items : this.getSetterPanelConfig()
        });
        if(this.initUiOpt){
            this.addListener('afterrender', function(){
                this.applyTypeOptValues(this.initUiOpt);
            }, this, {
                single : true
            });
        }
        this.callParent();
    },
    /**
     * 获取设置面板的值
     *
     * @returns {Object}
     */
    getTypeOptValues : function()
    {
        var values = this.doGetValuesHandler() || {};
        Ext.apply(values, {
            fieldType : this.fieldType
        });
        Ext.applyIf(values, this.initUiOpt);//有些字段是必须保持的
        return values;
    },
    /**
     * 获取相关值的处理函数
     *
     * @template
     * @return {Object}
     */
    doGetValuesHandler : Ext.emptyFn,
    /**
     * 设置设置参数
     *
     * @param {Object} values
     */
    applyTypeOptValues : Ext.emptyFn,
    /**
     * 还原类型参数设置
     */
    restoreTypeOptValues : Ext.emptyFn,
    /**
     * 获取配置面板的配置对象
     *
     * @return {Object}
     */
    getSetterPanelConfig : Ext.emptyFn,
    /**
     * 选项是否合法
     *
     * @return {Boolean}
     */
    isSettingValid : function()
    {
        return true;
    },
    destroy : function()
    {
        this.mixins.langTextProvider.destroy.call(this);
        this.mixins.formTooltip.destroy.call(this);
        delete this.initUiOpt;
        delete this.mode;
        delete this.ABSTRACT_LANG_TEXT;
        this.callParent();
    }
});