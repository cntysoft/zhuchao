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
   const APP_API_BUY_MGR = 'BuyerMgr';
   
   //手机短信验证码的类型
   const SMS_TYPE_REG = 1;
   const SMS_TYPE_FORGET = 2;
   
   //图片验证码的类型
   const PIC_CODE_TYPE_REG = 1;
   const PIC_CODE_TYPE_FORGET = 2;
   const PIC_CODE_TYPE_SITEMANAGER = 3;
   
   //用户的状态
   const USER_STATUS_NORMAL = 1;
   const USER_STATUS_LOCK = 2;
   
   //用户登陆的方式
   const FRONT_USER_NAME_LOGIN = 1;
   const FRONT_USER_PHONE_LOGIN = 2;
}
