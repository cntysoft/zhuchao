/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 单行文本UI参数设置面板
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldOptSetter.SingleLineText', {
    extend : 'App.Yunzhan.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'SingleLineText',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.SINGLE_LINE_TEXT',
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
            values.enableValidate = values.enableValidate == 'on' ? true : false;
            if(this.mode == 1){
                values.type = 'varchar';
                values.length = 512;
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
     * 添加验证相关组件
     */
    addValidatorFieldsHandler : function(chkbox, newValue)
    {
        var items = this.formPanelRef.items;

        if(newValue){

            items.getAt(4).show();
            items.getAt(5).show();
            items.getAt(4).setDisabled(false);
            items.getAt(5).setDisabled(false);
        } else{
            items.getAt(4).hide();
            items.getAt(5).hide();
            items.getAt(4).setDisabled(true);
            items.getAt(5).setDisabled(true);
        }
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
            }, {
                xtype : 'textfield',
                fieldLabel : A_L.DEFAULT,
                width : 500,
                name : 'defaultValue'
            }, {
                xtype : 'checkbox',
                fieldLabel : F.OPEN_VALIDATE,
                listeners : {
                    change : this.addValidatorFieldsHandler,
                    scope : this
                },
                name : 'enableValidate'
            }, this.getVTypeComboConfig(), {
                xtype : 'textfield',
                fieldLabel : F.ERROR,
                width : 500,
                name : 'vTypeMsg',
                hidden : true,
                disabled : true
            }],
            listeners : {
                afterrender : function(panel){
                    this.formPanelRef = panel;
                },
                scope : this
            }
        };
    },
    getVTypeComboConfig : function()
    {
        var F = this.LANG_TEXT.FIELDS;
        var V_NAMES = this.LANG_TEXT.VALIDATOR_NAMES;
        return {
            xtype : 'fieldcontainer',
            fieldLabel : F.VALIDATE_TYPE,
            width : 600,
            layout : {
                type : 'hbox',
                align : 'top',
                padding : '0 0 2 0'
            },
            hidden : true,
            disabled : true,
            items : [{
                xtype : 'combo',
                editable : false,
                queryMode : 'local',
                displayField : 'name',
                valueField : 'value',
                name : 'vtype',
                store : new Ext.data.Store({
                    fields : ['name', 'value'],
                    data : [
                        {name : V_NAMES.EMAIL, value : 'email'},
                        {name : V_NAMES.URL, value : 'url'},
                        {name : V_NAMES.ALPHA, value : 'alpha'},
                        {name : V_NAMES.ALPHA_NUM, value : 'alphanum'},
                        {name : V_NAMES.SELF, value : 'self'}
                    ]
                }),
                listeners : {
                    afterrender : function(self){
                        self.setValue('email');
                    },
                    change : function(self, newValue)
                    {
                        var target = self.nextSibling();
                        if(newValue == 'self'){
                            target.show();
                            target.setDisabled(false);
                        } else{
                            target.hide();
                            target.setDisabled(true);
                        }
                    },
                    scope : this
                }
            }, {
                xtype : 'textfield',
                height : 27,
                width : 230,
                margin : '0 0 0 2',
                name : 'selfRegex',
                allowBlank : false,
                hidden : true,
                disabled : true,
                toolTipText : this.LANG_TEXT.T_TEXT.REGEX,
                listeners : {
                    afterrender : function(formItem)
                    {
                        this.mixins.formTooltip.setupTooltipTarget.call(this, formItem);
                    },
                    scope : this
                }
            }]
        };
    },
    destroy : function()
    {
        delete this.formPanelRef;
        this.mixins.formTooltip.destroy();
        this.callParent();
    }
});