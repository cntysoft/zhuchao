/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.SelectLocalFileWin', {
   extend : 'Ext.window.Window',
   alias : 'widget.cmfselectlocalfilewindow',
   requires : [
      'Cntysoft.Component.FsView.GridView',
      'Ext.layout.container.Fit'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /**
    * 默认的文件类型
    */
   fileType : [
      'gif',
      'jpg',
      'jpeg',
      'png',
      'bmf',
      'zip',
      'rar',
      'rpm'
   ],
   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.Site.CmMgr',
   LANG_TEXT : null,
   allowMutiSelect : null,
   fsViewRef : null,
   constructor : function(config)
   {
      config = config || {};
      this.fileType = config.hasOwnProperty('fileType') ? config.fileType : this.fileType;
      this.LANG_TEXT = this.GET_LANG_TEXT('FIELD_WIDGET.SELECT_LOCAL_FILE');
      this.allowMutiSelect = config.hasOwnProperty('allowMutiSelect') ? config.allowMutiSelect : true;
      this.applyConstraintConfig();
      this.callParent([config]);
   },
   applyConstraintConfig : function()
   {
      var TITLE = this.LANG_TEXT.TITLE;
      Ext.apply(this, {
         title : TITLE,
         width : 900,
         height : 400,
         resizable : false,
         maximizable : false,
         modal : true,
         bodyStyle : 'background:#ffffff',
         closeAction : 'hide',
         bodyPadding : 1,
         layout : 'fit'
      });
   },
   initComponent : function()
   {
      var BTN = Cntysoft.GET_LANG_TEXT('UI.BTN');
      Ext.apply(this, {
         items : {
            xtype : 'cmpgridfsview',
            startPaths : [
               'Data/UploadFiles'
            ],
            isCreateBBar : false,
            allowMutiSelect : this.allowMutiSelect,
            listeners : {
               afterrender : function(view){
                  this.fsViewRef = view;
               },
               beforeitemcontextmenu : function(){
                  return false;
               },
               beforeselect : this.beforeSelectHandler,
               scope : this
            }
         },
         buttons : [{
            text : BTN.OK,
            listeners : {
               click : this.okBtnClickHandler,
               scope : this
            }
         }, {
            text : BTN.CANCEL,
            listeners : {
               click : function(){
                  this.close();
               },
               scope : this
            }
         }]
      });
      this.addEvents('selecteditems');
      this.addListener({
         close : this.closeHandler,
         scope : this
      });
      this.callParent();
   },
   closeHandler : function()
   {
      this.fsViewRef.cd2InitDir();
   },
   beforeSelectHandler : function(fsView, record, event)
   {
      var type = record.get('type');
      if(Ext.Array.contains(this.fileType, type)){
         return true;
      } else{
         return false;
      }
   },
   okBtnClickHandler : function()
   {
      var records = this.fsViewRef.getSelectedItems();
      var ERROR = this.LANG_TEXT.ERROR;
      var data = [];
      if(0 == records.length){
         Cntysoft.showErrorWindow(ERROR.EMPTY_SELECT);
         return;
      }
      Ext.each(records, function(record){
         data.push(record.data);
      });
      if(this.hasListeners.selecteditems) {
         this.fireEvent('selecteditems', data);
      }
      this.close();
   },
   /**
    * 接口，用于接收数据
    */
   setTarget : function(target)
   {
      this.valueTarget = target;
   },
   /**
    * 清空自定义数据
    */
   destroy : function()
   {
      delete this.LANG_TEXT;
      delete this.fsViewRef;
      delete this.valueTarget;
      this.callParent();
   }
});