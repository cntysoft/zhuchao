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
Ext.define('App.ZhuChao.Provider.Ui.Company.Editor', {
   extend : 'Ext.form.Panel',
   requires : [
      'App.ZhuChao.Provider.Const',
      'ZhuChao.Kernel.Component.Uploader.SimpleUploader'
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
   currentCompany : null,
   targetLoadId : null,
   nameFieldRef : null,
   iconSrc : null,
   imageRef : null,
   constructor : function (config)
   {
      config = config || {};
      this.LANG_TEXT = this.GET_LANG_TEXT('COMPANY.EDITOR');
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
         layout : {
            type : 'table',
            columns : 2
         },
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
            width : 730
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
         this.mainPanelRef.appRef.getProviderCompany(id, this.afterLoadNodeHandler, this);
         this.$_current_nid_$ = id;
      }
   },
   afterLoadNodeHandler : function (response)
   {
      this.loadMask.hide();
      if(!response.status){
         Cntysoft.Kernel.Utils.processApiError(response);
      } else{
         this.currentCompany = response.data;
         this.getForm().setValues(this.currentCompany);
         this.iconSrc = this.currentCompany.logo;
         this.imageRef.setSrc(ZC.getZhuChaoImageUrl(this.currentCompany.logo));
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
//         this.nameFieldRef.setDisabled(true);
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
         this.currentCompany = null;
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
            Ext.apply(values, {
               logo : this.iconSrc
            });
            this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
            this.mainPanelRef.appRef.createProviderCompany(values, this.afterSaveHandler, this);
         } else if(C.MODIFY_MODE == this.mode){
            var id = this.targetLoadId;
            var values = form.getValues();
            Ext.apply(values, {
               logo : this.iconSrc
            });
            var rawData = this.currentCompany;
            Ext.each(values, function (name){
               if(rawData[name] == values[name]){
                  delete values[name];
               }
            }, this);
            if(!Ext.isEmpty(values)){
               this.setLoading(Cntysoft.GET_LANG_TEXT('MSG.SAVE'));
               this.mainPanelRef.appRef.updateProviderCompany(id, values, this.afterSaveHandler, this);
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
            xtype : 'combo',
            name : 'providerId',
            fieldLabel : F.PROVIDER,
            fields : ['code', 'name'],
            displayField : 'name',
            valueField : 'code',
            minChars : 1,
            store : this.createProviderStore(),
            emptyText : F.EMPTY_TEXT,
            listeners : {
               focus : function (combo)
               {
                  combo.expand();
               },
               scope : this
            }
         }, {
            xtype : 'fieldcontainer',
            layout : 'hbox',
            fieldLabel : F.PCD,
            defaults : {
               margin : '0 10 0 0'
            },
            items : [{
                  xtype : 'combo',
                  name : 'province',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {
                     fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {
                     afterrender : function (combo){
                        this.provinceRef = combo;
                        this.mainPanelRef.appRef.getProvinces(function (response){
                           combo.getStore().loadData(response.data);
                           this.regprovinceRef.getStore().loadData(response.data);
                           if(this.currentCompany && this.currentCompany['province']){
                              combo.setValue(this.currentCompany['province']);
                           }
                           if(this.currentCompany && this.currentCompany['registerProvince']){
                              this.regprovinceRef.setValue(this.currentCompany['registerProvince']);
                           }
                        }, this);
                     },
                     change : function (combo, newValue){
                        if(newValue == 0){
                           return;
                        }
                        this.mainPanelRef.appRef.getArea(newValue, function (response){
                           this.cityRef.getStore().loadData(response.data);
                           if(this.currentCompany && newValue == this.currentCompany['province']){
                              this.cityRef.setValue(this.currentCompany['city']);
                           } else{
                              this.cityRef.setValue(0);
                           }
                        }, this);
                     },
                     scope : this
                  }
               }, {
                  xtype : 'combo',
                  name : 'city',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {
                     fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {
                     afterrender : function (combo){
                        this.cityRef = combo;
                     },
                     change : function (combo, newValue){
                        if(newValue == 0){
                           return;
                        }
                        this.mainPanelRef.appRef.getArea(newValue, function (response){
                           this.districtRef.getStore().loadData(response.data);
                           if(this.currentCompany && newValue == this.currentCompany['city']){
                              this.districtRef.setValue(this.currentCompany['district']);
                           } else{
                              this.districtRef.setValue(0);
                           }
                        }, this);
                     },
                     scope : this
                  }
               }, {xtype : 'combo',
                  name : 'district',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {afterrender : function (combo){
                        this.districtRef = combo;
                     },
                     scope : this
                  }
               }]
         }, {fieldLabel : F.ADDR,
            allowBlank : false,
            name : 'address'
         }, {xtype : 'fieldcontainer',
            colspan : 2,
            fieldLabel : F.IMG,
            layout : {type : 'hbox',
               align : 'bottom'
            },
            items : [{
                  xtype : 'image',
                  width : 100,
                  height : 100,
                  style : 'border:1px solid #EDEDED',
                  listeners : {afterrender : function (comp){
                        this.imageRef = comp;
                     },
                     scope : this
                  }
               }, {xtype : 'zhuchaosimpleuploader',
                  uploadPath : this.mainPanelRef.appRef.getUploadFilesPath(),
                  useOss : ZC.getUseOss(),
                  createSubDir : true,
                  fileTypeExts : ['gif', 'png', 'jpg', 'jpeg'],
                  margin : '0 0 0 5',
                  maskTarget : this,
                  enableFileRef : false,
                  overwrite : true,
                  buttonText : F.UPLOAD,
                  listeners : {fileuploadsuccess : this.uploadSuccessHandler,
                     scope : this
                  }
               }]
         }, {
            xtype : 'combo',
            name : 'type',
            allowBlank : false,
            editable : false,
            fieldLabel : F.TYPE,
            fields : ['code', 'name'],
            displayField : 'name',
            valueField : 'code',
            minChars : 1,
            store : Ext.create('Ext.data.Store', {
               fields : ['name', 'code'],
               data : [
                  {"code" : "1", "name" : F.TYPE_NAME.GUOYOU},
                  {"code" : "2", "name" : F.TYPE_NAME.JITI},
                  {"code" : "3", "name" : F.TYPE_NAME.SIYING},
                  {"code" : "4", "name" : F.TYPE_NAME.GUFENG},
                  {"code" : "5", "name" : F.TYPE_NAME.LIANYING},
                  {"code" : "6", "name" : F.TYPE_NAME.WAISHANG},
                  {"code" : "7", "name" : F.TYPE_NAME.GOT},
                  {"code" : "8", "name" : F.TYPE_NAME.GUFENGHEZUO}
               ]
            })
         }, {
            xtype : 'combo',
            name : 'tradeMode',
            allowBlank : false,
            editable : false,
            fieldLabel : F.TRADEMODE,
            fields : ['code', 'name'],
            displayField : 'name',
            valueField : 'code',
            minChars : 1,
            store : Ext.create('Ext.data.Store', {
               fields : ['name', 'code'],
               data : [
                  {"code" : "1", "name" : F.TRADEMODE_NAME.SALE},
                  {"code" : "2", "name" : F.TRADEMODE_NAME.PRODUCE},
                  {"code" : "3", "name" : F.TRADEMODE_NAME.DESIGN},
                  {"code" : "4", "name" : F.TRADEMODE_NAME.INFO},
                  {"code" : "5", "name" : F.TRADEMODE_NAME.STOP},
                  {"code" : "6", "name" : F.TRADEMODE_NAME.STOD},
                  {"code" : "7", "name" : F.TRADEMODE_NAME.PTOD},
                  {"code" : "8", "name" : F.TRADEMODE_NAME.DPS}
               ]
            })
         }, {fieldLabel : F.POSTCODE,
            name : 'postCode'
         }, {fieldLabel : F.WEBSITE,
            name : 'website'
         }, {xtype : 'textarea',
            fieldLabel : F.DESCRIPTION,
            allowBlank : false,
            name : 'description',
            width : 730,
            height : 80
         }, {xtype : 'textarea',
            allowBlank : false,
            fieldLabel : F.PRODUCTS,
            emptyText : F.PEMPTY_TEXT,
            regex : /([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[0-9a-zA-Z_])*(,)([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[0-9a-zA-Z_])*/,
            regexText : F.PEMPTY_TEXT,
            name : 'products',
            width : 730,
            height : 80
         }, {xtype : 'radiogroup',
            colspan : 2,
            columns : 2,
            fieldLabel : F.STATUS,
            items : [{
                  boxLabel : F.STATUS_NAME.NORMAL,
                  name : 'status',
                  inputValue : 1,
                  checked : true
               }, {boxLabel : F.STATUS_NAME.LOCK,
                  name : 'status',
                  inputValue : 2}]
         }, {xtype : 'textarea',
            colspan : 2,
            fieldLabel : F.CUSTOMER,
            allowBlank : false,
            name : 'customer',
            width : 730,
            height : 80
         }, {fieldLabel : F.BRAND,
            name : 'brand'
         }, {fieldLabel : F.REGCAPITAL,
            allowBlank : false,
            name : 'registerCapital'
         }, {fieldLabel : F.REGYEAR,
            name : 'registerYear'
         }, {xtype : 'fieldcontainer',
            layout : 'hbox',
            fieldLabel : F.REGADDR,
            defaults : {margin : '0 10 0 0'
            },
            items : [{
                  xtype : 'combo',
                  name : 'registerProvince',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {afterrender : function (combo){
                        this.regprovinceRef = combo;
                     },
                     change : function (combo, newValue){
                        if(newValue == 0){
                           return;
                        }
                        this.mainPanelRef.appRef.getArea(newValue, function (response){
                           this.regcityRef.getStore().loadData(response.data);
                           if(this.currentCompany && newValue == this.currentCompany['registerProvince']){
                              this.regcityRef.setValue(this.currentCompany['registerCity']);
                           } else{
                              this.regcityRef.setValue(0);
                           }
                        }, this);
                     },
                     scope : this
                  }
               }, {
                  xtype : 'combo',
                  name : 'registerCity',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {
                     fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {
                     afterrender : function (combo){
                        this.regcityRef = combo;
                     },
                     change : function (combo, newValue){
                        if(newValue == 0){
                           return;
                        }
                        this.mainPanelRef.appRef.getArea(newValue, function (response){
                           this.regdistrictRef.getStore().loadData(response.data);
                           if(this.currentCompany && newValue == this.currentCompany['registerCity']){
                              this.regdistrictRef.setValue(this.currentCompany['registerDistrict']);
                           } else{
                              this.regdistrictRef.setValue(0);
                           }
                        }, this);
                     },
                     scope : this
                  }
               }, {
                  xtype : 'combo',
                  name : 'registerDistrict',
                  queryMode : 'local',
                  displayField : 'name',
                  valueField : 'code',
                  store : Ext.create('Ext.data.Store', {
                     fields : ['name', 'code']
                  }),
                  editable : false,
                  emptyText : F.EMPTY_TEXT,
                  listeners : {
                     afterrender : function (combo){
                        this.regdistrictRef = combo;
                     },
                     scope : this
                  }
               }]
         }, {
            fieldLabel : F.LEGALPERSON,
            name : 'legalPerson'
         }, {
            fieldLabel : F.BANK,
            name : 'bank'
         }, {
            fieldLabel : F.BANKACCOUNT,
            name : 'bankAccount'
         }];
   },
   createProviderStore : function ()
   {
      return new Ext.data.Store({
         autoLoad : true,
         fields : [{name : 'code', type : 'integer', persist : false},
            {name : 'name', type : 'string', persist : false}
         ],
         proxy : {type : 'apigateway',
            callType : 'App',
            invokeMetaInfo : {module : 'ZhuChao',
               name : 'Provider',
               method : 'ComMgr/getProviderListAll'
            },
            reader : {type : 'json',
               rootProperty : 'items',
               totalProperty : 'total'
            }
         }
      });
   },
   uploadSuccessHandler : function (file)
   {
      var file = file.pop();
      this.iconSrc = file.filename;
      this.imageRef.setSrc(ZC.getZhuChaoImageUrl(file.filename));
   },
   getIdFieldConfig : function ()
   {
      return {
         xtype : 'displayfield',
         colspan : 2,
         fieldLabel : this.LANG_TEXT.FIELD.ID,
         name : 'id'
      };
   },
   destroy : function ()
   {
      delete this.currentCompany;
      delete this.nameFieldRef;
      delete this.targetLoadId;
      delete this.regprovinceRef;
      delete this.regcityRef;
      delete this.regdistrictRef;
      delete this.provinceRef;
      delete this.cityRef;
      delete this.districtRef;
      delete this.iconSrc;
      delete this.imageRef;
      this.callParent();
   }
});
