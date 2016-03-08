/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Site.CmMgr.Lib.Editor.ArticleEditor', {
   extend: 'App.Site.CmMgr.Lib.Editor.StdEditor',
   requires: [
      'App.Site.Content.Comp.RemoteImageSaver'
   ],
   /**
    * @property {App.Site.Content.Comp.RemoteImageSaver}
    */
   remoteImageSaver: null,
   //文章里面所有的图片映射表
   imgRefMap: null,
   /**
    * @property {RegExp} imgRegex
    */
   imgRegex: null,
   constructor: function(config)
   {
      this.callParent([config]);
      this.remoteImageSaver = new App.Site.Content.Comp.RemoteImageSaver({
         editor: this
      });
      this.imgRegex = /<img.*?src=[\"\']([^\"]*?)[\"\'][^\/]*\/>/igm;
      this.imgRefMap = new Ext.util.HashMap();
   },
   initComponent: function()
   {
      this.addListener('beforesaverequest', this.beforeSaveRequestHandler, this);
      this.callParent();
   },
   prepareLoadInfoHandler: function()
   {
      this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD'));
      this.cmAppRef.readInfo(this.targetLoadId, function(response) {
         this.loadMask.hide();
         if (!response.status) {
            Cntysoft.processApiError(response);
         } else {
            //缓存数据
            this.loadedValue = response.data;
            this.fileRefs = [];//清空
            this.imgRefMap.clear();
            Ext.Array.forEach(this.loadedValue.imgRefMap, function(ref) {
               this.imgRefMap.add(ref[0], ref[1]);
            }, this);
            if (Ext.isArray(this.loadedValue.fileRefs)) {
               Ext.Array.forEach(this.loadedValue.fileRefs, function(ref) {
                  this.fileRefs.push(parseInt(ref));
               }, this);
            }
            //渲染组件，这里只负责对BasicForm中的数据进行赋值
            this.renderFieldWidgetsHandler();
            //对属性信息表单赋值
            this.setPropertyFormValues();
         }
      }, this);
   },
   beforeSaveRequestHandler: function(values, mode)
   {
      var imgs = [];
      var cdnServer = ZC.getImageCdnServer();
      while (result = this.imgRegex.exec(values.content)) {
         imgs.push(result[1].replace(cdnServer + '/', ''));
      }
      var maps = [];
      this.imgRefMap.each(function(key, value) {
         if (!Ext.Array.contains(imgs, key)) {
            Ext.Array.remove(this.fileRefs, value);
            this.imgRefMap.removeAtKey(key);
         } else {
            maps.push([key, value]);
         }
      }, this);
      values.imgRefMap = maps;
      return true;
   },
   /**
    * 保存编辑器里面内容
    */
   saveHandler: function()
   {
      if (this.isBasicFieldsValid()) {
         //首先获取基本信息
         var values = this.getBasicFieldValues();
         //获取属性信息，并将其合并到一块
         Ext.apply(values, this.getPropertyFormValues());
         //添加模型ID
         Ext.apply(values, {
            modelId: this.modelId,
            fileRefs: this.fileRefs
         });
         if (this.mode == WebOs.Const.NEW_MODE) {
            values.modelId = this.modelId;
         } else {
            values.id = this.loadedValue.id;
         }
         var ossServer = ZC.getImgOssServer();
         var regex = new RegExp(ossServer, 'igm');
         values.content = values.content.replace(regex, ZC.getImageCdnServer());
         this.doSaveArticle(values, this.mode);
      }
   },
   /**
    * 保存文章相关信息
    */
   doSaveArticle: function(values, mode)
   {
      if (this.hasListeners.beforesaverequest) {
         if (this.fireEvent('beforesaverequest', values, this.mode, this)) {
            if (this.hasListeners.saverequest) {
               this.fireEvent('saverequest', values, this.mode, this);
            }
         }
      } else {
         if (this.hasListeners.saverequest) {
            this.fireEvent('saverequest', values, this.mode, this);
         }
      }
   },
   destroy: function()
   {
      this.imgRefMap.clear();
      delete this.imgRefMap;
      delete this.remoteImageSaver;
      this.callParent();
   }
});