<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */

namespace Cntysoft;
//一些标准的SESSION KEY
//系统后台登录验证码session键值
const SITEMANAGER_S_KEY_CHK_CODE = 'sitemanager_s_key_chk_code';
//系统后台用户信息session键值
const SITEMANAGER_S_KEY_SYS_USER_INFO = 'sitemanager_key_user_info';
//系统后台用户角色信息session键值
const SITEMANAGER_S_KEY_ROLE = 'sitemanager_key_role';
//系统自定义表单信息session键值
const SITEMANAGER_CUSTOM_FORM_ADD_CHK_CODE = 'sitemanager_custom_form_add_chk_code';

//前台用户注册时图片验证码session键值
const FRONT_USER_S_KEY_REG_CHK_CODE = 'front_user_s_key_reg_chk_code';
//前台用户忘记密码时图片验证码session键值
const FRONT_USER_S_KEY_FORGET_CHK_CODE = 'front_user_s_key_reg_forget_chk_code';
//前台用户登陆时图片验证码的session的键值
const FRONT_USER_S_KEY_LOGIN_CHK_CODE = 'front_user_s_key_reg_login_chk_code';
//注册时发送短信验证或者邮件验证码的session键值
const FRONT_USER_S_KEY_REG_MSG_CODE = 'front_user_s_key_reg_msg_code';
//忘记密码发送短信验证或者邮件验证码的session键值
const FRONT_USER_S_KEY_FORGET_MSG_CODE = 'front_user_s_key_forget_msg_code';
//前台用户信息session键值
const FRONT_USER_BUYER_S_KEY_INFO = 'front_user_buyer_s_key_info';
//采购会员的session键值
const FRONT_USER_PROVIDER_S_KEY_INFO = 'front_user_provider_s_key_info';

//前台用户注册时图片验证码session键值
const PROVIDER_USER_S_KEY_REG_CHK_CODE = 'provider_user_s_key_reg_chk_code';
//前台用户忘记密码时图片验证码session键值
const PROVIDER_USER_S_KEY_FORGET_CHK_CODE = 'provider_user_s_key_reg_forget_chk_code';
//前台用户登陆时图片验证码的session的键值
const PROVIDER_USER_S_KEY_LOGIN_CHK_CODE = 'provider_user_s_key_reg_login_chk_code';
//注册时发送短信验证或者邮件验证码的session键值
const PROVIDER_USER_S_KEY_REG_MSG_CODE = 'provider_user_s_key_reg_msg_code';
//忘记密码发送短信验证或者邮件验证码的session键值
const PROVIDER_USER_S_KEY_FORGET_MSG_CODE = 'provider_user_s_key_forget_msg_code';

//标准异常上下文
const ZHU_CHAO_STD_EXCEPTION_CONTEXT = 'ZhuChao/Kernel/StdErrorType';

//商家的默认域名
const MERCHANT_DOMAIN = 'fhzc.com';
const MERCHANT_DOMAIN_DEBUG = 'fhzc.cn';
const ZHUCHAO_PLATFORM_DBNAME = 'zhuchao_devel';

//系统的主域名
const SYS_DOMAIN = 'fhzc.com';
const SYS_DOMAIN_DEVEL = 'abc.com';

//站点系统的主域名
const ZHUCHAO_SITE_DOMAIN = 'site.fhzc.com';
const ZHUCHAO_SITE_DOMAIN_DEVEL = 'site.abc.com';
const ZHUCHAO_SITE_DB_PREFIX = 'zhuchao_site_';

//阿里OTS配置
const OTS_INTERNAL_API = 'http://fhzc-ots.cn-beijing.ots-internal.aliyuncs.com';  
const OTS_PUB_API = 'http://fhzc-ots.cn-beijing.ots.aliyuncs.com';
const ZHUCHAO_OTS_INSTANCE_NAME = 'fhzc-b2b-ots';

//阿里的API服务器
const ALI_SEARCH_API_PUB_ENTRY = 'http://opensearch-cn-beijing.aliyuncs.com';
const ALI_SEARCH_INTERNAL_ENTRY = 'http://intranet.opensearch-cn-beijing.aliyuncs.com';
//OSS相关常量的定义
const OSS_INTERNAL_ENTRY = 'oss-cn-beijing-internal.aliyuncs.com';
const OSS_PUBLIC_ENTRY = 'oss-cn-beijing.aliyuncs.com';
const ZHUCHAO_OSS_IMG_BUCKET = 'fhzc-b2b-img';
const ZHUCHAO_OSS_IMG_BUCKET_DEVEL = 'fhzc-b2b-img-devel';
const ZHUCHAO_OSS_IMG_BUCKET_DEBUG = 'fhzc-b2b-img-debug';
const ZHUCHAO_IMG_CDN_SERVER = 'img-b2b.cdn.fhzc.com';
const ZHUCHAO_IMG_CDN_SERVER_DEVEL = 'img-b2b-devel.cdn.fhzc.com';
const ZHUCHAO_IMG_CDN_SERVER_DEBUG = 'img-b2b-debug.cdn.fhzc.com';
const STORAGE_BACKEND_OSS = 1;
const STORAGE_BACKEND_LOCAL = 2;
//部署模式
const DEPLOY_TYPE_DEVEL = 1;
const DEPLOY_TYPE_LOCAL_PRODUCT = 2;
const DEPLOY_TYPE_LOCAL_DEBUG = 3;
const DEPLOY_TYPE_PUBLIC_PRODUCT = 4;
const DEPLOY_TYPE_PUBLIC_DEBUG = 5;