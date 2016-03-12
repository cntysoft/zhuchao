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

   const PROVIDER_STATUS_NORMAL = 1;
   const PROVIDER_STATUS_LOCK = 2;
   
   //手机短信验证码的类型
   const SMS_TYPE_REG = 1;
   const SMS_TYPE_FORGET = 2;
   
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
}
