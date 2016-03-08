/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.MultiLineText', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'MultiLineText',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.MULTI_LINE_TEXT',
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
            values.enableLenCheck = values.enableLenCheck === 'on' ? true : false;
            Ext.applyIf(values, {
                maxLen : 512//最长就是512个字符
            });
            if(this.mode == 1){
                values.type = 'text';//暂时搞成 text
            }
            return values;
        }
    },
    /**
     * @inheritdoc
     */
    applyTypeOptValues : function(values)
    {
        this.formPanelRef.getForm().setValues(values);
    },
    /**
     * @inheritdoc
     */
    restoreTypeOptValues : function()
    {
        this.formPanelRef.getForm().setValues(this.initUiOpt);
    },
    /**
     * 处理是否激活验证
     */
    lengthCheckHandler : function(chkbox, newValue)
    {
        var target = chkbox.nextSibling();
        if(newValue){
            target.show();
            target.setDisabled(false);
        }else{
            target.hide();
            target.setDisabled(true);
        }
    },
    /**
     * @inheritdoc
     */
    getSetterPanelConfig : function()
    {
        var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
        var A_L = this.ABSTRACT_LANG_TEXT;
        var L = this.LANG_TEXT;
        return {
            xtype : 'form',
            defaults : {
                listeners : {
                    afterrender : function(formItem)
                    {
                        this.mixins.formTooltip.setupTooltipTarget.call(this, formItem);
                    },
                    scope : this
                }
            },
            items : [{
                xtype : 'numberfield',
                fieldLabel : A_L.WIDTH + R_STAR,
                value : 600,
                minValue : 0,
                step : 10,
                name : 'width'
            }, {
                xtype : 'numberfield',
                fieldLabel : A_L.HEIGHT + R_STAR,
                minValue : 100,
                value : 100,
                step : 10,
                name : 'height'
            }, {
                xtype : 'textarea',
                fieldLabel : A_L.DEFAULT,
                name : 'default',
                width : 500,
                height : 80
            },{
                xtype : 'checkbox',
                fieldLabel : L.LEN_CHECK,
                listeners : {
                    change : this.lengthCheckHandler,
                    scope : this
                },
                name : 'enableLenCheck'
            },{
                xtype : 'numberfield',
                fieldLabel : L.FIELD_LENGTH + R_STAR,
                value : 512,
                minValue : 0,
                step : 1,
                name : 'maxLen',
                padding : '0 0 2 0',
                toolTipText : L.T_TEXT.LENGTH,
                hidden : true,
                disabled : true
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