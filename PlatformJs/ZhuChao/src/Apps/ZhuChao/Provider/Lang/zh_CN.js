/*
 * Cntysoft Cloud Software Team
 *
 * @author Chanwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 商品分类语言包
 */
Ext.define('App.ZhuChao.Provider.Lang.zh_CN', {
   extend : 'Cntysoft.Kernel.AbstractLangHelper',
   data : {
      PM_TEXT : {
         DISPLAY_TEXT : '供应商管理',
         ENTRY : {
            WIDGET_TITLE : '欢迎使用供应商管理程序',
            TASK_BTN_TEXT : '供应商管理'
         },
         PROVIDER : {
            WIDGET_TITLE : '欢迎使用供应商管理程序',
            TASK_BTN_TEXT : '供应商管理'
         },
         COMPANY : {
            WIDGET_TITLE : '欢迎使用企业管理程序',
            TASK_BTN_TEXT : '企业管理'
         }
      },
      ENTRY : {
      },
      UI : {
         LIST_VIEW : {
            TITLE : '供应商列表',
            COLS : {
               ID : 'ID',
               NAME : '用户名称',
               PHONE : '手机号码',
               REGTIME : '注册时间',
               LAST_LOGIN_TIME : '上次登陆时间',
               STATUS : '状态'
            },
            STATUS : {
               NORMAL : '正常',
               LOCK : '锁定'
            },
            TBAR : {
               ADD : '添加供应商',
               QUERY : '搜索',
               TIP : '输入手机号码搜索',
               ERROR_TEXT : '手机号码格式不正确'
            },
            MENU : {
               LOCK : '锁定用户',
               UNLOCK : '解锁用户',
               MODIFY : '修改用户信息'
            }
         },
         EDITOR : {
            TITLE : '供应商信息编辑器',
            FIELD : {
               BASEINFO : '基本信息',
               PROFILE : '详细信息',
               ID : '供应商ID',
               NAME : '用户名',
               PHONE : '手机号码',
               PASSWORD : '密码',
               RE_PASSWORD : '重复密码',
               REGISTE_TIME : '注册时间',
               LAST_LOGIN_TIME : '上次登录时间',
               LAST_LOGIN_IP : '上次登录IP',
               REAL_NAME : '真实姓名',
               SEX : '性别',
               DEPARTMENT : '所在部门',
               POSITION : '职位名称',
               EMAIL : '邮箱信息',
               SHOW_PHONE : '展示手机',
               QQ : 'QQ号码',
               TEL : '联系电话',
               FAX : '传真号码',
               STATUS : '用户状态',
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
               SAVE_OK : '供应商信息保存成功！',
               NO_PASSWORD : '请输入用户的密码！',
               PASSWORD_NOT_EQUAL : '两次输入的密码不一致！',
               PHONE_TEXT : '请输入正确的手机号码！',
               NOT_DIRTY : '没有任何修改'
            },
            ERROR_MAP : {
               "App/ZhuChao/Provider/Mgr" : {
                  10001 : '手机号码已经存在！',
                  10002 : '用户名称已经存在！'
               }
            }
         }
      },
      COMPANY : {
         LIST_VIEW : {
            TITLE : '供应商企业列表',
            COLS : {
               ID : 'ID',
               NAME : '企业名称',
               PROVIDER : '供货商',
               INTIME : '录入时间',
               STATUS : '状态'
            },
            STATUS : {
               NORMAL : '正常',
               LOCK : '锁定'
            },
            TBAR : {
               ADD : '添加企业',
               QUERY : '搜索',
               TIP : '输入企业名称搜索'
            },
            MENU : {
               LOCK : '锁定企业',
               UNLOCK : '解锁企业',
               MODIFY : '修改企业信息'
            }
         },
         EDITOR : {
            TITLE : '供应商企业信息编辑器',
            FIELD : {
               BASEINFO : '基本信息',
               PROFILE : '详细信息',
               ID : '企业ID',
               NAME : '企业名',
               PCD : '企业地址',
               PROVIDER : '供应商',
               PRODUCTS : '主营产品',
               PEMPTY_TEXT : '产品间以\',\'隔开',
               TYPE : '企业类型',
               TRADEMODE : '经营模式',
               ADDR : '详细地址',
               POSTCODE : '邮编',
               WEBSITE : '企业网址',
               IMG : '企业logo',
               UPLOAD : '上传文件',
               DESCRIPTION : '企业描述',
               STATUS : '企业状态',
               EMPTY_TEXT : '请选择',
               CUSTOMER : '主要客户群体',
               BRAND : '品牌名称',
               REGCAPITAL : '注册资本(万)',
               REGYEAR : '注册年份',
               REGADDR : '注册地址',
               LEGALPERSON : '法人/负责人',
               BANK : '开户银行',
               BANKACCOUNT : '开户帐号',
               SEX_NAME : {
                  MAN : '男',
                  WOMAN : '女',
                  SECRET : '保密'
               },
               STATUS_NAME : {
                  NORMAL : '正常',
                  LOCK : '锁定'
               },
               TYPE_NAME : {
                  GUOYOU : '国有企业',
                  JITI : '集体所有制',
                  SIYING : '私营企业',
                  GUFENG : '股份制企业',
                  LIANYING : '联营企业',
                  WAISHANG : '外商投资企业',
                  GOT : '港、澳、台',
                  GUFENGHEZUO : '股份合作企业'
               },
               TRADEMODE_NAME : {
                  SALE : '销售型',
                  PRODUCE : '生产型',
                  DESIGN : '设计型',
                  INFO : '信息服务型',
                  STOP : '销售+生产型',
                  STOD : '销售+设计型',
                  PTOD : '生产+设计型',
                  DPS : '销售+生产+设计型'
               }
            },
            MSG : {
               SAVE_OK : '企业信息保存成功！',
               NO_PASSWORD : '请输入用户的密码！',
               PASSWORD_NOT_EQUAL : '两次输入的密码不一致！',
               PHONE_TEXT : '请输入正确的手机号码！',
               NOT_DIRTY : '没有任何修改'
            },
            ERROR_MAP : {
               "App/ZhuChao/Provider/Mgr" : {
                  10001 : '手机号码已经存在！',
                  10002 : '用户名称已经存在！'
               }
            }
         }
      }
   }
});
