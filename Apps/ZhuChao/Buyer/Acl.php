<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel\ConfigProxy;
use Cntysoft\Kernel;
use Cntysoft\Framework\Net\Sms\YunPian;
use Cntysoft\Stdlib\Filesystem;

class Acl extends AbstractLib
{
   /**
    * @var \Zend\Session\Container $sessionManager
    */
   protected $sessionManager = null;
   /**
    * @var \Cntysoft\Kernel\CookieManager $cookieManager
    */
   protected $cookieManager = null;
   /**
    * 当前登录用户的缓存
    * 
    * @var \App\Shop\User\Model\UserBaseInfo
    */
   protected $curUser = null;
   /**
    * Cookie 键值
    * 
    * @var array
    */
   protected $cookieKeys = null;
   /**
    * 当前Cookie值的缓存
    * 
    * @var string
    */
   protected $curCookieData = null;

   /**
    * 构造函数, 初始化CookieManager和SessionManager
    */
   public function __construct()
   {
      parent::__construct();
      $this->sessionManager = $this->di->get('SessionManager');
      $this->cookieManager = $this->di->get('CookieManager');
   }
   
   /**
    * 采购商注册
    * 
    * @param string $phone
    * @param string $password
    * @param integer $smsCode
    */
   public function register($phone, $password, $smsCode)
   {
      $this->checkSmsCode($phone, $smsCode, Constant::SMS_TYPE_REG);
      $userInfo = array(
         'password'   => $password,
         'name'       => '',
         'phone'      => $phone
      );

      return $this->getAppCaller()->call(
         Constant::MODULE_NAME, 
         Constant::APP_NAME, 
         Constant::APP_API_BUYER_MGR, 
         'addBuyer', 
         array($userInfo)
      );
   }
   
   /**
    * 获取短信验证码存的sessionkey值
    * 
    * @param integer $type
    * @return string
    */
   public function getSmsSessionKey($type)
   {
      switch ($type) {
         case Constant::SMS_TYPE_REG:
            return \Cntysoft\FRONT_USER_S_KEY_REG_MSG_CODE;
         case Constant::SMS_TYPE_FORGET:
            return \Cntysoft\FRONT_USER_S_KEY_FORGET_MSG_CODE;
         default:
            return \Cntysoft\FRONT_USER_S_KEY_REG_MSG_CODE;
      }
   }

   /**
    * 获取图片验证码的sessionKey值
    * 
    * @param integer $type
    * @return string
    */
   public function getPicCodeSessionKey($type)
   {
      switch ($type) {
         case Constant::PIC_CODE_TYPE_REG:
            return \Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE;
         case Constant::PIC_CODE_TYPE_FORGET:
            return \Cntysoft\FRONT_USER_S_KEY_FORGET_CHK_CODE;
         case Constant::PIC_CODE_TYPE_LOGIN:
            return \Cntysoft\FRONT_USER_S_KEY_LOGIN_CHK_CODE;
         case Constant::PIC_CODE_TYPE_SITEMANAGER:
            return \Cntysoft\SITEMANAGER_S_KEY_CHK_CODE;
         default:
            return \Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE;
      }
   }
   
