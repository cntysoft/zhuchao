<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer;

class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'Buyer';
   const APP_API_BUYER_MGR = 'BuyerMgr';
   const APP_API_BUYER_ACL = 'Acl';
   
   //手机短信验证码的类型
   const SMS_TYPE_REG = 1;
   const SMS_TYPE_FORGET = 2;
   
   //图片验证码的类型
   const PIC_CODE_TYPE_REG = 1;
   const PIC_CODE_TYPE_FORGET = 2;
   const PIC_CODE_TYPE_LOGIN = 3;
   const PIC_CODE_TYPE_SITEMANAGER = 4;
   
   //用户的状态
   const USER_STATUS_NORMAL = 1;
   const USER_STATUS_LOCK = 2;
   
   //Cookie键值
   const AUTH_KEY = 'authKey';
   const STATUS_KEY = 'authStatusKey';
   
   //用户登陆的方式
   const FRONT_USER_NAME_LOGIN = 1;
   const FRONT_USER_PHONE_LOGIN = 2;
   
   const ADDRESS_MAX_NUM = 6;  //收获地址最大数量
   
   //收获地址状态
   const ADDRESS_STATUS_NOT_DEFAULT = 0;
   const ADDRESS_STATUS_DEFAULT = 1; 
}
