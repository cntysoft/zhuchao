/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.FieldWidget.WordEditor', {
   extend : 'App.Site.CmMgr.Lib.FieldWidget.AbstractField',
   requires : [
      'WebOs.Component.CkEditor.Editor',
      'WebOs.Kernel.StdPath'
   ],
   /**
    * @private
    * @property {Cntysoft.Component.CkEditor.Editor} wordEditorRef
    */
   wordEditorRef : null,
   /**
    * @property {String} toobarType
    */
   toobarType : 'standard',
   initComponent : function()
   {
      var uiOpt = this.renderOptions.uiOption;
      var width = parseInt(uiOpt.width) || 100;
      var height = parseInt(uiOpt.height) || 400;
      Ext.apply(this, {
         layout : 'fit',
         anchor : width + '%',
         height : height,
         items : this.getEditorConfig(height)
      });
      this.callParent();
   },
   getEditorConfig : function(height)
   {
      if('standard' == this.toobarType){
         height -= 150;
      } else if('basic' == this.toobarType){
         height -= 80;
      }
      var phpSetting = WebOs.getSysEnv().get(WebOs.Const.ENV_PHP_SETTING);
      var basePath = ZC.getAppUploadFilesPath('Site', 'Content');
      this.wordEditorRef = new WebOs.Component.CkEditor.Editor({
         height : height,
         toobarType : this.toobarType,
         defaultUploadPath : basePath,
         uploadMaxSize : phpSetting.uploadMaxFileSize,
         listeners : {
            blur : function()
            {
               this.isFieldValueValid();
            },
            filerefrequest : function(ref, form)
            {
               form.setValues({url : ZC.getZhuChaoImageUrl(ref.filename)});
               var rid = parseInt(ref.rid);
               this.editorRef.fileRefs.push(rid);
               if(this.editorRef.imgRefMap){
                  this.editorRef.imgRefMap.add(ref.filename, rid);
               }
            },
            editorready: function(){
               Ext.Function.defer(function(){
                  this.editorRef.basicFormRef.scrollTo(0, 0);
               }, 1, this);
            },
            lengthoverflow : function()
            {
               this.$_length_overflow_$ = true;
            },
            lengthvalid : function()
            {
               this.$_length_overflow_$ = false;
            },
            scope : this
         }
      });
      return this.wordEditorRef;
   },
   /**
    * @inheritdoc
    */
   setFieldValue : function(value)
   {
      if(!this.wordEditorRef){
         this.addListener('editorready', function(){
            this.setFieldValue(value);
            Ext.Function.defer(function(){
               this.editorRef.basicFormRef.scrollTo(0, 0);
            }, 2000, this);
         }, this);
         return;
      }
      this.wordEditorRef.setData(value);
   },
   /**
    * @inheritdoc
    */
   getFieldValue : function()
   {
      if(this.wordEditorRef){
         return this.wordEditorRef.getData();
      }
   },
   /**
    * @inheritdoc
    */
   isFieldValueValid : function()
   {
      var MSG = this.GET_LANG_TEXT('FIELD_WIDGET.WORD_EDITOR').MSG;
      if(this.renderOptions && this.renderOptions.require){
         if('' === Ext.String.trim(this.wordEditorRef.getData())){
            //this.markInvalid(MSG.EMPTY);
            Ext.fly(this.el.id+'-containerEl').setStyle({
               border : '1px solid red'
            });
            return false;
         } else if(this.$_length_overflow_$){
            //this.markInvalid(MSG.LEN_OVERFLOW);
            return false;
         }
         //去掉红色
         Ext.fly(this.el.id+'-containerEl').setStyle({
            border : 'none'
         });
      }
      return true;
   },
   destroy : function()
   {
      delete this.wordEditorRef;
      this.callParent();
   }
});
