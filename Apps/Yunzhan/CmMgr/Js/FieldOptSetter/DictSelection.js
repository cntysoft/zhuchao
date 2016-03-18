/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 字典字段设置程序
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldOptSetter.DictSelection', {
    extend : 'App.Yunzhan.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'DictSelection',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.DICT_SELECTION',
    /**
     * @inheritdoc
     */
    isSettingValid : function()
    {
        return this.formPanelRef.getForm().isValid();
    },
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        var values = form.getValues();
        values.multiSelect = values.multiSelect ? true : false;
        if(this.mode == 1){
            values.type = 'varchar';
        }
        return values;
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
        var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
        return {
            xtype : 'form',
            bodyPadding : 10,
            defaults : {
                listeners : {
                    afterrender : function(formItem)
                    {
                        this.mixins.formTooltip.setupTooltipTarget.call(this, formItem);
                    },
                    scope : this
                }
            },
            items : [this.getDictKeyComboConfig(), {
                xtype : 'textfield',
                fieldLabel : L.BTN_TEXT +R_STAR,
                allowBlank : false,
                width : 400,
                name : 'btnText',
                toolTipText : L.T_TEXT.BTN_TEXT
            },{
                xtype : 'checkbox',
                name : 'multiSelect',
                inputValue : true,
                fieldLabel : L.MULTI_SELECT
            }, {
                xtype : 'numberfield',
                width : 250,
                value : 150,
                minValue : 100,
                fieldLabel : L.TEXT_WIDTH,
                name : 'textWidth',
                step : 10
            }, {
                xtype : 'numberfield',
                width : 250,
                value : 128,
                minValue : 32,
                fieldLabel : L.DATA_LEN,
                toolTipText : L.T_TEXT.DATA_LEN,
                step : 16,
                name : 'length',
                disabled : this.mode == 2
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
    createDictKeyComboStore : function()
    {
        return new Ext.data.Store({
            autoLoad : true,
            fields : [
                {name : 'key', type : 'string', persist : false}
            ],
            proxy : {
                type : 'apigateway',
                callType : 'Sys',
                invokeMetaInfo : {
                    name : 'KvDict',
                    method : 'getKvKeys'
                },
                reader : {
                    type : 'json',
                    rootProperty : 'data'
                }
            }
        });
    },
    getDictKeyComboConfig : function()
    {
        var L = this.LANG_TEXT;
        var T_TEXT = L.T_TEXT;
        return {
            xtype : 'combo',
            queryMode : 'remote',
            displayField : 'key',
            valueField : 'key',
            editable : false,
            width : 400,
            store : this.createDictKeyComboStore(),
            fieldLabel : L.KEY_TYPE + Cntysoft.Utils.HtmlTpl.RED_STAR,
            name : 'kvDictKey',
            allowBlank : false,
            toolTipText : T_TEXT.KEY_TYPE
        };
    },
    destroy : function()
    {
        delete this.formPanelRef;
        this.callParent();
    }
});