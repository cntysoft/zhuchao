/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 复选框UI设置面板
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.Checkbox', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'Checkbox',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.CHECKBOX',
    /**
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values =  form.getValues();
            values.defaultValue = values.defaultValue === 'on' ? true : false;
            if(this.mode == 1){
                values.type = 'boolean';
            }
            return values;
        }
    },
    /**
     * 设置设置参数
     *
     * @param {Object} values
     */
    applyTypeOptValues : function(values)
    {
        this.formPanelRef.getForm().setValues(values);
    },
    /**
     * 还原类型参数设置
     */
    restoreTypeOptValues : function()
    {
        this.formPanelRef.getForm().setValues(this.initUiOpt);
    },
    /**
     * @inheritdoc
     */
    getSetterPanelConfig : function()
    {
        var L = this.LANG_TEXT;
        return {
            xtype : 'form',
            bodyPadding : 10,
            items : [{
                xtype : 'checkbox',
                fieldLabel : L.IS_SELECTED,
                name : 'defaultValue'
            }],
            listeners : {
                afterrender : function(panel)
                {
                    this.formPanelRef = panel;
                },
                scope : this
            }
        };
    },
    destroy : function()
    {
        delete this.formPanelRef;
        this.callParent();
    }
});