<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;   

/**
 * 主要是处理采购会员相关的的Ajax调用
 * 
 * @package FrontApi
 */
class User extends AbstractScript
{
   /**
    * 采购会员注册
    * <code>
    *    array(
    *       'phone' => 15522222222,
    *       'password' => 'dwadawdwadadawd',
    *       'smsCode' => 222222
    *    )
    * </code>
    * 
    * @param array $params
    * @return type
    */
   public function register(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'password', 'smsCode'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'register',
         array($params['phone'], $params['password'], $params['smsCode'])
      );
   }
   
   /**
    * 采购会员登陆的方法
    * 
    * @param array $params
    * @return boolean
    */
   public function login(array $params)
   {
      $this->checkRequireFields($params, array('key', 'password', 'remember'));
      
      if(isset($params['picCode'])){
         $this->checkPicCode(array('code' => $params['picCode'], BUYER_CONST::PIC_CODE_TYPE_LOGIN));
      }
      
      $type = $this->getLoginType($params['key']);
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'login',
         array($params['key'], $params['password'], $type)
      );
   }
   
   /**
    * 发送手机短信
    * 
    * @param array $params
    * @return type
    */
   public function sendSmsCode(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'type'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'sendSmsCode',
         array($params['phone'], (int)$params['type'])
      );
   }
   
   /**
    * 找回密码的操作
    * 
    * @param array $params
    * @return boolean
    */
   public function findPassword(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'password'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'findPassword',
         array($params['phone'], $params['password'])
      );
   }
   
   /**
    * 用户中心修改用户的密码
    * 
    * @param array $params
    * @return 
    */
   public function resetPassword(array $params)
   {
      $this->checkRequireFields($params, array('oldPassword', 'newPassword'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'resetPassword',
         array($params['oldPassword'], $params['newPassword'])
      );
   }
   
   /**
    * 检测短信验证码是否正确
    * 
    * @param array $params
    * @return boolean
    */
   public function checkSmsCode(array $params)
   {
      $this->checkSmsCode($params, array('phone', 'code', 'type'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'checkSmsCode',
         array($params['phone'], $params['code'], $params['type'])
      );
   }
   
   /**
    * 获取用户的登录方式
    * 
    * @param string $key
    * @return integer
    */
   public function getLoginType($key)
   {
      if(preg_match('/^1[0-9]{10}$/', $key)){
         return BUYER_CONST::FRONT_USER_PHONE_LOGIN;
      }else{
         return BUYER_CONST::FRONT_USER_NAME_LOGIN;
      }
   }
   
   /**
    * 检查图片验证码是否正确
    * 
    * @param array $params
    * @return boolean
    */
   public function checkPicCode(array $params)
   {
      $this->checkRequireFields($params, array('code', 'type'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'checkPicCode',
         array($params['code'], (int)$params['type'])
      );
   }
   
}