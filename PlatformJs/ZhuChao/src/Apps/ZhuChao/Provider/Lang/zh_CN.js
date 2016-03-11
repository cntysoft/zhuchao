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
        }
    }
});
