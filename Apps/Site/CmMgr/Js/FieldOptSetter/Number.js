/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 数字输入框UI设置面板
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.Number', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.NUMBER',
    /**
     * @inheritdoc
     */
    fieldType : 'Number',
    /**
     * @private
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @private
     * @property {Ext.form.field.Number} minRef
     */
    minRef : null,
    /**
     * @private
     * @property {Ext.form.field.Number} maxRef
     */
    maxRef : null,
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values = form.getValues();
            values.checkDomain = values.checkDomain == 'on' ? true : false;
            if(this.mode == 1){
                values.type = 'integer';
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
    checkDomainHandler : function(checkbox, newValue)
    {
        if(newValue){
            this.minRef.setDisabled(false);
            this.maxRef.setDisabled(false);
        }else{
            this.minRef.setDisabled(true);
            this.maxRef.setDisabled(true);
        }
    },
    /**
     * @inheritdoc
     */
    getSetterPanelConfig : function()
    {
        var A_L = this.ABSTRACT_LANG_TEXT;
        var L = this.LANG_TEXT;
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
            },{
                xtype : 'numberfield',
                fieldLabel : A_L.DEFAULT,
                width : 200,
                name : 'defaultValue',
                value : 0
            }, {
                xtype : 'numberfield',
                fieldLabel : L.STEP,
                width : 200,
                name : 'step',
                value : 1,
                minValue : 1
            },{
                xtype : 'checkbox',
                fieldLabel : L.CHECK_DOMAIN,
                name : 'checkDomain',
                listeners : {
                    change : this.checkDomainHandler,
                    scope : this
                }
            },{
                xtype : 'numberfield',
                fieldLabel : L.MIN,
                width : 200,
                name : 'min',
                value : 0,
                disabled : true,
                listeners : {
                    afterrender : function(field)
                    {
                        this.minRef = field;
                    },
                    scope : this
                }
            },{
                xtype : 'numberfield',
                fieldLabel : L.MAX,
                width : 200,
                name : 'max',
                value : 0,
                disabled : true,
                listeners : {
                    afterrender : function(field)
                    {
                        this.maxRef = field;
                    },
                    scope : this
                }
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
        delete this.minRef;
        delete this.maxRef;
        delete this.formPanelRef;
        this.callParent();
    }
});