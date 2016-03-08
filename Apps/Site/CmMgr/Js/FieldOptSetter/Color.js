/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 颜色选择器UI设置面板
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.Color', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'Color',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.COLOR',
    /**
     * @private
     * @property {Ext.form.field.Text} colorTextfieldRef
     */
    colorTextfieldRef : null,
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
            var values = form.getValues();
            if(this.mode == 1){
                values.type = 'char';
                values.length = 8;
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
        var A_L = this.ABSTRACT_LANG_TEXT;
        return {
            xtype : 'form',
            bodyPadding : 10,
            items : {
                xtype : 'fieldcontainer',
                layout : 'hbox',
                fieldLabel : A_L.DEFAULT,
                items : [{
                    xtype : 'textfield',
                    width : 100,
                    itemId : 'colorValue',
                    validator : Ext.bind(this.checkColorValue, this),
                    value : '#000000',
                    listeners : {
                        afterrender : function(text)
                        {
                            this.colorTextfieldRef = text;
                        },
                        scope : this
                    }
                }, {
                    xtype : 'button',
                    text : L.SELECT_BTN,
                    margin : '0 0 0 4',
                    menu : {
                        xtype : 'colormenu',
                        value : '000000',
                        listeners : {
                            select : this.colorSelectHandler,
                            scope : this
                        }
                    }
                }]
            },
            listeners : {
                afterrender : function(panel)
                {
                    this.formPanelRef = panel;
                },
                scope : this
            }
        };
    },
    /**
     * 检查颜色值
     *
     * @property {String} value
     */
    checkColorValue : function(value)
    {
        var MSG = this.LANG_TEXT.MSG;
        var length;
        if('#' != value.charAt(0)){
            return MSG.NOT_COLOR;
        }
        value = Ext.String.trim(value);
        length = value.length;
        if(length != 4 && length != 7){
            return MSG.WRONG_LEN;
        }
        var code;
        for(var i = 1; i < length; i++) {
            code = value.charCodeAt(i);
            if(!((code >= 48 && code <= 57) || (code >= 97 && code <= 102))){
                return Ext.String.format(MSG.NOT_0X, i + 1);
            }
        }
        return true;
    },
    /**
     * 设置颜色选择器中的颜色
     */
    colorSelectHandler : function(colorPanel, color)
    {
        this.colorTextfieldRef.setValue('#' + color.toLowerCase());
    },
    destroy : function()
    {
        delete this.formPanelRef;
        delete this.colorTextfieldRef;
        this.callParent();
    }
});