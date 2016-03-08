/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 选项UI设置面板
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.Selection', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    statics : {
        COMBO : 1,
        LIST : 2,
        RADIO : 3,
        CHECKBOX : 4,
        A_MAP : {
            DELETE : 1,
            SET_DEFAULT : 2
        }
    },
    /**
     * @inheritdoc
     */
    fieldType : 'Selection',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.SELECTION',
    /**
     * @private
     * @property {Ext.form.field.ComboBox} prefixCombo
     */
    typeComboRef : null,
    /**
     * @private
     * @property {Ext.form.field.ComboBox} datatypeComboRef
     */
    datatypeComboRef : null,
    /**
     * @private
     * @property {Ext.form.Panel} formPanelRef
     */
    formPanelRef : null,
    /**
     * @private
     * @property {Ext.grid.Panel} dataGridRef
     */
    dataGridRef : null,
    /**
     * @private
     * @property {Ext.form.field.Checkbox} lenChangeChkbox
     */
    lenChangeChkbox : null,
    /**
     * @private
     * @property {Ext.form.field.Text} defaultValueTextRef
     */
    defaultValueTextRef : null,
    /**
     * @private
     * @property {Number} seed
     */
    seed : 1,
    /**
     * @property {Number} contextMenuRef
     */
    contextMenuRef : null,
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var form = this.formPanelRef.getForm();
        if(form.isValid()){
            var values = form.getValues();
            values.denyChangeItemNum = values.denyChangeItemNum === 'on' ? true : false;
            var items = values.items = [];
            this.dataGridRef.store.each(function(item){
                items.push(item.get('name') + '|' + item.get('value'));
            });
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
        var items = values.items || [];
        var len = items.length;
        var data = [];
        var parts;
        var defaultValue = values.defaultValue;
        var isDefault = false;
        for(var i = 0; i < len; i++) {
            parts = items[i].split('|');
            if(items[i] === defaultValue){
                isDefault = true;
            } else{
                isDefault = false;
            }
            data.push({
                name : parts[0],
                value : parts[1],
                isDefault : isDefault
            });
        }
        this.dataGridRef.store.removeAll();
        this.dataGridRef.store.add(data);
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
    createtypeComboRefStore : function()
    {
        var L = this.LANG_TEXT;
        var S = this.self;
        return new Ext.data.Store({
            fields : ['name', 'code'],
            data : [
                {name : L.COMBO, code : S.COMBO, persist : true},
                {name : L.LIST, code : S.LIST, persist : true},
                {name : L.RADIO, code : S.RADIO, persist : true},
                {name : L.CHECKBOX, code : S.CHECKBOX, persist : true}
            ]
        });
    },
    /**
     * 处理类型变化，单选和复选需要设置列的大小
     */
    typeChangeHandler : function(combo, newType)
    {
        var self = this.self;
        var target = combo.nextSibling();
        if(newType === self.RADIO || newType === self.CHECKBOX){
            target.show();
            target.setDisabled(false);
        } else{
            target.hide();
            target.setDisabled(true);
        }
        //将默认值失效
        if(this.defaultValueTextRef){
            this.defaultValueTextRef.setValue('');
        }

    },
    dataTypeChangeHandler : function(combo, newType)
    {
        var target = combo.nextSibling();
        if(newType === 'string'){
            target.show();
            if(this.mode != 2){
                target.setDisabled(false);
            }
        } else{
            target.hide();
            target.setDisabled(true);
        }
    },
    addNewItemHandler : function()
    {
        var GRID = this.LANG_TEXT.GRID;
        var id = this.seed++;
        var newItem = {
            name : GRID.NAME + id,
            value : GRID.VALUE + id
        };
        this.dataGridRef.store.add(newItem);
    },
    /**
     * 删除选项处理函数
     */
    menuItemClickHandler : function(menu, item)
    {
        var code = item.code;
        var records = menu.sel;
        var A_MAP = this.self.A_MAP;
        var store = this.dataGridRef.store;
        if(code === A_MAP.DELETE){
            store.remove(records);
            //重新生成默认值
            var defaultValue = [];
            store.each(function(record){
                if(record.get('isDefault')){
                    defaultValue.push(record.get('value'));
                }
            }, this);
            this.defaultValueTextRef.setValue(defaultValue.join('|'));
        } else if(code === A_MAP.SET_DEFAULT){
            store.each(function(r){
                if(Ext.Array.contains(records, r)){
                    r.set('isDefault', true);
                } else{
                    r.set('isDefault', false);
                }
            });
            var len = records.length;
            var defaultValue = [];
            var record;
            for(var i = 0; i < len; i++) {
                record = records[i];
                defaultValue.push(record.get('value'));
            }
            this.defaultValueTextRef.setValue(defaultValue.join('|'));
        }
    },
    /**
     * 获取上下文菜单
     */
    getcontextMenuRef : function()
    {
        var MENU = this.LANG_TEXT.MENU;
        var A_MAP = this.self.A_MAP;
        if(null == this.contextMenuRef){
            this.contextMenuRef = new Ext.menu.Menu({
                ignoreParentClicks : true,
                items : [{
                    text : MENU.SET_DEFAULT,
                    code : A_MAP.SET_DEFAULT
                }, {
                    text : MENU.DELETE,
                    code : A_MAP.DELETE,
                    disabled : this.deneyChangeItemNum()
                }],
                listeners : {
                    click : this.menuItemClickHandler,
                    scope : this
                }
            });
        }
        return this.contextMenuRef;
    },
    /**
     * 是否允许改变选项数目
     *
     * @return {Boolean}
     */
    deneyChangeItemNum : function()
    {
        if(this.mode === 2){
            if(this.initUiOpt.denyChangeItemNum){
                return true;
            }
        }
        return false;
    },
    /**
     * 获取上下文菜单点击事件
     */
    gridItemContextClickHandler : function(grid, record, htmlItem, index, event)
    {
        //判断是否有多于2条的信息被选中
        var sel = grid.getSelectionModel().getSelection();
        var menu = this.getcontextMenuRef();
        if(sel.length > 1){
            //分多选和单选
            var S = this.self;
            var selType = this.typeComboRef.getValue();
            if(S.COMBO == selType || S.RADIO === selType){
                menu.items.getAt(0).setDisabled(true);
            } else{
                menu.items.getAt(0).setDisabled(false);
            }
        } else{
            menu.items.getAt(0).setDisabled(false);
        }

        var pos = event.getXY();
        menu.sel = sel;
        event.stopEvent();
        menu.showAt(pos[0], pos[1]);
    },
    dataGridRefItemChangeHandler : function(editor, e)
    {
        var record = e.record;
        if(record.get('isDefault')){
            this.defaultValueTextRef.setValue(record.get('value'));
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
            items : [this.gettypeComboRefConfig(), {
                xtype : 'numberfield',
                fieldLabel : L.COL_SIZE,
                value : 1,
                minValue : 1,
                name : 'colSize',
                width : 160,
                disabled : true,
                hidden : true
            }, this.getItemGridConfig(), {
                xtype : 'textfield',
                fieldLabel : A_L.DEFAULT,
                width : 505,
                margin : '4 0 4 0',
                readOnly : true,
                name : 'defaultValue',
                listeners : {
                    afterrender : function(text)
                    {
                        this.defaultValueTextRef = text;
                    },
                    scope : this
                }
            }, {
                xtype : 'checkbox',
                fieldLabel : L.DENEY_CHANGE_ITEM,
                toolTipText : L.T_TEXT.DENEY_CHANGE_ITEM,
                listeners : {
                    afterrender : function(box){
                        this.lenChangeChkbox = box;
                        this.mixins.formTooltip.setupTooltipTarget.call(this, box);
                    },
                    scope : this
                },
                name : 'denyChangeItemNum',
                disabled : this.mode == 2 ? true : false //修改模式不能变
            }, {
                xtype : 'fieldcontainer',
                layout : {
                    type : 'hbox',
                    align : 'top',
                    padding : '0 0 2 0'
                },
                disabled : this.mode == 2 ? true : false, //修改模式不能变
                width : 440,
                fieldLabel : L.DATA_TYPE,
                toolTipText : L.T_TEXT.DATA_TYPE,
                items : [this.getDatatypeComboRefConfig(), {
                    xtype : 'numberfield',
                    value : 255,
                    minValue : 32,
                    step : 32,
                    margin : '0 0 0 4',
                    name : 'length',
                    hidden : true,
                    disabled : true
                }]
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
     * @return {Object}
     */
    gettypeComboRefConfig : function()
    {
        var L = this.LANG_TEXT;
        return {
            xtype : 'combo',
            queryMode : 'local',
            displayField : 'name',
            valueField : 'code',
            width : 250,
            editable : false,
            store : this.createtypeComboRefStore(),
            fieldLabel : L.SELECTION_TYPE,
            name : 'selectionType',
            listeners : {
                afterrender : function(self){
                    this.typeComboRef = self;
                    self.setValue(1);
                },
                change : this.typeChangeHandler,
                scope : this
            }
        };
    },
    getDatatypeComboRefConfig : function()
    {
        var L = this.LANG_TEXT;
        return {
            xtype : 'combo',
            queryMode : 'local',
            displayField : 'name',
            valueField : 'type',
            editable : false,
            store : new Ext.data.Store({
                fields : ['name', 'type'],
                data : [
                    {name : 'Text', type : 'text', persist : false},
                    {name : 'Varchar', type : 'string', persist : false}
                ]
            }),
            name : 'type',
            listeners : {
                afterrender : function(self){
                    this.datatypeComboRef = self;
                    self.setValue('Text');
                },
                change : this.dataTypeChangeHandler,
                scope : this
            }
        };
    },
    /**
     * @return {Object}
     */
    getItemGridConfig : function()
    {
        var GRID = this.LANG_TEXT.GRID;
        var L = this.LANG_TEXT;

        return {
            xtype : 'fieldcontainer',
            fieldLabel : L.COLLECTION,
            items : [{
                xtype : 'grid',
                height : 200,
                width : 400,
                autoScroll : true,
                border : false,
                toolTipText : this.LANG_TEXT.T_TEXT.GRID,
                style : 'border : 1px solid #CCC',
                emptyText : GRID.EMPTY,
                columns : [
                    {text : GRID.NAME, dataIndex : 'name', flex : 1, menuDisabled : true, resizable : false, sortable : false,
                        editor : {
                            xtype : 'textfield',
                            allowBlank : false
                        }},
                    {text : GRID.VALUE, dataIndex : 'value', flex : 2, menuDisabled : true, resizable : false, sortable : false,
                        editor : {
                            xtype : 'textfield',
                            allowBlank : false
                        }}
                ],
                store : new Ext.data.Store({
                    fields : [
                        {name : 'name', type : 'string', persist : false},
                        {name : 'value', type : 'string', persist : false},
                        {name : 'isDefault', type : 'boolean', persist : false}
                    ]
                }),
                selModel : {
                    allowDeselect : true,
                    mode : 'MULTI'
                },
                plugins : [
                    Ext.create('Ext.grid.plugin.CellEditing', {
                        clicksToEdit : 2
                    })
                ],
                listeners : {
                    itemcontextMenuRef : this.gridItemContextClickHandler,
                    afterrender : function(grid)
                    {
                        this.dataGridRef = grid;
                    },
                    edit : this.dataGridRefItemChangeHandler,
                    scope : this
                }
            }, {
                xtype : 'fieldcontainer',
                items : {
                    xtype : 'button',
                    text : L.ADD_NEW,
                    listeners : {
                        click : this.addNewItemHandler,
                        scope : this
                    },
                    disabled : this.deneyChangeItemNum()
                },
                margin : '4 0 0 0'
            }]
        };
    },
    destroy : function()
    {
        delete this.formPanelRef;
        delete this.typeComboRef;
        delete this.dataGridRef;
        delete this.defaultValueTextRef;
        if(this.contextMenuRef){
            this.contextMenuRef.destroy();
            delete this.contextMenuRef;
        }
        this.callParent();
    }
});