/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 日期UI设置面板
 */
Ext.define('App.Yunzhan.CmMgr.Lib.FieldOptSetter.Date', {
    extend : 'App.Yunzhan.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.DATE',
    /**
     * @inheritdoc
     */
    fieldType : 'Date',
    /**
     * @private
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @private
     * @property {Ext.form.field.ComboBox} typeComboRef
     */
    typeComboRef : null,
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values = form.getValues();
            if(this.mode == 1){
                values.type = 'date';
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
     * @return {Ext.data.Store}
     */
    createTypeStore : function()
    {
        return new Ext.data.Store({
            fields : ['name', 'value'],
            data : [
                {name : 'Y/m/d', value : 'Y/m/d'},
                {name : 'Y-m-d', value : 'Y-m-d'}
            ]
        });
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
            items : [this.getTypeComboConfig()],
            listeners : {
                afterrender : function(panel)
                {
                    this.formPanelRef = panel;
                },
                scope : this
            }
        };
    },
    getTypeComboConfig : function()
    {
        return {
            xtype : 'combo',
            queryMode : 'local',
            displayField : 'name',
            valueField : 'value',
            fieldLabel : this.LANG_TEXT.FORMAT,
            width : 300,
            editable : false,
            store : this.createTypeStore(),
            name : 'format',
            listeners : {
                afterrender : function(self){
                    this.typeComboRef = self;
                    self.setValue('Y/m/d');
                },
                scope : this
            }
        };
    },
    destroy : function()
    {
        delete this.typeComboRef;
        delete this.formPanelRef;
        this.callParent();
    }
});