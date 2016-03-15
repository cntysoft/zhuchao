/*
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MessageMgr.Ui.Offer.Offer', {
   extend : 'Ext.grid.Panel',
   alias : 'widget.appzhuchaomessagemgruiofferoffer',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   runableLangKey : 'App.ZhuChao.MessageMgr',
   storeRef : null,
   constructor : function (config){
      this.applyConstraintConfig(config);
      this.LANG_TEXT = this.GET_LANG_TEXT('UI.OFFER.OFFER');
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
            {text : this.LANG_TEXT.INQUIRY, dataIndex : 'inquiry', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.INQUIRYTIME, dataIndex : 'inquiryTime', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.GOODS, dataIndex : 'goods', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.OFFER, dataIndex : 'offer', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.LOWPRICE, dataIndex : 'lowPrice', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.HIGHPRICE, dataIndex : 'highPrice', flex : 1, resizable : false, sortable : false, menuDisabled : true},
            {text : this.LANG_TEXT.OFFERTIME, dataIndex : 'offerTime', flex : 1, resizable : false, sortable : false, menuDisabled : true}
         ]
      });
      this.callParent();
   },
   getGridStore : function (){
      if(null == this.storeRef){
         this.storeRef = new Ext.data.Store({
            autoLoad : true,
            pageSize : 25,
            fields : [
               {name : 'id', type : 'integer'},
               {name : 'inquiry', type : 'string'},
               {name : 'inquiryTime', type : 'string'},
               {name : 'goods', type : 'string'},
               {name : 'offer', type : 'string'},
               {name : 'lowPrice', type : 'string'},
               {name : 'highPrice', type : 'string'},
               {name : 'offerTime', type : 'string'}
            ],
            proxy : {
               type : 'apigateway',
               callType : 'App',
               invokeMetaInfo : {
                  module : 'ZhuChao',
                  name : 'MessageMgr',
                  method : 'InquiryOffer/getInquiryAndOfferList'
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
   destroy : function (){
      delete this.storeRef;
      this.callParent();
   }
});