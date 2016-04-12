Ext.define('App.ZhuChao.MarketMgr.Ui.Feedback.Feedback', {
    extend : 'Ext.grid.Panel',
    alias : 'widget.appzhuchaomarketmgruifeedbackfeedback',
    mixins : {
        langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
    },
    runableLangKey : 'App.ZhuChao.MarketMgr',
    storeRef : null,
    gridRef : null,
    contextMenuRef : null,
    constructor : function (config){
        this.applyConstraintConfig(config);
        this.LANG_TEXT = this.GET_LANG_TEXT('FEEDBACK');
        this.callParent([config]);
    },
    applyConstraintConfig : function (config){
        Ext.apply(config, {
        });
    },
    initComponent : function (){
        var store = this.getGridStore();
        Ext.apply(this, {
            bbar : Ext.create('Ext.PagingToolbar', {
                store : store,
                displayInfo : true,
                emptyMsg : this.LANG_TEXT.EMPTYTEXT
            }),
            store : store,
            columns : [
                {text : this.LANG_TEXT.ID, dataIndex : 'id', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.TYPE, dataIndex : 'type', flex : 1, resizable : false, sortable : false, menuDisabled : true, renderer : Ext.bind(this.typeRenderer, this)},
                {text : this.LANG_TEXT.TEXT, dataIndex : 'text', flex : 3, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.NAME, dataIndex : 'name', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.PHONE, dataIndex : 'phone', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.EMAIL, dataIndex : 'email', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.QQ, dataIndex : 'qq', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.INPUTTIME, dataIndex : 'inputTime', flex : 1, resizable : false, sortable : false, menuDisabled : true},
                {text : this.LANG_TEXT.STATUS, dataIndex : 'status', flex : 1, resizable : false, sortable : false, menuDisabled : true, renderer : Ext.bind(this.statusRenderer, this)}
            ],
            listeners : {
                afterrender : function (grid){
                    this.gridRef = grid;
                },
                itemcontextmenu : this.itemContextMenuClickHandler,
                scope : this
            }
        });
        this.callParent();
    },
    statusRenderer : function (value)
    {
        switch (value) {
            case 1:
                return '<span style = "color:red">' + this.LANG_TEXT.UNREAD + '</span>';
            case 2:
                return '<span style = "color:orange">' + this.LANG_TEXT.READED + '</span>';
            case 3:
                return '<span style = "color:green">' + this.LANG_TEXT.DEALED + '</span>';
        }
    },
    typeRenderer : function (value)
    {
        var type = parseInt(value);
        switch (type) {
            case 1:
                return this.LANG_TEXT.TYPE_1;
            case 2:
                return this.LANG_TEXT.TYPE_2;
            case 3:
                return this.LANG_TEXT.TYPE_3;
            case 4:
                return this.LANG_TEXT.TYPE_4;
        }
    },
    itemContextMenuClickHandler : function (grid, record, item, index, event, eOpts)
    {
        var menu = this.createContextMenu(record);
        menu.record = record;
        var pos = event.getXY();
        event.stopEvent();
        menu.showAt(pos[0], pos[1]);
    },
    createContextMenu : function (record)
    {
        var item = [
            {
                text : this.LANG_TEXT.TOUNREAD,
                status : 1
            },
            {
                text : this.LANG_TEXT.TOREADED,
                status : 2
            }, {
                text : this.LANG_TEXT.TODEALED,
                status : 3
            }
        ];
        if(null == this.contextMenuRef){
            this.contextMenuRef = new Ext.menu.Menu({
                width : 190,
                items : item,
                listeners : {
                    click : this.contextMenuClickHandler,
                    scope : this
                }
            });
        }
        return this.contextMenuRef;
    },
    contextMenuClickHandler : function (menu, item){
        var id = menu.record.getId();
        this.appRef.changFeedbackStatus({id : id, status : item.status}, function (response){
            if(!response.status){
                Cntysoft.showErrorWindow(response.msg);
            } else{
                this.gridRef.store.reload();
            }
        }, this);
    },
    getGridStore : function (){
        if(null == this.storeRef){
            this.storeRef = new Ext.data.Store({
                autoLoad : true,
                pageSize : 25,
                fields : [
                    {name : 'id', type : 'integer'},
                    {name : 'type', type : 'string'},
                    {name : 'text', type : 'string'},
                    {name : 'name', type : 'string'},
                    {name : 'phone', type : 'string'},
                    {name : 'email', type : 'string'},
                    {name : 'qq', type : 'string'},
                    {name : 'inputTime', type : 'string'},
                    {name : 'status', type : 'integer'},
                    {name : 'identify', type : 'string'}
                ],
                proxy : {
                    type : 'apigateway',
                    callType : 'App',
                    invokeMetaInfo : {
                        module : 'ZhuChao',
                        name : 'Service',
                        method : 'Feedback/getFeedbackList'
                    },
                    reader : {
                        type : 'json',
                        rootProperty : 'items',
                        totalProperty : 'total'
                    }
                }
            });
        }
        return this.storeRef;
    },
    destroy : function ()
    {
        delete this.gridRef;
        delete this.storeRef;
        if(this.contextMenuRef){
            this.contextMenuRef.destroy();
        }
        delete this.contextMenuRef;
        this.callParent();
    }
});