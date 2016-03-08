/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 封装单行文本，其实就是一个单行文本的textfield
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.Title', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'Title',
    /**
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.TITLE',
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values =  form.getValues();
            values.defatultValue = null;
            if(this.mode == 1){
                values.type = 'varchar';
                values.length = 255;
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
        var F = this.LANG_TEXT.FIELDS;
        var A_L = this.ABSTRACT_LANG_TEXT;
        var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
        return {
            xtype : 'form',
            bodyPadding : 10,
            items : [{
                xtype : 'numberfield',
                fieldLabel : A_L.WIDTH + R_STAR,
                value : 500,
                minValue : 0,
                step : 10,
                name : 'width'
            }, {
                xtype : 'numberfield',
                fieldLabel : A_L.HEIGHT + R_STAR,
                minValue : 25,
                value : 25,
                name : 'height'
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