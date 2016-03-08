/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
Ext.define('App.Sys.Login.Ui.LoginPanel', {
   extend : 'Ext.Component',
   mixins : {
      langTextProvider : 'WebOs.Mixin.RunableLangTextProvider'
   },
   /**
    * {@link Cntysoft.Mixin.RunableLangTextProvider#property-runableLangKey}
    *
    * @property {String} runableLangKey
    */
   runableLangKey : 'App.Sys.Login',
   childEls : ['loginPanelEl', 'usernameEl', 'passwordEl', 'loginEl', 'errorTextEl', 'chkcodeEl', 'chkcodeimgEl', 'chkcodeimgBtnEl', 'copyrightEl', 'loginBoxEl', 'loadingEl'],
   renderTpl : [
      '<div class="l_main"  id="{id}-loginPanelEl" data-ref="loginPanelEl">',
      '<div class="m_content" id="{id}-loginBoxEl" data-ref="loginBoxEl">',
      '<h3>{Title}</h3>',
      '<div class="input_ele">',
      '<i class="user_logo"></i>',
      '<input type="text" placeholder="{Username_Holder}" id ="{id}-usernameEl" data-ref="usernameEl" class="name" name="username">',
      '</div>',
      '<div class="input_ele">',
      '<i class="pwd_logo"></i>',
      '<input type="password"  placeholder="{Password_Holder}" id ="{id}-passwordEl" class="password" name ="password" data-ref="passwordEl">',
      '</div>',
      '<div class="input_ele confirm clearfix">',
      '<i class="confirm_logo"></i>',
      '<input type="text" placeholder="{Check_Holder}" id ="{id}-chkcodeEl" name ="chkcode" blankText ="{chkcode_blank_text}" data-ref="chkcodeEl">',
      '<div class="confirm_right">',
      '<img src="{CheckCodeSrc}" id="{id}-chkcodeimgEl" data-ref="chkcodeimgEl">',
      '<span id="{id}-chkcodeimgBtnEl" data-ref="chkcodeimgBtnEl">{Chk_Code_Change}</span>',
      '</div>',
      '<span class="tip hide" id="{id}-errorTextEl" data-ref="errorTextEl"></span>',
      '</div>',
      '<div class="submit">',
      '<input type="submit" value="{Ui_Login}" id ="{id}-loginEl" data-ref="loginEl">',
      '<img src="/Statics/Images/Global/icon_loading.gif" class="hide" id ="{id}-loadingEl" data-ref="loadingEl">',
      '</div>',
      '</div>',
      '</div>',
      '<div class="footer" id="{id}-copyrightEl" data-ref="copyrightEl">',
      '<span>郑州神恩信息科技有限公司 版权所有 2011 - 2016</span>',
      '<a href="http://www.sheneninfo.com/" target="__blank">郑州神恩信息科技官方网站</a>',
      '</div>'
   ],
   errorCls : 'error',

   /**
    * 当数据验证成功的时候发出登录请求
    * @event loginrequest
    */
   initComponent : function()
   {
      //设置body
      this.addListener('afterrender', this.ensureLayoutHandler, this);
      Ext.on('resize', this.ensureLayoutHandler, this);
      this.callParent();
   },

   initRenderData : function()
   {
      var TEXT = this.GET_LANG_TEXT('PLACE_HOLDER'), F = this.GET_LANG_TEXT('FIELDS');
      return Ext.apply(this.callParent(), {
         Title : F.TITLE,
         Chk_Code_Change : F.CHK_CODE_CHANGE,
         CheckCodeSrc : this.getCheckCodeUrl(),
         Ui_Login : Cntysoft.GET_LANG_TEXT('UI.BTN.LOGIN'),
         Username_Holder : TEXT.USERNAME,
         Password_Holder : TEXT.PASSWORD,
         Check_Holder : TEXT.CHECKCODE
      });
   },

   /**
    * @return {String} 验证码生成地址
    */
   getCheckCodeUrl : function()
   {
      var info = Cntysoft.getDomainInfo();
      return info.domain + '/Utils/Index/siteManagerChkCode?dc_' + (new Date().getTime());
   },
   /**
    * @returns {undefined}
    */
   generateChkCode : function()
   {
      this.chkcodeimgEl.set({
         src : this.getCheckCodeUrl()
      });
   },

   /**
    * 检查表单是否正确
    *
    * @return {Boolean}
    */
   isValid : function()
   {
      var values = this.getFormValues();
      var data;
      var el;
      var ret = true;
      for(var key in values) {
         data = Ext.String.trim(values[key]);
         if('' == data){
            el = this[key + 'El'];
            el.addCls(this.errorCls);
            ret = false;
         }
      }
      return ret;
   },

   /**
    * @return {Object}
    */
   getFormValues : function()
   {
      var els = [this.usernameEl, this.passwordEl, this.chkcodeEl];
      var len = els.length;
      var item;
      var values = {};
      var key;
      for(var i = 0; i < len; i++) {
         item = els[i];
         key = item.getAttribute('name');
         values[key] = item.getValue();
         item.hasError = true;
         item.isBlank = true;
      }
      return values;
   },

   onLogin : function()
   {
      if(this.isValid()){
         this.loginEl.set({'value':''});
         this.loadingEl.show();
         var data = this.getFormValues();
         var password = Cntysoft.Utils.Common.hashPwd(data.password);
         this.errorTextEl.hide();
         if(this.hasListeners.loginrequest){
            this.fireEvent('loginrequest', data.username, password, data.chkcode);
         }
      }
   },

   onReset : function()
   {
      var els = [this.usernameEl, this.passwordEl, this.chkcodeEl];
      var len = els.length;
      var item;
      for(var i = 0; i < len; i++) {
         item = els[i];
         item.dom.value = '';
         item.removeCls(this.errorCls);
         item.isBlank = false;
      }
   },

   //private
   onRender : function()
   {
      this.callParent(arguments);
      this.chkcodeimgBtnEl.on({
         click : this.generateChkCode,
         scope : this
      });
      this.loginEl.on({
         click : this.onLogin,
         scope : this
      });
      this.chkcodeEl.on({
         blur : this.keyupHandler,
         keyup : this.keyupHandler,
         scope : this
      });
      this.usernameEl.on({
         blur : this.keyupHandler,
         keyup : this.keyupHandler,
         scope : this
      });
      this.passwordEl.on({
         blur : this.keyupHandler,
         keyup : this.keyupHandler,
         scope : this
      });
      this.usernameEl.focus();
      this.setUpKeyMap();
   },

   /**
    * 初始化 ENTER 按键点击事件
    */
   setUpKeyMap : function()
   {
      if(!this.keyMap){
         this.keyMap = new Ext.util.KeyMap({
            target : Ext.getBody(),
            key : Ext.event.Event.ENTER,
            fn : this.enterKeyHandler,
            scope : this
         });
      } else{
         this.keyMap.addBinding({
            target : Ext.getBody(),
            key : Ext.event.Event.ENTER,
            fn : this.enterKeyHandler,
            scope : this
         });
      }
   },

   /**
    * 移除 ENTER 按键点击事件
    */
   removeKeyMap : function()
   {
      this.keyMap.removeBinding({
         target : Ext.getBody(),
         key : Ext.event.Event.ENTER,
         fn : this.enterKeyHandler,
         scope : this
      });
   },

   enterKeyHandler : function()
   {
      this.onLogin();
   },

   keyupHandler : function(event, htmlDom, opt)
   {
      var el = Ext.fly(htmlDom);
      var value = el.getValue();
      if('' == Ext.String.trim(value)){
         el.addCls(this.errorCls);
      } else{
         el.removeCls(this.errorCls);
      }
   },
   /**
    * 保证登录界面的布局
    */
   ensureLayoutHandler : function()
   {
      var height = Ext.dom.Element.getViewportHeight();
      var divHeight = height > 500 ? height : 500;
      this.loginPanelEl.setHeight(divHeight);
   },

   //private
   beforeDestroy : function()
   {
      //在这个地方一定不能忘记作用域 要不然删除不掉
      Ext.un('resize', this.ensureLayoutHandler, this);
      this.callParent(arguments);
   },
   destroy : function()
   {
      Ext.destroy(this.keyMap);
      delete this.keyMap;
      this.callParent();
   }
});