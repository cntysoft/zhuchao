<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Provider\Constant as P_CONST;
/**
 * 主要是处理采购商相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Provider extends AbstractScript
{
   /**
    * 用户登录
    * 
    * @param array $params
    * @return boolean
    */
   public function login($params)
   {
      $this->checkRequireFields($params, array('key', 'password', 'remember', 'type'));
      if(!isset($params['code'])) {
         $params['code'] = null;
      }
      
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'login', array($params['key'], $params['password'], $params['type'], $params['code'], $params['remember']));
   }
   
   /**
    * 注册用户
    * 
    * @param array $params
    * @return boolean
    */
   public function register($params)
   {
     $this->checkRequireFields($params, array('phone', 'password', 'code'));
     if(!isset($params['name'])) {
        $params['name'] = $params['phone'];
     }
     
     return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'register', array($params['phone'], $params['name'], $params['password'], $params['code']));
   }
   
   /**
    * 发送注册验证短信
    * 
    * @param array $params
    */
   public function checkRegAuthCode($params)
   {
      $this->checkRequireFields($params, array('phone', 'code'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      $acl->checkPicCode($params['code'], P_CONST::PIC_CODE_TYPE_REG);
      $acl->checkPhoneExist($params['phone']);
      $acl->sendSmsCode($params['phone'], P_CONST::SMS_TYPE_REG);
   }
}