   /**
    * 验证图片验证码是否正确
    * 
    * @param string $code 图片验证码的内容，不区分大小写
    * @param integer $type 图片验证码使用类型
    * @return boolean
    */
   public function checkPicCode($code, $type = Constant::PIC_CODE_TYPE_REG)
   {
      $key = $this->getPicCodeSessionKey($type);
      $chkCode = $this->sessionManager->offsetGet($key);
      if (!$chkCode) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_BUYER_ACL_PIC_CODE_EXPIRE', $code), $errorType->code('E_BUYER_ACL_PIC_CODE_EXPIRE')));
      } elseif (strtoupper($code) !== $chkCode) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_BUYER_ACL_PIC_CODE_ERROR', $code), $errorType->code('E_BUYER_ACL_PIC_CODE_ERROR')));
      }

      //删除Session
      $this->sessionManager->offsetUnset($key);

      return true;
   }

   /**
    * 向指定的手机号发送短信验证码
    * 
    * @param string $phone 要发短信验证码的手机号
    * @param integer $type 短信验证码使用的类型
    */
   public function sendSmsCode($phone, $type = Constant::SMS_TYPE_REG)
   {
      if(!in_array($type, array(
         Constant::SMS_TYPE_REG,
         Constant::SMS_TYPE_FORGET
      ))){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ACL_SMS_TYPE_ERROR', $type), $errorType->code('E_BUYER_ACL_SMS_TYPE_ERROR') 
         ), $this->getErrorTypeContext());
      }
      $code = $this->getVerifyCode(6);
      //首先发送短信验证码
      $tplValue = '#code#=' . $code;
      $netConfig = ConfigProxy::getFrameworkConfig('Net');
      $yunPian = new YunPian();
      $ret = $yunPian->tplSendSms($netConfig->yunpian->tplid, $tplValue, array($phone));
      if (0 != $ret['code']) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ACL_SMS_SEND_ERROR'), $errorType->code('E_BUYER_ACL_SMS_SEND_ERROR')
         ), $this->getErrorTypeContext());
      }

      //发送成功, 保存Session, 默认保存时间为2分钟
      $value = $phone . '|' . $code;
      $life = 2 * 60;
      $sessionKey = $this->getSmsSessionKey($type);
      $this->sessionManager->setExpirationSeconds($life, array(
         $sessionKey
      ));
      $this->sessionManager->offsetSet($sessionKey, $value);
   }

   /**
    * 验证手机号的短信验证码是否正确
    * 
    * @param string $phone 手机号
    * @param integer $code 验证码的内容
    * @param integer $type 验证码使用的类型
    * @return boolean
    */
   public function checkSmsCode($phone, $code, $type = Constant::SMS_TYPE_REG)
   {
      $sessionKey = $this->getSmsSessionKey($type);
      $token = $this->sessionManager->offsetGet($sessionKey);
      if (!$token) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_BUYER_ACL_SMS_EXPIRE', $code), $errorType->code('E_BUYER_ACL_SMS_EXPIRE')), $this->getErrorTypeContext());
      }
      $token = explode('|', $token);

      if ($token[0] != $phone || $token[1] != $code) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_BUYER_ACL_SMS_ERROR', $code), $errorType->code('E_BUYER_ACL_SMS_ERROR')), $this->getErrorTypeContext());
      }

      $this->sessionManager->offsetUnset($sessionKey);

      return true;
   }

   /**
    * 根据登录类型获取用户登录的方法
    * 
    * @param int $type
    * @return string
    */
   protected function getLoginMethod($type)
   {
      //首先判断登录方式
      if (!in_array($type, array(Constant::FRONT_USER_NAME_LOGIN, Constant::FRONT_USER_PHONE_LOGIN))) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_BUYER_ACL_LOGIN_TYPE_ERROR'), $errorType->code('E_BUYER_ACL_LOGIN_TYPE_ERROR')
         ), $this->getErrorTypeContext());
      }

      $method = '';
      switch ($type) {
         case Constant::FRONT_USER_PHONE_LOGIN: //手机号码登录
            $method = 'getUserByPhone';
            break;
         case Constant::FRONT_USER_NAME_LOGIN: //用户名登录
            $method = 'getUserByName';
            break;
      }

      return $method;
   }
   
   /**
    * 获取cookies键值
    *
    * @return array
    */
   public function getCookieKeys()
   {
      if (null == $this->cookieKeys) {
         $config = ConfigProxy::getFrameworkConfig('Security');
         if (isset($config->cookieKeys)) {
            $this->cookieKeys = $config->cookieKeys->buyer->toArray();
         } else {
            $this->cookieKeys = array();
         }
         $this->cookieKeys += $this->getDefaultKeys();
      }
      return $this->cookieKeys;
   }
   
   /**
    * 默认的COOKIE键
    *
    * @return array
    */
   protected function getDefaultKeys()
   {
      return array(
         Constant::AUTH_KEY   => 'Frer$#er545',
         Constant::STATUS_KEY => '#$ERSDFerd$$5'
      );
   }
   
   /**
    * 生成登录的token
    * 默认的token信息分成三个部分:  用户的识别码(用户名, 手机号, 邮箱), 与用户识别码对应的登录方式, 系统生成的机器特征码
    * 
    * @param string $key
    * @param int $type
    * @return string
    */
   protected function getLoginToken($key, $type)
   {
      $token = array(
         $key,
         $type,
         md5($key . Kernel\get_server_env('HTTP_USER_AGENT')),
         ''//最后一个不知道为什么是乱码
      );
      return implode('|', $token);
   }
   
   /**
    * 用户登录, 主要是设置Session和Cookie
    * 登录的时候没有设置验证码
    * 
    * @param string $key 手机号或者用户名
    * @param string $password
    * @param boolean $type
    * @param integer $remember
    */
   public function login($key, $password, $type = Constant::FRONT_USER_PHONE_LOGIN, $remember = false)
   {
      $method = $this->getLoginMethod($type);
      //获取用户信息
      $user = $this->getAppCaller()->call(
         Constant::MODULE_NAME, 
         Constant::APP_NAME, 
         Constant::APP_API_BUYER_MGR, 
         $method, 
         array($key)
      );
      if (!$user) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $key), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      if (Constant::USER_STATUS_LOCK == $user->getStatus()) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_BUYER_ACL_USER_LOCKED'), $errorType->code('E_BUYER_ACL_USER_LOCKED')
         ), $this->getErrorTypeContext());
      }
      try {
         $pwdHasher = $this->di->getShared('security');
         $targetPwd = $user->getPassword();
         if (!$pwdHasher->checkHash($password, $targetPwd)) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
                    $errorType->msg('E_BUYER_ACL_PASSWORD_ERROR'), $errorType->code('E_BUYER_ACL_PASSWORD_ERROR')
            ), $this->getErrorTypeContext());
         }

         //记录相关登录时间和IP
         $user->setLastLoginTime(time());
         $user->setLastLoginIp(Kernel\get_client_ip());
         $user->save();
         //登录成功, 设置Cookie和Session
         //@TODO这个过程合理吗？
         $keys = $this->getCookieKeys();
         $authKey = $keys[Constant::AUTH_KEY];
         $token = $this->getLoginToken($key, $type);
         $cookieLife = null;
         if ($remember) {//记住密码, 默认保存一周
            $cookieLife = 7 * 24 * 60 * 60;
         }
         $this->cookieManager->setCookie($authKey, $token, $cookieLife, \Cntysoft\RT_SYS_DOMAIN);
         $this->sessionManager->offsetSet(\Cntysoft\FRONT_USER_BUYER_S_KEY_INFO, $key . '|' . $type);
         return true;
      } catch (\Exception $ex) {
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 获取当前登录的用户信息
    * 
    * @return \App\Shop\User\Model\BaseInfo
    */
   public function getCurUser()
   {
      if (null == $this->curUser) {
         $data = $this->getCurCookieData();
         if (empty($data)) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_BUYER_ACL_COOKIE_NOT_EXIST'), $errorType->code('E_BUYER_ACL_COOKIE_NOT_EXIST')
            ), $this->getErrorTypeContext());
         }
         $data = explode('|', $data);
         $key = $data[0];
         $type = $data[1];
         $method = $this->getLoginMethod($type);
         $this->curUser = $this->getAppCaller()->call(
            Constant::MODULE_NAME, 
            Constant::APP_NAME, 
            Constant::APP_API_BUYER_MGR, 
            $method, 
            array($key)
         );
      }

      return $this->curUser;
   }

   /**
    * 获取当前Cookie中的值
    * 
    * @return string
    */
   protected function getCurCookieData()
   {
      if (null == $this->curCookieData) {
         $keys = $this->getCookieKeys();
         $key = $keys[Constant::AUTH_KEY];
         $this->curCookieData = $this->cookieManager->getCookie($key);
      }

      return $this->curCookieData;
   }
   
   /**
    * 通过Cookie登录
    * 
    * @return boolean
    */
   public function loginByCookie()
   {
      $keys = $this->getCookieKeys();
      $authKey = $keys[Constant::AUTH_KEY];
      $token = $this->cookieManager->getCookie($authKey);
      try {
         //首先验证Cookie是否存在
         if (empty($token)) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_BUYER_ACL_COOKIE_NOT_EXIST'), $errorType->code('E_BUYER_ACL_COOKIE_NOT_EXIST')
               ), $this->getErrorTypeContext());
         }
         //验证Cookie中保存的值是否正确
         $clientToken = Kernel\get_trait_token();
         $token = explode('|', $token);
         if ($clientToken !== $token[2]) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_BUYER_ACL_COOKIE_ERROR'), $errorType->code('E_BUYER_ACL_COOKIE_ERROR')
            ), $this->getErrorTypeContext());
         }
         //获取当前等用用户的信息
         $user = $this->getCurUser();
         //验证用户的状态
         if ($user && Constant::USER_STATUS_LOCK == $user->getStatus()) {
            $this->logout();
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_BUYER_ACL_USER_LOCKED'), $errorType->code('E_BUYER_ACL_USER_LOCKED')
               ), $this->getErrorTypeContext()
            );
         } else if (!$user) {
            $this->logout();
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_BUYER_USER_NOT_EXIST', $token[1]), $errorType->code('E_BUYER_USER_NOT_EXIST')
               ), $this->getErrorTypeContext()
            );
         }
         //登录成功
         //记录相关登录时间和IP
         $user->setLastLoginTime(time());
         $user->setLastLoginIp(Kernel\get_client_ip());
         return $user->save();
      } catch (\Exception $ex) {
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 通过手机号找回密码
    * 
    * @param string $phone
    * @param string $password
    * @param string $code
    * @return boolean
    */
   public function findPassword($phone, $password, $code = '')
   {
      if($code){
         $this->checkSmsCode($phone, $code, Constant::SMS_TYPE_FORGET);
      }
      $user = $user = $this->getAppCaller()->call(
         Constant::MODULE_NAME, 
         Constant::APP_NAME, 
         Constant::APP_API_BUYER_MGR, 
         'getUserByPhone', 
         array($phone)
      );
      
      if(!$user){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $phone), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $hasher = $this->di->getShared('security');
      $password = $hasher->hash($password);
      $user->setPassword($password);
      $user->setLastModifyPwdTime(time());
      return $user->save();
   }
   

   /**
    * 修改用户的密码
    * 
    * @param string $oldPassword
    * @param string $newPassword
    * @return boolean
    */
   public function resetPassword($oldPassword, $newPassword)
   {
      $user = $this->getCurUser();
      $hasher = $this->di->getShared('security');
      $curPassword = $user->getPassword();
      //首先验证输入的原密码是否正确
      if (!$hasher->checkHash($oldPassword, $curPassword)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ACL_PASSWORD_ERROR'), $errorType->code('E_BUYER_ACL_PASSWORD_ERROR')
            ), $this->getErrorTypeContext());
      }

      //将新密码写入数据库
      $password = $hasher->hash($newPassword);
      $user->setPassword($password);
      $user->setLastModifyPwdTime(time());
      return $user->save();
   }

   /**
    *  保存用户上传的图像到OSS <p>
    *  用户保存到OSS的图像路径为: <i> App/Shop/User/Avatar/{uid}.png</i>
    *  在这里<b>不生成文件索引</b>, 每个用户的头像文件名直接是用户名称, 上传新的图片直接覆盖
    * </p>
    * @param string $type
    * @param string $content
    * @return array
    * @throws \Exception
    */
   public function saveUserImage($type, $content)
   {
      $curUser = $this->getCurUser();
      $oldAvatar = $curUser->getAvatar();
      $uid = $curUser->getId();

      $ret;
      //创建文件夹
      $targetDir = CNTY_UPLOAD_DIR . DS . 'Apps' . DS . 'ZhuChao' . DS . 'Buyer' . DS . 'Avatar';
      if (!file_exists($targetDir)) {
         Filesystem::createDir($targetDir, 0755, true);
      }
      $filename = $targetDir . DS . uniqid() . '.' . $type;

      //写入文件
      $out = Filesystem::fopen($filename, 'wb');
      fwrite($out, base64_decode($content));
      Filesystem::fclose($out);

      $objectName = str_replace(CNTY_ROOT_DIR, '', $filename);
//      $ossClient = $this->di->get('OssClient');
//      $bucketName = Kernel\get_image_oss_bucket_name();

      try {
         //上传到OSS
//         $retry = 3;
//         $isOk = false;
//         while ($retry-- > 0) {
//            $response = $ossClient->uploadFileByFile($bucketName, $objectName, $filename);
//            if ($ossClient->responseIsOk($response)) {
//               $isOk = true;
//               break;
//            }
//         }
//         if (!$isOk) {
//            $errorType = $this->getErrorType();
//            Kernel\throw_exception(new Exception($errorType->msg('E_SAVE_AVATAR_OSS_ERROR'), $errorType->code('E_SAVE_AVATAR_OSS_ERROR')));
//         }
         //删除原来存在的图片
         if (!empty($oldAvatar)) {
            $isDelete = false;
//            $response = $ossClient->deleteObject($bucketName, $oldAvatar);
//            if ($ossClient->responseIsOk($response)) {
            if (Filesystem::deleteFile(CNTY_ROOT_DIR . $oldAvatar)) {
               $isDelete = true;
            }

            if (!$isDelete) {
               $errorType = $this->getErrorType();
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_BUYER_DELETE_AVATAR_ERROR'), $errorType->code('E_BUYER_DELETE_AVATAR_ERROR')
                  ), $this->getErrorTypeContext());
            }
         }


