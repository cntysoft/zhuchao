/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 商品分类管理详细属性设置面板
 */
Ext.define('App.ZhuChao.CategoryMgr.Ui.Attrs.StdAttrPanel', {
   extend : 'Ext.panel.Panel',
   requires : [
      'App.ZhuChao.CategoryMgr.Comp.StdAttrGrid'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.ZhuChao.CategoryMgr',
   //权限添加模式
   mode : 1,
   stdPanelRef : null,
   stdAttrs : null,
   targetLoadId : null,
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('UI.ATTRS.STD_ATTR');
      this.applyConstraintConfig(config);
      if(config.mode == ZhuChao.Const.MODIFY_MODE){
         if(!Ext.isDefined(config.targetLoadId) || !Ext.isNumber(config.targetLoadId)){
            Ext.Error.raise({
               cls : Ext.getClassName(this),
               method : 'constructor',
               msg : 'mode is modify, so you must set node id'
            });
         }

         this.targetLoadId = config.targetLoadId;
      }
      this.callParent([config]);
   },
   applyConstraintConfig : function (config)
   {
      Ext.apply(config, {
         border : true,
         layout : {
            type : 'vbox',
            align : 'stretch'
         },
         autoScroll : true,
         title : this.LANG_TEXT.TITLE
      });
   },
   initComponent : function ()
   {
      Ext.apply(this, {
         items : [
            this.getStdAttrPanelConfig()
         ],
         buttons : [{
               xtype : 'button',
               text : Cntysoft.GET_LANG_TEXT('UI.BTN.SAVE'),
               listeners : {
                  click : this.saveHandler,
                  scope : this
               }
            }]
      });
      this.addListener('afterrender', this.afterRenderHandler, this);
      this.callParent();
   },
   loadNodeStdAttrs : function (nid)
   {
      if(this.$_current_nid_$ !== nid){
         this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD'));
         this.appRef.getNodeStdAttrs(nid, this.afterLoadNodeHandler, this);
         this.$_current_nid_$ = nid;
      }
   },
   getStdAttrPanelConfig : function ()
   {
      return {
         xtype : 'shopcategorymgrcompstdattrgrid',
         nodeId : this.targetLoadId,
         listeners : {
            afterrender : function (panel)
            {
               this.stdPanelRef = panel;
            },
            scope : this
         }
      };
   },
   afterRenderHandler : function ()
   {
      if(this.mode == ZhuChao.Const.MODIFY_MODE){
         this.loadNodeStdAttrs(this.targetLoadId);
      }
   },
   afterLoadNodeHandler : function (response)
   {
      this.loadMask.hide();
      if(!response.status){
         Cntysoft.Kernel.Utils.processApiError(response);
      } else{
         this.stdAttrs = response.data;
         this.stdPanelRef.setAttrValues(this.stdAttrs);
      }
   },
   saveHandler : function ()
   {
      var values = this.stdPanelRef.getAttrValues();
      Cntysoft.showQuestionWindow(this.LANG_TEXT.MSG.MODIFY_WARNING, function (val){
         if('yes' == val){
            this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
            this.appRef.saveNodeStdAttrs({
               values : values,
               nid : this.targetLoadId
            }, this.afterSaveHandler, this);
         }
      }, this);
   },
   afterSaveHandler : function (response)
   {
      this.loadMask.hide();
      if(!response.status){
         Cntysoft.Kernel.Utils.processApiError(response);
      } else{
         Cntysoft.showAlertWindow(Cntysoft.GET_LANG_TEXT('MSG.SAVE_OK'), function (){
//            this.stdPanelRef.clearAttrValues();
         }, this);
      }
   },
   destroy : function ()
   {
      delete this.stdAttrs;
      delete this.stdPanelRef;
      this.callParent();
   }
});