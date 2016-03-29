<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderMobileApi;
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
      $this->checkRequireFields($params, array('phone', 'pswd'));
      if (!isset($params['code'])) {
         $params['code'] = null;
      }
      if (!isset($params['type'])) {
         $params['type'] = 2;
      }

      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'login', array($params['phone'], $params['pswd'], $params['type'], $params['code']));
   }

   /**
    * 注册用户
    * 
    * @param array $params
    * @return boolean
    */
   public function register($params)
   {
      $this->checkRequireFields($params, array('phone', 'pswd', 'code', 'name'));
      if (!isset($params['name'])) {
         $params['name'] = $params['phone'];
      }
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'register', array($params['phone'], $params['name'], $params['pswd'], $params['code']));
   }

   /**
    * 发送注册验证短信
    * @param array $params
    * @param int $type
    */
   public function sendCode($params)
   {
      $this->checkRequireFields($params, array('phone', 'type'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      //type等于1时,为注册,所有需检测是否存在,若存在则返回错误信息
      if ($params['type'] == 1) {
         $acl->checkPhoneExist($params['phone']);
      }
      $acl->sendSmsCode($params['phone'], $params['type']);
   }

   /**
    * 修改showphone
    * @param type $params
    */
   public function modifyShowPhone($params)
   {
      $this->checkRequireFields($params, array('showPhone', 'code'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      if ($acl->checkSmsCode($params['showPhone'], $params['code'], 3)) {
         return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProvider', array($user->getId(), array('showPhone' => $params['showPhone'])));
      }
   }

   /**
    * 检测密码
    * @param type $params
    * @return type
    */
   public function checkPswd($params)
   {
      $this->checkRequireFields($params, array('pswd'));
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'checkPassword', array($params['pswd']));
   }

   /**
    * 修改注册手机号
    * @param type $params
    */
   public function changePhone($params)
   {
      $this->checkRequireFields($params, array('phone'));
      $acl = $this->appCaller->getAppObject(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR);
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      if ($acl->checkSmsCode($params['phone'], $params['code'], 4)) {
         return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProvider', array($user->getId(), array('phone' => $params['phone'])));
      }
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
   public function checkPhone($params)
   {
      $this->checkRequireFields($params, array('phone'));
      $exist = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'providerPhoneExist', array($params['phone']));
      return array('exist' => $exist);
   }

   /**
    * 检查手机号是否注册
    * @param type $params
    * @return type
    */
   public function checkName($params)
   {
      $this->checkRequireFields($params, array('name'));
      $exit = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'providerNameExist', array($params['name']));
      return array('exist' => $exit);
   }

   /**
    * 获得企业logo,name
    */
   public function getCompanyNameAndLogo()
   {
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      if ($company) {
         return array(
            'name' => $company->getName().','.$user->getName(),
            'logo' => \Cntysoft\Kernel\get_image_cdn_url($company->getLogo()),
            'subAttr' => $company->geySubAttr() ? $company->geySubAttr() : ''
         );
      }else{
         return array(
            'name' => '未填写'.$user->getName(),
            'logo' => '',
            'subAttr' => ''
         );
      } else {
         return array(
            'name' => '未填写,' . $user->getName(),
            'logo' => ''
         );
      }
   }

   /**
    * 返回供应商信息
    * @return type
    */
   public function getProviderInfo()
   {
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $userProfile = $user->getProfile();
      $company = $user->getCompany();
      return array(
         'id'          => $user->getId(),
         'name'        => $user->getName() ? $user->getName() : '',
         "phone"       => $user->getPhone(),
         "realName"    => $userProfile->getRealName() ? $userProfile->getRealName() : '',
         "sex"         => $userProfile->getSex() ? $userProfile->getSex() : 3,
         "position"    => $userProfile->getPosition() ? $userProfile->getPosition() : '',
         "companyName" => $company ? $company->getName() : '',
         "department"  => $userProfile->getDepartment() ? $userProfile->getDepartment() : '',
         "email"       => $userProfile->getEmail() ? $userProfile->getEmail() : '',
         "showPhone"   => $userProfile->getShowPhone() ? $userProfile->getShowPhone() : '',
         "qq"          => $userProfile->getQq() ? $userProfile->getQq() : '',
         "tel"         => $userProfile->getTel() ? $userProfile->getTel() : '',
         "fax"         => $userProfile->getFax() ? $userProfile->getFax() : ''
      );
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
      unset($params['showPhone']);
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
   public function modifyPswd($params)
   {
      $this->checkRequireFields($params, array('oldPwd', 'newPwd'));
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'resetPassword', array($params['oldPwd'], $params['newPwd']));
   }

}
