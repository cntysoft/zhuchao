/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 多行文本UI设置面板
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldOptSetter.WordEditor', {
    extend : 'App.Yunzhan.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'WordEditor',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.WORD_EDITOR',
    statics : {
        SIMPLE : 1,
        STANDARDC : 2,
        FULL : 3
    },
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values = form.getValues();
            if(this.mode == 1){
                values.type = 'text';
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
     * @inheritdoc
     */
    getSetterPanelConfig : function()
    {
        var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
        var A_L = this.ABSTRACT_LANG_TEXT;
        var L = this.LANG_TEXT;
        return {
            xtype : 'form',
            autoScroll : true,
            defaults : {
                listeners : {
                    afterrender : function(formItem)
                    {
                        this.mixins.formTooltip.setupTooltipTarget.call(this, formItem);
                    },
                    scope : this
                }
            },
            items : [this.getEditorTypeConfig(), {
                xtype : 'numberfield',
                fieldLabel : A_L.WIDTH + R_STAR,
                value : 80,
                minValue : 10,
                maxValue : 100,
                step : 5,
                name : 'width',
                toolTipText : L.T_TEXT.WIDTH
            }, {
                xtype : 'numberfield',
                fieldLabel : A_L.HEIGHT + R_STAR,
                minValue : 400,
                value : 400,
                step : 10,
                name : 'height'
            }, {
                xtype : 'textarea',
                fieldLabel : A_L.DEFAULT,
                name : 'default',
                width : 500,
                height : 200
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
    /**
     * 获取编辑器复杂度参数
     */
    getEditorTypeConfig : function()
    {
        var L = this.LANG_TEXT;
        var T = L.TYPE;
        var S = this.self;
        return {
            xtype : 'combo',
            queryMode : 'local',
            displayField : 'name',
            valueField : 'code',
            fieldLabel : L.EDITOR_TYPE,
            editable : false,
            name : 'editorType',
            store : new Ext.data.Store({
                fields : ['name', 'code'],
                data : [
                    {name : T.SIMPLE, code : S.SIMPLE},
                    {name : T.STANDARD, code : S.STANDARDC},
                    {name : T.FULL, code : S.FULL}
                ]
            }),
            listeners : {
                afterrender : function(self){
                    self.setValue(2);
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