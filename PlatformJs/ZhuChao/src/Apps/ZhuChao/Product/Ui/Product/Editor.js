/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/*
 * 产品信息编辑器
 */
Ext.define('App.ZhuChao.Product.Ui.Product.Editor', {
   extend : 'Ext.panel.Panel',
   requires : [
      'WebOs.Kernel.StdPath',
      'App.ZhuChao.Product.Comp.CategoryCombo',
      'WebOs.Component.Uploader.Window',
      'Cntysoft.Component.ImagePreview.View',
      'App.ZhuChao.Product.Comp.NormalAttrGroupWin',
      'App.ZhuChao.Product.Comp.NormalAttrWindow',
      'App.ZhuChao.Product.Comp.GoodsDetailImageView',
      'WebOs.Component.Uploader.SimpleUploader'
   ],
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /**
    * @inheritdoc
    */
   panelType : 'Editor',
   /**
    * {@link WebOs.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.ZhuChao.Product',
   //文章里面所有的图片映射表
   imgRefMap: null,
   /**
    * @property {RegExp} imgRegex
    */
   imgRegex: null,
   
   basicFormRef : null,
   normalAttrsPanelRef : null,
   categoryFieldRef : null,
   fileRefs : null,
   imagePreviewRef : null,
   normalAttrGroupMap : {},
   normalAttrGroupWinRef : null,
   normalAttrWinRef : null,
   statusFieldRef : null,
   mode : 1,
   uploadWinRef : null,
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('UI.PRODUCT.EDITOR');
      this.applyConstraintConfig(config);
      if(config.mode == ZhuChao.Const.MODIFY_MODE){
         if(!Ext.isDefined(config.targetLoadId) || !Ext.isNumber(config.targetLoadId)){
            Ext.Error.raise({
               cls : Ext.getClassName(this),
               method : 'constructor',
               msg : 'mode is modify, so you must set goods id'
            });
         }
      }
      this.imagePreviewRef = new Cntysoft.Component.ImagePreview.View({
         trackMouse : false
      });
      this.imgRegex = /<img.*?src=[\"\']([^\"]*?)[\"\'][^\/]*\/>/igm;
      this.imgRefMap = new Ext.util.HashMap();
      this.callParent([config]);
   },
   applyConstraintConfig : function (config)
   {
      Ext.apply(config, {
         title : this.LANG_TEXT.TITLE,
         autoScroll : true,
         layout : {
            type : 'vbox',
            align : 'stretch'
         }
      });
   },
   initComponent : function ()
   {
      Ext.apply(this, {
         buttons : [{
               text : Cntysoft.GET_LANG_TEXT('UI.BTN.SAVE'),
               listeners : {
                  click : this.saveHandler,
                  scope : this
               }
            }, {
               text : Cntysoft.GET_LANG_TEXT('UI.BTN.CANCEL'),
               listeners : {
                  click : function (){
                     this.close();
                  },
                  scope : this
               }
            }],
         items : [
            this.getBasicInfoConfig(),
            this.getNormalAttrFormConfig(),
            this.getOtherFormConfig(),
            this.getDetailFormConfig(),
            this.getDescriptionConfig()
         ]
      });
      this.addListener('afterrender', this.afterRenderHandler, this);
      this.callParent();
   },
   gotoModifyMode : function (force)
   {
      if(force || this.mode != ZhuChao.Const.MODIFY_MODE){
         this.categoryFieldRef.disable();
         this.companyFieldRef.disable();
         this.mode = ZhuChao.Const.MODIFY_MODE;
      }
   },
   afterRenderHandler : function ()
   {
      this.fileRefs = [];
      if(this.mode == ZhuChao.Const.MODIFY_MODE){
         this.gotoModifyMode(true);
         this.loadProductInfo(this.targetLoadId);
      }
   },
   loadProductInfo : function (gid)
   {
      if(this.$_current_gid_$ !== gid){
         this.gotoModifyMode();
         this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.LOAD'));
         this.mainPanelRef.appRef.getProductInfo(gid, this.afterLoadProductInfoHandler, this);
         this.$_current_gid_$ = gid;
      }
   },
   afterLoadProductInfoHandler : function (response)
   {
      this.loadMask.hide();
      if(!response.status){
         Cntysoft.Kernel.Utils.processApiError(response, this.LANG_TEXT.ERROR_MAP);
      } else{
         var data = response.data;
         this.basicFormRef.getForm().setValues(data);
         this.otherFormRef.getForm().setValues(data);
         this.companyFieldRef.setValue(data.companyId);
         this.companyFieldRef.setRawValue(data.company);
         this.categoryFieldRef.setValue(data.categoryId);
         this.categoryFieldRef.setRawValue(data.category);
         
         this.statusFieldRef.setValue({
            status : data.status
         });

         this.wordEditorRef.setData(data.introduction);
         
         //设置普通属性
         if(Ext.isDefined(data.attribute)){
            this.mainPanelRef.appRef.getCategoryAttrs(data.categoryId, function (response){
               this.loadMask.hide();
               if(!response.status){
                  Cntysoft.Kernel.Utils.processApiError(response);
               } else{
                  var attrs = response.data;
                  this.setupNormalAttrsForm(attrs.normalAttrs);
                  //设置值
                  var attrs = data.attribute;
                  var normalGroupNames = [];
                  this.normalAttrsPanelRef.items.each(function (form){
                     form.getForm().setValues(attrs[form.getTitle()]);
                     normalGroupNames.push(form.getTitle());
                  }, this);
                  Ext.Object.each(attrs, function (gname, attrs){
                     if(!Ext.Array.contains(normalGroupNames, gname)){
                        var items = [];
                        for(var key in attrs) {
                           items.push({
                              xtype : 'textfield',
                              fieldLabel : key,
                              name : key,
                              width : 500,
                              value : attrs[key]
                           });
                        }
                        if(items.length > 0){
                           this.normalAttrsPanelRef.add({
                              xtype : 'form',
                              title : gname,
                              bodyPadding : 10,
                              items : items
                           });
                        }
                     }
                  }, this);
               }
            }, this);
         }

         //设置详细信息
         if(Ext.isDefined(data.images)){
            Ext.Array.forEach(data.images, function (item){
               this.goodsDetailViewRef.store.add({
                  url : ZC.getZhuChaoImageUrl(item[0]),
                  fileRefId : item[1]
               });
            }, this);
         }
         
         this.imgRefMap.clear();
         Ext.Array.forEach(data.imgRefMap, function(ref) {
            this.imgRefMap.add(ref[0], ref[1]);
         }, this);

         if (Ext.isArray(data.fileRefs)) {
            Ext.Array.forEach(data.fileRefs, function(ref) {
               this.fileRefs.push(parseInt(ref));
            }, this);
         }
      }
   },
   
   getEditorValues: function(values, mode)
   {
      var imgs = [];
      var cdnServer = ZC.getImgOssServer();
      var result;
      while (result = this.imgRegex.exec(values.introduction)) {
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
      return maps;
   },
   
   isFormValid : function()
   {
      var flag = true;
      if(!this.basicFormRef.getForm().isValid()){
         flag = false;
      }
      if(!this.otherFormRef.getForm().isValid()){
         flag = false;
      }
      if(0 == this.goodsDetailViewRef.store.getCount()){
         flag = false;
      } 
      if(!this.wordEditorRef.getData()){
         flag = false;
      }
      return flag;
   },
   
   saveHandler : function ()
   {
      if(!this.isFormValid()){
         return false;
      }
      var values = {};
      var groupedAttrsValues = {};
      //普通属性
      if(!this.normalAttrsPanelRef.isHidden()){
         var groupForms = this.normalAttrsPanelRef.items;
         for(var i = 0; i < groupForms.getCount(); i++) {
            var formPanel = groupForms.getAt(i);
            var form = formPanel.getForm();
            if(!form.isValid()){
               return;
            }
            var group = formPanel.getTitle();
            groupedAttrsValues[group] = form.getValues();
         }
         Ext.apply(values, {
            attribute : groupedAttrsValues
         });
      }

      var detailImages = [];
      var ossServer = ZC.getImgOssServer() + '/';
      
      this.goodsDetailViewRef.store.each(function (record){
         var url = record.get('url');
         if(Ext.String.startsWith(url, ossServer)){
            url = url.replace(ossServer, '');
         }
         detailImages.push([
            url,
            record.get('fileRefId')
         ]);
      }, this);
      Ext.apply(values, {
         images : detailImages
      });
      
      Ext.apply(values, {
         introduction : this.wordEditorRef.getData()
      });
      values.imgRefMap = this.getEditorValues(values);
      //文件引用
      Ext.apply(values, {
         fileRefs : this.fileRefs
      });

      if(ZhuChao.Const.NEW_MODE == this.mode){
         //基本面板
         var form = this.basicFormRef.getForm();
         Ext.apply(values, form.getValues());
         Ext.apply(values, this.otherFormRef.getForm().getValues());
         this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
         this.mainPanelRef.appRef.addProductInfo(values, this.afterSaveProductInfoHandler, this);
      } else if(ZhuChao.Const.MODIFY_MODE == this.mode){
         //修改模式
         //基本面板
         var form = this.basicFormRef.getForm();
         Ext.apply(values, {
            productId : this.$_current_gid_$
         });
         Ext.apply(values, form.getValues());
         Ext.apply(values, this.otherFormRef.getForm().getValues());
         
         this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
         this.mainPanelRef.appRef.updateProductInfo(values, this.afterSaveProductInfoHandler, this);
      }
   },

   afterSaveProductInfoHandler : function (response)
   {
      this.loadMask.hide();
      if(!response.status){
         Cntysoft.Kernel.Utils.processApiError(response, this.LANG_TEXT.ERROR_MAP);
      } else{
         this.mainPanelRef.gotoPrev();
         var panel = this.mainPanelRef.getCurrentActivePanel();
         if(panel.panelType == 'ListView'){
            panel.getStore().reload();
         }
         this.close();
      }
   },

   setupNormalAttrsForm : function (attrs)
   {
      this.normalAttrsPanelRef.removeAll();
      var len = attrs.length;
      if(0 == len){
         this.normalAttrsPanelRef.hide();
         return;
      }
      var groupValues = {};
      Ext.Array.forEach(attrs, function (item){
         var groupName = item.group;
         if(!groupValues[groupName]){
            groupValues[groupName] = [item];
         } else{
            groupValues[groupName].push(item);
         }
      }, this);
      Ext.Object.each(groupValues, function (key, value){
         this.addNormalAttrGroup(key, value);
      }, this);

      this.normalAttrsPanelRef.show();
   },
   addNormalAttrGroup : function (groupName, attrs)
   {
      var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
      if('' == groupName || undefined == groupName || null == groupName || 'null' == groupName){
         groupName = this.LANG_TEXT.FIELDS.DEFAULT_TITLE;
      }
      var form = {
         title : groupName,
         xtype : 'form',
         bodyPadding : 10,
         margin : '5 0 0 0'
      };
      var items = [];
      Ext.Array.forEach(attrs, function (attr){
         this.normalAttrGroupMap[attr.name] = attr.group;
         var allowBlank = !attr.required;
         var fieldLabel = attr.name;
         if(attr.required){
            fieldLabel += R_STAR;
         }
         if(Ext.isEmpty(attr.optValue)){
            //普通的属性
            items.push({
               xtype : 'textfield',
               name : attr.name,
               fieldLabel : fieldLabel,
               allowBlank : allowBlank,
               width : 800
            });
         } else{
            var sels = attr.optValue.split(',');
            var data = [];
            Ext.Array.forEach(sels, function (item){
               data.push({
                  text : item,
                  value : item
               });
            });
            items.push({
               xtype : 'combobox',
               name : attr.name,
               width : 500,
               emptyText : this.LANG_TEXT.MSG.EMPTY_ATTR,
               store : new Ext.data.Store({
                  fields : [
                     {name : 'text', type : 'string', persist : false},
                     {name : 'value', type : 'string', persist : false}
                  ],
                  data : data
               }),
               allowBlank : allowBlank,
               editable : false,
               fieldLabel : fieldLabel,
               queryMode : 'local',
               displayField : 'text',
               valueField : 'value'
            });
         }
      }, this);
      form.items = items;
      this.normalAttrsPanelRef.add(form);
   },

   uploadSuccessHandler : function (file, uploadbtn)
   {
      var targetGrid = uploadbtn.ownerCt.previousSibling();
      var file = file.pop();
      this.fileRefs.push(parseInt(file.rid));
      targetGrid.store.add({
         url : file.filename,
         fileRefId : file.rid
      });
   },

   getNormalAttrGroupWin : function (grid)
   {
      if(null == this.normalAttrGroupWinRef){
         this.normalAttrGroupWinRef = new App.ZhuChao.Product.Comp.NormalAttrGroupWin({
            listeners : {
               saverequest : this.attrGroupRequestHandler,
               scope : this
            }
         });
      }
      this.normalAttrGroupWinRef.targetForm = grid;
      return this.normalAttrGroupWinRef;
   },
   getNormalAttrWin : function (form)
   {
      if(null == this.normalAttrWinRef){
         this.normalAttrWinRef = new App.ZhuChao.Product.Comp.NormalAttrWindow({
            listeners : {
               saverequest : this.addAttrRequestHandler,
               scope : this
            }
         });
      }
      this.normalAttrWinRef.targetForm = form;
      return this.normalAttrWinRef;
   },
   addAttrRequestHandler : function (form, data, mode)
   {
      if(mode == ZhuChao.Const.NEW_MODE){
         var container = form.add({
            xtype : 'fieldcontainer',
            fieldLabel : data.name,
            layout : {
               type : 'hbox'
            },
            items : [{
                  xtype : 'textfield',
                  width : 600,
                  name : data.name,
                  allowBlank : !data.required
               }, {
                  xtype : 'button',
                  margin : '0 0 0 4',
                  text : this.LANG_TEXT.NORMAL_ATTR_PANEL.BTN.DELETE_ATTR,
                  listeners : {
                     click : function ()
                     {
                        form.remove(container);
                     },
                     scope : this
                  }
               }]
         });
      }
   },
   attrGroupRequestHandler : function (form, data, mode)
   {
      if(mode == ZhuChao.Const.NEW_MODE){
         this.addCustomNormalAttrGroup(Ext.String.trim(data.groupName));
      } else if(mode == ZhuChao.Const.MODIFY_MODE){
         form.setTitle(Ext.String.trim(data.groupName));
      }
   },
   addCustomNormalAttrGroup : function (groupName)
   {
      var F = this.LANG_TEXT.NORMAL_ATTR_PANEL;
      var MSG = this.LANG_TEXT.MSG
      groupName = Ext.String.trim(groupName);
      var exist = false;
      this.normalAttrsPanelRef.items.each(function (item){
         if(item.getTitle() == groupName){
            exist = true;
         }
      }, this);
      if(exist){
         Cntysoft.showErrorWindow(Ext.String.format(this.LANG_TEXT.MSG.GROUP_ALREADY_EXIST, groupName));
         return;
      }
      var form = this.normalAttrsPanelRef.add({
         title : groupName,
         xtype : 'form',
         bodyPadding : 10,
         closable : true,
         margin : '5 0 0 0',
         tbar : [{
               xtype : 'button',
               text : F.BTN.ADD_ATTR,
               listeners : {
                  click : function (btn){
                     var attrs = btn.up('form').query('fieldcontainer');
                     if(3 == attrs.length){
                        Cntysoft.showAlertWindow(MSG.CUSTOM_ATTR_OVER);
                     }
                     var win = this.getNormalAttrWin(form);
                     win.center();
                     win.show();
                  },
                  scope : this
               }
            }]
      });
   },
   categorySelectedHandler : function (record)
   {
      this.setLoading(this.LANG_TEXT.MSG.LOAD_ATTRS);
      this.setupCategoryAttrs(record.get('id'));
   },
   setupCategoryAttrs : function (cid)
   {
      this.mainPanelRef.appRef.getCategoryAttrs(cid, function (response){
         this.loadMask.hide();
         if(!response.status){
            Cntysoft.Kernel.Utils.processApiError(response);
         } else{
            var attrs = response.data;
            if(ZhuChao.Const.NEW_MODE == this.mode){
               this.setupNormalAttrsForm(attrs.normalAttrs);
            }
         }
      }, this);
   },
   getBasicInfoConfig : function ()
   {
      var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
      var F = this.LANG_TEXT.FIELDS.BASIC_FORM;
      var L = this.LANG_TEXT;
      var MSG = L.MSG;
      return {
         xtype : 'form',
         bodyPadding : 10,
         items : [{
            xtype : 'fieldcontainer',
            fieldLabel : F.TITLE + R_STAR,
            layout : {
               type : 'hbox',
               align : 'bottom'
            },
            items : [{
                  xtype : 'textfield',
                  maxLength : 10,
                  name : 'brand',
                  margin : '0 10 0 0 ',
                  allowBlank : false,
                  emptyText : MSG.EMPTY_BRAND
               }, {
                  xtype : 'textfield',
                  maxLength : 15,
                  width : 250,
                  name : 'title',
                  margin : '0 10 0 0 ',
                  allowBlank : false,
                  emptyText : MSG.EMPTY_TITLE
               }, {
                  xtype : 'textfield',
                  maxLength : 20,
                  width : 300,
                  name : 'description',
                  allowBlank : false,
                  emptyText : MSG.EMPTY_DESCRIPTION
               }]
            }, {
               xtype : 'textfield',
               fieldLabel : F.ADVERT + R_STAR,
               name : 'advertText',
               width : 600,
               emptyText : MSG.EMPTY_ADVERT,
               allowBlank : false
            }, {
               xtype : 'fieldcontainer',
               fieldLabel : F.KEYWORDS + R_STAR,
               allowBlank : false,
               layout : {
                  type : 'hbox'
               },
               defaults : {
                  xtype : 'textfield',
                  maxLength : 10,
                  emptyText : MSG.EMPTY_KEYWORDS,
                  margin : '0 10 0 0'
               },
               items : [{
                  name : 'keywords1',
                  allowBlank : false
               }, {
                  name : 'keywords2'
               },{
                  name : 'keywords3'
               }]
            }, {
               xtype : 'combo',
               fieldLabel : F.COMPANY + R_STAR,
               name : 'companyId',
               allowBlank : false,
               queryMode: 'local',
               displayField: 'text',
               valueField: 'id',
               editable : false,
               store : Ext.create('Ext.data.Store', {
                  fields : ['text', 'id']
               }),
               listeners : {
                  afterrender : function(combo){
                     this.companyFieldRef = combo;
                     this.mainPanelRef.appRef.getCompanyList(function(response){
                        if(!response.status){
                           Cntysoft.Kernel.Utils.processApiError(response);
                        }else{
                           combo.getStore().loadData(response.data);
                        }
                     }, this);
                  },
                  scope : this
               }
            }, {
               xtype : 'zhuchaoproductcompcategorycombo',
               width : 500,
               allowBlank : false,
               fieldLabel : F.CATEGORY + R_STAR,
               name : 'categoryId',
               listeners : {
                  afterrender : function (comp)
                  {
                     this.categoryFieldRef = comp;
                  },
                  categoryselect : this.categorySelectedHandler,
                  scope : this
               }
            }, {
               xtype : 'radiogroup',
               fieldLabel : F.STATUS,
               width : 500,
               items : [{
                     boxLabel : F.NORMAL,
                     inputValue : 3,
                     name : 'status',
                     checked : true
                  }, {
                     boxLabel : F.SHELF,
                     inputValue : 5,
                     name : 'status'
                  }],
               listeners : {
                  afterrender : function (group)
                  {
                     this.statusFieldRef = group;
                  },
                  scope : this
               }
            }],
         listeners : {
            afterrender : function (comp)
            {
               this.basicFormRef = comp;
            },
            scope : this
         }
      };
   },
   getNormalAttrFormConfig : function ()
   {
      var F = this.LANG_TEXT.NORMAL_ATTR_PANEL;
      var conf = {
         xtype : 'panel',
         title : F.TITLE,
         hidden : true,
         listeners : {
            afterrender : function (comp)
            {
               this.normalAttrsPanelRef = comp;
            },
            scope : this
         }
      };
      if(this.mode == ZhuChao.Const.NEW_MODE){
         conf.tbar = [{
               xtype : 'button',
               text : F.BTN.ADD_GROUP,
               listeners : {
                  click : function (){
                     var win = this.getNormalAttrGroupWin();
                     win.center();
                     win.show();
                  },
                  scope : this
               }
            }];
      }
      return conf;
   },

   getOtherFormConfig : function()
   {
      var R_STAR = Cntysoft.Utils.HtmlTpl.RED_STAR;
      var F = this.LANG_TEXT.FIELDS.OTHER_FORM;
      var L = this.LANG_TEXT;
      var MSG = L.MSG;
      return {
         xtype : 'form',
         title : F.TITLE,
         bodyPadding : 10,
         defaults : {
            width : 400
         },
         items : [{
               xtype : 'combo',
               fieldLabel : F.UNIT + R_STAR,
               name : 'unit',
               allowBlank : false,
               queryMode: 'local',
               displayField: 'text',
               valueField: 'text',
               value : F.UNIT_LIST.UNIT1,
               editable : false,
               store : this.getUnitStore()
            }, {
               xtype : 'numberfield',
               fieldLabel : F.MINIMUM + R_STAR,
               name : 'minimum',
               minValue : 1,
               allowBlank : false
            }, {
               xtype : 'numberfield',
               fieldLabel : F.STOCK + R_STAR,
               name : 'stock',
               minValue : 1,
               allowBlank : false
            }, {
               xtype : 'numberfield',
               fieldLabel : F.PRICE,
               name : 'price',
               emptyText : MSG.EMPTY_PRICE
            }, {
               xtype : 'radiogroup',
               fieldLabel : F.BATCH + R_STAR,
               width : 300,
               items : [{
                     boxLabel : F.BATCH_YES,
                     inputValue : 1,
                     name : 'isBatch',
                     checked : true
                  }, {
                     boxLabel : F.BATCH_NO,
                     inputValue : 2,
                     name : 'isBatch'
                  }],
               listeners : {
                  afterrender : function (group)
                  {
                     this.statusFieldRef = group;
                  },
                  scope : this
               }
            }],
         listeners : {
            afterrender : function (comp)
            {
               this.otherFormRef = comp;
            },
            scope : this
         }
      };
   },
   
   getUnitStore : function()
   {
      var LIST = this.LANG_TEXT.FIELDS.OTHER_FORM.UNIT_LIST;
      var data = [];
      for(var key in LIST){
         var item = {};
         item.text = LIST[key];
         data.push(item);
      }
      return Ext.create('Ext.data.Store', {
         fields : ['text'],
         data : data
      });
   },
   
   getDetailFormConfig : function ()
   {
      var F = this.LANG_TEXT.FIELDS.DETAIL_FORM;
      return {
         xtype : 'panel',
         title : F.TITLE,
         height : 400,
         layout : {
            type : 'vbox',
            align : 'stretch'
         },
         items : [{
               xtype : 'zhuchaocompgoodsdetailimageview',
               imageHeight : 260,
               imageWidth : 146,
               height : 300,
               autoScroll : true,
               store : new Ext.data.Store({
                  fields : [
                     {name : 'url', type : 'string', persist : false},
                     {name : 'fileRefId', type : 'integer', persist : false}
                  ]
               }),
               listeners : {
                  afterrender : function (view){
                     this.goodsDetailViewRef = view;
                  },
                  itemcontextmenu : function (grid, record, htmlItem, index, event)
                  {
                     var menu = this.getDetailImageContextMenu();
                     menu.record = record;
                     menu.store = grid.store;
                     var pos = event.getXY();
                     event.stopEvent();
                     menu.showAt(pos[0], pos[1]);
                  },
                  scope : this
               }
            }, {
               xtype : 'container',
               items : {
                  xtype : 'button',
                  text : F.UPLOAD_DETAIL_BTN,
                  listeners : {
                     click : this.uploadClickHandler,
                     scope : this
                  }
               }
            }],
         listeners : {
            afterrender : function (comp)
            {
               this.detailFormRef = comp;
            },
            scope : this
         }
      };
   },
   
   getDetailImageContextMenu : function ()
   {
      var L = this.LANG_TEXT.MENU.DETAIL_FORM;
      if(null == this.detailImageContextMenuRef){
         this.detailImageContextMenuRef = new Ext.menu.Menu({
            ignoreParentClicks : true,
            items : [{
                  text : L.DELETE_IMAGE,
                  listeners : {
                     click : function (item)
                     {
                        item.parentMenu.store.remove(item.parentMenu.record);
                        Ext.Array.remove(this.fileRefs, item.parentMenu.record.get('fileRefId'));
                     },
                     scope : this
                  }
               }]
         });
      }
      return this.detailImageContextMenuRef;
   },
   
   /**
    * @TODO 大小限制相关
    */
   uploadClickHandler : function ()
   {
      var MSG = this.LANG_TEXT.MSG;
      if(5 == this.goodsDetailViewRef.store.getCount()){
         Cntysoft.showAlertWindow(MSG.IMAGES_OVER);
         return false;
      }
      
      WebOs.showLoadScriptMask();
      var uploaderConfig = {};
      Ext.require('WebOs.Component.Uploader.Window', function(){
         WebOs.hideLoadScriptMask();
         if(null == this.uploadWinRef){
            var phpSetting = WebOs.getSysEnv().get(WebOs.Const.ENV_PHP_SETTING);
            Ext.apply(uploaderConfig, {
               initUploadPath : ZC.getAppUploadFilesPath('ZhuChao', 'Product'),
               uploaderConfig : {
                  //启用附件追踪
                  enableFileRef : true,
                  fileTypeExts : ['gif', 'png', 'jpg', 'jpeg'],
                  createSubDir : true,
                  uploadMaxSize : phpSetting.uploadMaxFileSize,
                  threads : 1,
                  useOss : ZC.getUseOss(),
               },
               listeners : {
                  fileuploadsuccess : function (file, btn){
                     if(this.goodsDetailViewRef.store.getCount() < 5){
                        var file = file.pop();
                        this.fileRefs.push(parseInt(file.rid));
                        this.goodsDetailViewRef.store.add({
                           url : ZC.getZhuChaoImageUrl(file.filename),
                           fileRefId : file.rid
                        });
                     }
                  },
                  fileuploaderror : function (response)
                  {
                     if(!response.status){
                        Cntysoft.Kernel.Utils.processApiError(response);
                     }
                  },
                  uploadcomplete : function ()
                  {
                     //是否马上关闭
                     this.uploadWinRef.close();
                  },
                  scope : this
               }
            });
            this.uploadWinRef = new WebOs.Component.Uploader.Window(uploaderConfig);
         }
         this.uploadWinRef.center();
         this.uploadWinRef.show();
      }, this);
   },
   getDescriptionConfig : function()
   {
      var DESCRIPTION = this.LANG_TEXT.FIELDS.DESCRIPTION_FORM;
      return {
         xtype : 'form',
         title : DESCRIPTION.TITLE,
         height : 400,
         items : [this.getEditorConfig()],
         listeners : {
            afterrender : function (comp)
            {
               this.decriptionFormRef = comp;
            },
            scope : this
         }
      };
   },
   
   getEditorConfig : function()
   {
      var phpSetting = WebOs.getSysEnv().get(WebOs.Const.ENV_PHP_SETTING);
      var basePath = ZC.getAppUploadFilesPath('ZhuChao', 'Product');
      this.wordEditorRef = Ext.create('WebOs.Component.CkEditor.Editor',{
         height : 400,
         toobarType : 'standard',
         defaultUploadPath : basePath,
         uploadMaxSize : phpSetting.uploadMaxFileSize,
         useOss : ZC.getUseOss(),
         listeners : {
            filerefrequest : function(ref, form)
            {
               form.setValues({url : ZC.getZhuChaoImageUrl(ref.filename)});
               var rid = parseInt(ref.rid);
               this.fileRefs.push(rid);
               if(this.imgRefMap){
                  this.imgRefMap.add(ref.filename, rid);
               }
            },
            editorready: function(){
               Ext.Function.defer(function(){
                  this.decriptionFormRef.scrollTo(0, 0);
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
      }, this);
      return this.wordEditorRef;
   },
   
   destroy : function ()
   {
      delete this.basicFormRef;
      delete this.normalAttrsPanelRef;
      delete this.categoryFieldRef;
      delete this.normalAttrGroupMap;
      delete this.statusFieldRef;
      this.fileRefs = null;
      delete this.fileRefs;
      this.imagePreviewRef.destroy();
      delete this.imagePreviewRef;
      delete this.$_current_gid_$;
      if(this.uploadWinRef){
         this.uploadWinRef.destroy();
      }
      delete this.uploadWinRef;
      if(this.normalAttrGroupWinRef){
         this.normalAttrGroupWinRef.destroy();
         delete this.normalAttrGroupWinRef;
      }
      if(this.normalAttrWinRef){
         this.normalAttrWinRef.destroy();
         delete this.normalAttrWinRef;
      }
      this.imgRefMap.clear();
      delete this.imgRefMap;
      this.callParent();
   }
});
