/*
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 采购商管理语言包
 */
Ext.define('App.ZhuChao.Buyer.Lang.zh_CN', {
   extend : 'Cntysoft.Kernel.AbstractLangHelper',
   data : {
      PM_TEXT : {
         DISPLAY_TEXT : '采购商管理',
         ENTRY : {
            WIDGET_TITLE : '欢迎使用采购商管理程序',
            TASK_BTN_TEXT : '采购商管理'
         }
      },
      ENTRY : {
      },
      UI : {
         BUYER : {
            LIST_VIEW : {
               TITLE : '采购商列表',
               COLS : {
                  ID : 'ID',
                  NAME : '用户名称',
                  PHONE : '手机号码',
                  REGTIME : '注册时间',
                  LAST_LOGIN_TIME : '上次登陆时间',
                  STATUS : '状态',
                  SEX : '性别'
               },
               STATUS : {
                  NORMAL : '正常',
                  LOCK : '锁定'
               },
               SEX : {
                  MAN : '男',
                  WOMAN : '女',
                  SECRET : '保密'
               },
               TBAR : {
                  ADD : '添加采购商',
                  QUERY : '搜索',
                  TIP : '输入手机号码搜索',
                  ERROR_TEXT : '手机号码格式不正确'
               },
               MENU : {
                  LOCK : '锁定用户',
                  UNLOCK : '解锁用户',
                  MODIFY : '修改用户信息'
               },
               ERROR_MAP : {
                  "App/ZhuChao/Buyer/BuyerMgr" : {
                     10011 : '指定的用户不存在！'
                  }
               }
            },
            EDITOR : {
               TITLE : '采购商信息编辑器',
               FIELD : {
                  ID : '采购商ID',
                  NAME : '用户名',
                  PHONE : '手机号码',
                  PASSWORD : '密码',
                  RE_PASSWORD : '重复密码',
                  REGISTE_TIME : '注册时间',
                  LAST_LOGIN_TIME : '上次登录时间',
                  SEX : '性别',
                  STATUS : '用户状态',
                  AVATAR : '头像',
                  EXPERIENCE : '经验值',
                  SEX_NAME : {
                     MAN : '男',
                     WOMAN : '女',
                     SECRET : '保密'
                  },
                  STATUS_NAME : {
                     NORMAL : '正常',
                     LOCK : '锁定'
                  }
               },
               MSG : {
                  SAVE_OK : '采购商信息保存成功！',
                  NO_PASSWORD : '请输入用户的密码！',
                  NO_REPASSWORD : '请输入用户确认密码！',
                  PASSWORD_NOT_EQUAL : '两次输入的密码不一致！',
                  PHONE_TEXT : '请输入正确的手机号码！',
                  NOT_DIRTY : '没有任何修改'
               },
               ERROR_MAP : {
                  "App/ZhuChao/Buyer/AjaxHandler/Buyer" : {
                     10011 : '指定的用户信息不存在！'
                  },
                  "App/ZhuChao/Buyer/BuyerMgr" : {
                     10006 : '手机号码必须填写！',
                     10007 : '用户密码必须填写！',
                     10008 : '填写的用户名已经存在！',
                     10009 : '填写的手机号码已经存在！',
                     10011 : '指定的用户不存在！'
                  }
               }
            }
         }
      }
   }
});
