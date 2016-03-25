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
use Cntysoft\Kernel;
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
      if (!isset($params['code'])) {
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
      if (!isset($params['name'])) {
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

   /**
    * 发送忘记密码验证短信
    * 
    * @param array $params
    */
   public function checkForgetAuthCode($params)
   {
      $this->checkRequireFields($params, array('phone', 'chkcode'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      $acl->checkPicCode($params['chkcode'], P_CONST::PIC_CODE_TYPE_FORGET);
      $acl->checkPhoneExist($params['phone'], true);
      $acl->sendSmsCode($params['phone'], P_CONST::SMS_TYPE_FORGET);
   }

   /**
    * 忘记密码，重置密码
    * 
    * @param array $params
    */
   public function resetPasswordWithCode($params)
   {
      $this->checkRequireFields($params, array('phone', 'code', 'password'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      $acl->findPassword($params['phone'], $params['password'], $params['code']);
   }

   /**
    * 检查手机号是否注册
    * @param type $params
    * @return type
    */
   public function checkPhoneExist($params)
   {
      $this->checkRequireFields($params, array('phone'));
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'providerPhoneExist', array($params['phone']));
   }

   /**
    * 更新供应商的信息
    * 
    * @param array $params
    * @return boolean
    */
   public function updateUserInfo($params)
   {
      unset($params['id']);
      unset($params['password']);
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      //判断是否可以修改用户名
      if (isset($params['name'])) {
         if ($user->getPhone() != $user->getName()) {//已经修改过用户名称
            unset($params['name']);
         }
      }
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProvider', array($user->getId(), $params));
   }

   /**
    * 用户中信修改密码
    * 
    * @param array $params
    * @return boolean
    */
   public function changePassword($params)
   {
      $this->checkRequireFields($params, array('oldPwd', 'newPwd'));
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'resetPassword', array($params['oldPwd'], $params['newPwd']));
   }

   /**
    * 开通店铺
    * 
    * @param array $params
    */
   public function openSite($params)
   {
      $this->checkRequireFields($params, array('subAttr'));
      $provider = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      //判断企业信息是否存在
      $company = $provider->getCompany();
      if (!$company) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')
         ));
      }

      //判断二级域名是否合法
      $subAttr = $params['subAttr'];
      if ($this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'checkSubAttr', array($subAttr))) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_SUBATTR_EXIST'), $errorType->code('E_SUBATTR_EXIST')
         ));
      }
      
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'createSite', array($company->getId(), $subAttr));
   }

   /**
    * 安全退出
    * 
    * @return boolean
    */
   public function logout()
   {
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'logout');
   }
}