<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider;
 /**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'Provider';
   const APP_API_MGR= 'Acl';
   const APP_API_MANAGER = 'Mgr';
   const APP_API_LIST = 'ListView';

   const PK_PROVIDER_MANAGE = 'ProviderMgr';
   //节点排序权限
   const PK_PROVIDER_COMPANY = 'ComMgr';
   
   const PROVIDER_STATUS_NORMAL = 1;
   const PROVIDER_STATUS_LOCK = 2;
   
   const COMPANY_STATUS_NORMAL = 1;
   const COMPANY_STATUS_LOCK = 2;
   
   //手机短信验证码的类型
   const SMS_TYPE_REG = 1;
   const SMS_TYPE_FORGET = 2;
   const SMS_TYPE_SHOWPHONE = 3;
   const SMS_TYPE_CHANGEPHONE = 4;
   
   //图片验证码的类型
   const PIC_CODE_TYPE_REG = 1;
   const PIC_CODE_TYPE_FORGET = 2;
   const PIC_CODE_TYPE_LOGIN = 3;
   const PIC_CODE_TYPE_SITEMANAGER = 4;
   
   //Cookie键值
   const AUTH_KEY = 'authKey';
   const STATUS_KEY = 'authStatusKey';
   
   //用户登陆的方式
   const FRONT_USER_NAME_LOGIN = 1;
   const FRONT_USER_PHONE_LOGIN = 2;
   
   //用户网站的域名映射缓存键值
   const SITE_CACHE_KEY = 'site_name_map_cache_key';
   
   //企业类型
   const COMPANY_TYPE_GUOYOU = 1;  //国有企业
   const COMPANY_TYPE_JITI = 2; //集体企业
   const COMPANY_TYPE_SIYING = 3; //私营企业
   const COMPANY_TYPE_GUFENG = 4; //股份制企业
   const COMPANY_TYPE_LIANYING = 5; //联营企业
   const COMPANY_TYPE_WAISHANG = 6; //外商投资企业
   const COMPANY_TYPE_GOT = 7; //港、澳、台企业
   const COMPANY_TYPE_GUFENGHEZUO = 8; //股份合作企业
   const COMPATY_TYPE_GETI = 9; //个体工商
   const COMPANY_TYPE_OTHER = 10; //其他企业
}