//         $ret = array(
//            'filename' => Kernel\get_image_cdn_url($objectName)
//         );
//         Filesystem::deleteFile($filename);
         //修改数据库
         $curUser->setAvatar($objectName);
         $curUser->save();
         return $ret;
      } catch (\Exception $e) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ACL_SAVE_AVATAR_ERROR'), $errorType->code('E_BUYER_ACL_SAVE_AVATAR_ERROR')
            ), $this->getErrorTypeContext());
      }
   }

   /**
    * 用户注销登录, 删除保存的Cookie和Session
    * 
    * @return void
    */
   public function logout()
   {
      $keys = $this->getCookieKeys();
      $key = $keys[Constant::AUTH_KEY];
      $this->cookieManager->deleteCookie($key, \Cntysoft\SYS_DOMAIN_DEVEL);
      $this->sessionManager->offsetUnset(\Cntysoft\FRONT_USER_BUYER_S_KEY_INFO);
   }

   /**
    * 验证是否是普通用户登录
    * <p>这个方法不抛出异常, 直接返回布尔值</p>
    * @return boolean
    */
   public function isLogin()
   {
      $data = $this->getCurCookieData();
      if (empty($data)) {
         return false;
      }
      //获取当前登录的用户
      $user = $this->getCurUser();
      if (!$user) {
         return false;
      }

      return true;
   }

   /**
    * 随机生成指定长度的数字验证码
    * 
    * @param int $len
    * @return string
    */
   protected function getVerifyCode($len)
   {
      $ret = '';
      for ($i = 0; $i < $len; $i++) {
         $ret .= rand(0, 9);
      }

      return $ret;
   }

}
