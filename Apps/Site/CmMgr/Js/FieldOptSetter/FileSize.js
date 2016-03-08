/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.FileSize', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.FILE_SIZE',
    /**
     * @inheritdoc
     */
    fieldType : 'FileSize',
    /**
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @private
     * @property {Ext.form.field.Number} numTextRef
     */
    numTextRef : null,
    /**
     * @private
     * @property {Ext.form.field.ComboBox} unitComboRef
     */
    unitComboRef : null,
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values = {
                defaultValue : this.numTextRef.getValue()+'|'+this.unitComboRef.getValue()
            };
            if(this.mode == 1){
                values.type = 'varchar';
                values.length = 64;
            }
            return values;
        }
    },
    /**
     * @inheritdoc
     */
    applyTypeOptValues : function(values)
    {
        if(values.defaultValue){
            var parts = values.defaultValue.split('|');
            this.numTextRef.setValue(parts[0]);
            this.unitComboRef.setValue(parts[1]);
        }
    },
    /**
     * @inheritdoc
     */
    restoreTypeOptValues : function()
    {
        this.applyTypeOptValues(this.initUiOpt);
    },
    /**
     * @inheritdoc
     */
    getSetterPanelConfig : function()
    {
        var A_L = this.ABSTRACT_LANG_TEXT;
        return {
            xtype : 'form',
            bodyPadding : 10,
            items : {
                xtype : 'fieldcontainer',
                fieldLabel : A_L.DEFAULT,
                layout : {
                    type : 'hbox',
                    align : 'top',
                    padding : '0 0 4 0'
                },
                items : [{
                    xtype : 'numberfield',
                    width : 120,
                    value : 0,
                    minValue : 0,
                    listeners : {
                        afterrender : function(comp)
                        {
                            this.numTextRef = comp;
                        },
                        scope : this
                    }
                }, {
                    xtype : 'combo',
                    queryMode : 'local',
                    displayField : 'name',
                    valueField : 'value',
                    editable : false,
                    store : this.createUnitStore(),
                    width : 70,
                    value : 'MB',
                    margin : '0 0 0 4',
                    listeners : {
                        afterrender : function(comp)
                        {
                            this.unitComboRef = comp;
                        },
                        scope : this
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
    createUnitStore : function()
    {
        return new Ext.data.Store({
            fields : ['name', 'value'],
            data : [
                {name : 'KB', value : 'KB'},
                {name : 'MB', value : 'MB'},
                {name : 'GB', value : 'GB'}
            ]
        });
    },
    destroy : function()
    {
        delete this.formPanelRef;
        delete this.numTextRef;
        delete this.unitComboRef;
        this.callParent();
    }
});