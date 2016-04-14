/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 供应商信息编辑器
 */
Ext.define('App.ZhuChao.Provider.Ui.Domain.Editor', {
    extend : 'Ext.form.Panel',
    requires : [
        'App.ZhuChao.Provider.Const'
    ],
    mixins : {
        langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
    },
    /**
     * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
     *
     * @property {String} runableLangKey
     */
    runableLangKey : 'App.ZhuChao.Provider',
    //权限添加模式
    mode : 1,
    /**
     * @inheritdoc
     */
    panelType : 'Editor',
    currentNodeInfo : null,
    targetLoadId : null,
    nameFieldRef : null,
    siteNameFieldRef : null,
    domainFieldRef : null,
    constructor : function (config)
    {
        config = config || {};
        this.LANG_TEXT = this.GET_LANG_TEXT('DOMAIN.EDITOR');
        this.applyConstraintConfig(config);
        if(config.mode == ZhuChao.Const.MODIFY_MODE){
            if(!Ext.isDefined(config.targetLoadId) || !Ext.isNumber(config.targetLoadId)){
                Ext.Error.raise({
                    cls : Ext.getClassName(this),
                    method : 'constructor',
                    msg : 'mode is modify, so you must set targetLoadId id'
                });
            }
        }
        this.callParent([config]);
    },
    applyConstraintConfig : function (config)
    {
        Ext.apply(config, {
            border : true,
            bodyPadding : 10,
            autoScroll : true,
            title : this.LANG_TEXT.TITLE
        });
    },
    initComponent : function ()
    {
        Ext.apply(this, {
            defaults : {
                xtype : 'textfield',
                margin : '10 50 0 0',
                width : 700
            },
            items : this.getFormConfig(),
            buttons : [{
                    xtype : 'button',
                    text : Cntysoft.GET_LANG_TEXT('UI.BTN.SAVE'),
                    listeners : {
                        click : this.saveHandler,
                        scope : this
                    }
                }, {
                    xtype : 'button',
                    text : Cntysoft.GET_LANG_TEXT('UI.BTN.CANCEL'),
                    listeners : {
                        click : this.cancelButtonClickHandler,
                        scope : this
                    }
                }]
        });
        this.addListener('afterrender', this.afterRenderHandler, this);
        this.callParent();
    },
    loadNode : function (id)
    {
        if(this.$_current_nid_$ !== id){
            this.gotoModifyMode();
            this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD'));
            this.mainPanelRef.appRef.getCompanyDomainInfo(id, this.afterLoadNodeHandler, this);
            this.$_current_nid_$ = id;
        }
    },
    afterLoadNodeHandler : function (response)
    {
        this.loadMask.hide();
        if(!response.status){
            Cntysoft.Kernel.Utils.processApiError(response);
        } else{
            this.currentNodeInfo = response.data;
            this.getForm().setValues(this.currentNodeInfo);
        }
    },
    afterRenderHandler : function ()
    {
        if(this.mode == ZhuChao.Const.MODIFY_MODE){
            this.gotoModifyMode(true);
            this.loadNode(this.targetLoadId);
        }
    },
    gotoModifyMode : function (force)
    {
        if(force || this.mode == ZhuChao.Const.NEW_MODE){
            this.add(0, this.getIdFieldConfig());
            this.mode = ZhuChao.Const.MODIFY_MODE;
        } else{
            this.nameFieldRef.setDisabled(true);
            this.siteNameFieldRef.setDisabled(true);
        }
    },
    gotoNewMode : function (force)
    {
        if(force || this.mode != ZhuChao.Const.NEW_MODE){
            if(this.mode == ZhuChao.Const.MODIFY_MODE){
                this.remove(this.items.getAt(0));
            }
            this.nameFieldRef.setDisabled(false);
            this.getForm().reset();
            this.currentNodeInfo = null;
            this.mode = ZhuChao.Const.NEW_MODE;
            this.$_current_nid_$ = -1;
        }
    },
    saveHandler : function ()
    {
        var C = ZhuChao.Const;
        var form = this.getForm();
        var M = this.LANG_TEXT.MSG;
        if(form.isValid()){
            if(C.NEW_MODE == this.mode){
                var values = form.getValues();
                this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
                this.mainPanelRef.appRef.saveDomain(values, this.afterSaveHandler, this);
            } else if(C.MODIFY_MODE == this.mode){
                var id = this.targetLoadId;
                var values = form.getValues();
                var rawData = this.currentNodeInfo;
                Ext.each(values, function (name){
                    if(rawData[name] == values[name]){
                        delete values[name];
                    }
                }, this);
                if(!Ext.isEmpty(values)){
                    this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
                    this.mainPanelRef.appRef.saveDomain(id, values.domain, this.afterSaveHandler, this);
                } else{
                    Cntysoft.showAlertWindow(M.NOT_DIRTY);
                    return false;
                }
            }
        }
    },
    cancelButtonClickHandler : function ()
    {
        this.mainPanelRef.renderPanel('ListView');
    },
    afterSaveHandler : function (response)
    {
        this.loadMask.hide();
        if(!response.status){
            Cntysoft.Kernel.Utils.processApiError(response, this.LANG_TEXT.ERROR_MAP);
        } else{
            Cntysoft.showAlertWindow(this.LANG_TEXT.MSG.SAVE_OK, function (){
                this.mainPanelRef.renderPanel('ListView');
            }, this);
        }
    },
    getFormConfig : function ()
    {
        var F = this.LANG_TEXT.FIELD;
        var M = this.LANG_TEXT.MSG;
        var d = new Date();
        return [{
                fieldLabel : F.NAME,
                allowBlank : false,
                name : 'name',
                listeners : {
                    afterrender : function (name){
                        this.nameFieldRef = name;
                    },
                    scope : this
                }
            }, {
                fieldLabel : F.SITENAME,
                allowBlank : false,
                name : 'siteName',
                listeners : {
                    afterrender : function (name){
                        this.siteNameFieldRef = name;
                    },
                    scope : this
                }
            }, {
                fieldLabel : F.DOMAIN,
                allowBlank : false,
                name : 'domain',
                listeners : {
                    afterrender : function (name){
                        this.domainFieldRef = name;
                    },
                    scope : this
                }
            }];
    },
    getIdFieldConfig : function ()
    {
        return {
            xtype : 'displayfield',
            fieldLabel : this.LANG_TEXT.FIELD.ID,
            name : 'id'
        };
    },
    destroy : function ()
    {
        delete this.currentNodeInfo;
        delete this.nameFieldRef;
        delete this.targetLoadId;
        delete this.domainFieldRef;
        delete this.siteNameFieldRef;
        this.callParent();
    }
});
