/*
 * Cntysoft Cloud Software Team
 *
 *@author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.ZhuChao.MarketMgr.Main', {
   extend : 'WebOs.Kernel.ProcessModel.App',
   requires : [
      'App.ZhuChao.MarketMgr.Lang.zh_CN',
      'App.ZhuChao.MarketMgr.Const',
      'App.ZhuChao.MarketMgr.Widget.Entry'
   ],
   id : 'ZhuChao.MarketMgr',
   widgetMap : {
      Entry : 'App.ZhuChao.MarketMgr.Widget.Entry',
      Ads : 'App.ZhuChao.MarketMgr.Widget.Ads'
   },
   addBargain : function (data, callback, scope)
   {
      this.callApp('Bargain/addBargain', data, callback, scope);
   },
   getBargain : function (gid, callback, scope)
   {
      this.callApp('Bargain/getBargain', {id : gid}, callback, scope);
   },
   updateBargain : function (data, callback, scope){
      this.callApp('Bargain/updateBargain', data, callback, scope);
   },
   deleteBargain : function (gid, callback, scope){
      this.callApp('Bargain/deleteBargain', {
         id : gid
      }, callback, scope);
   },
   getCouponInfo : function (id, callback, scope)
   {
      this.callApp('Coupon/getCouponInfo', {id : id}, callback, scope);
   },
   updateCouponInfo : function (data, callback, scope)
   {
      this.callApp('Coupon/updateCouponInfo', data, callback, scope);
   },
   addCouponInfo : function (data, callback, scope)
   {
      this.callApp('Coupon/addCouponInfo', data, callback, scope);
   },
   deleteCouponInfo : function (cid, callback, scope){
      this.callApp('Coupon/deleteCouponInfo', {
         id : cid
      }, callback, scope);
   },
   setCouponStatus : function (data, callback, scope)
   {
      this.callApp('Coupon/setCouponStatus', data, callback, scope);
   },
   addCouponGoods : function (data, callback, scope)
   {
      this.callApp('Coupon/addCouponGoods', data, callback, scope);
   },
   getDiscountInfo : function (id, callback, scope)
   {
      this.callApp('Discount/getDiscountInfo', {id : id}, callback, scope);
   },
   updateDiscountInfo : function (data, callback, scope)
   {
      this.callApp('Discount/updateDiscountInfo', data, callback, scope);
   },
   addDiscountInfo : function (data, callback, scope)
   {
      this.callApp('Discount/addDiscountInfo', data, callback, scope);
   },
   deleteDiscountInfo : function (cid, callback, scope){
      this.callApp('Discount/deleteDiscountInfo', {
         id : cid
      }, callback, scope);
   },
   setDiscountStatus : function (data, callback, scope)
   {
      this.callApp('Discount/setDiscountStatus', data, callback, scope);
   },
   addDiscountGoods : function (data, callback, scope)
   {
      this.callApp('Discount/addDiscountGoods', data, callback, scope);
   },
   addAds : function(values, callback, scope)
   {
      this.callApp('Ads/addAds',values,callback,scope);
   },
   modifyAds : function(values, callback, scope)
   {
      this.callApp('Ads/modifyAds',values,callback,scope);
   },
   deleteAds : function(adsId, callback,scope)
   {
      this.callApp('Ads/deleteAds',{id : adsId},callback,scope);
   },
   deleteComment : function(data, callback, scope)
   {
      this.callApp('Comment/deleteComment',data,callback,scope);
   },
   verifyComment : function(data, callback, scope)
   {
      this.callApp('Comment/verifyComment',data,callback,scope);
   },
   loadCommentInfo : function(data, callback, scope)
   {
      this.callApp('Comment/getCommentInfo',data,callback,scope);
   },
   modifyCommentReply : function(data, callback, scope)
   {
      this.callApp('Comment/modifyCommentReply',data,callback,scope);
   },
   addCommentReply : function(data, callback, scope)
   {
      this.callApp('Comment/addCommentReply',data,callback,scope);
   },
   deleteCommentReply : function(data, callback, scope)
   {
      this.callApp('Comment/deleteCommentReply',data,callback,scope);
   }
});