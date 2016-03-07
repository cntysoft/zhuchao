<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\UserCenter\Constant;
use Cntysoft\Kernel;
use KeleShop\Framework\Net\Upload;
use Zend\Filter\StripTags;
use App\Shop\ShopFlow\Constant as SHOP_CONST;
use App\Shop\MarketMgr\Constant as MAR_CONST;
use App\Shop\GoodsMgr\Constant as GOOD_CONST;
use App\Site\Category\Constant as CategoryConst;
use App\Site\Content\Constant as ContentConst;
use Cntysoft\Kernel\ConfigProxy;
/**
 * 主要是处理前端用户中心通用的Ajax调用
 * 
 * @package FrontApi
 */
class User extends AbstractScript
{
   /**
    * 用户登录
    * 
    * @param array $params <p>
    * 参数为数组, 其中必须包含键值为:
    * <i>'key'</i>: 登录的用户名或者邮箱或者手机号码
    * <i>'password'</i>: 登录的密码
    * <i>'type'</i>: 登录的类型, 用于区分用户名登录, 邮箱登录或者手机号码登录
    * <i>'remember'</i>: 是否记住密码
    * </p>
    */
   public function login($params)
   {
      $this->checkRequireFields($params, array('key', 'password', 'type'));
      $acl = $this->di->get('FrontUserAcl');
      return $acl->login($params['key'], $params['password'], false, $params['type']);
   }

   /**
    * 用户注册
    * 
    * @param array $params <p>
    * 参数为数组, 必须包含的键值为:
    * <i>'key'</i>: 注册的用户名或者邮箱
    * <i>'password'</i>: 注册用户的密码
    * <i>'name'</i>: 注册的用户名
    * <i>'checkcode'</i>: 短信验证码或者邮件验证码
    * <i>'type'</i>: 用户的类型
    * </p>
    */
   public function register($params)
   {
      $this->checkRequireFields($params, array('key', 'password'));
      $acl = $this->di->get('FrontUserAcl');
      if (key_exists('phone', $params)) {
         $this->checkRequireFields($params, array('checkcode'));
         return $acl->registerbyPhone($params['key'], $params['password'], $params['key'], $params['checkcode']);
      }
      return $acl->register($params['key'], $params['password'], $params['key']);
   }

   /**
    * 注册检验短信验证码是否正确
    * 
    * @param array $params <p>
    * 参数为数组, 必须包含的键值为:
    * <i>'phone'</i>: 短信验证码或者邮件验证码
    * <i>'checkcode'</i>: 短信验证码或者邮件验证码
    * </p>
    */
   public function checkPhoneCode($params)
   {
      $this->checkRequireFields($params, array('phone', 'checkcode'));
      $acl = $this->di->get('FrontUserAcl');
      return $acl->checkSmsCode(Constant::SMS_TYPE_REG, $params['phone'], $params['checkcode']);
   }

   /**
    * 找回密码检验短信验证码是否正确
    * 
    * @param array $params <p>
    * 参数为数组, 必须包含的键值为:
    * <i>'phone'</i>: 短信验证码或者邮件验证码
    * <i>'checkcode'</i>: 短信验证码或者邮件验证码
    * </p>
    */
   public function checkPhoneCodeForPassword($params)
   {
      $this->checkRequireFields($params, array('phone', 'checkcode'));
      $acl = $this->di->get('FrontUserAcl');
      return $acl->checkSmsCode(Constant::SMS_TYPE_FORGET, $params['phone'], $params['checkcode']);
   }

   /**
    * 用户注销
    * 
    * @return boolean
    */
   public function logout()
   {
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'logout', array()
      );
   }

   public function getUserInfo()
   {
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $user = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER_MANAGER, 'getUser', array($user->getId())
      );
      return $this->getImgcdn($user->getAvatar(), 60, 60);
   }

   /**
    * 注册的时候, 发送短信或者邮件验证码之前, 需要验证图片验证码
    * 
    * @param array $params <p>
    * 参数为数组, 必须包含键值:
    * <i>code</i>: 前台的验证码
    * <i>key</i>: 用户输入的邮箱或者手机号
    * <i>type</i>: 辨别邮箱或者手机号码, 1 为手机号, 2 为邮箱 </p>
    * </p>
    */
   public function checkRegAuthCode($params)
   {
      $this->checkRequireFields($params, array('code', 'key', 'type'));
      $code = $params['code'];
      $sessionManager = $this->di->getShared('SessionManager');
      $oldCode = $sessionManager->offsetGet(\Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE);

      if (!$oldCode) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_AUTH_CODE_EXPIRE'), $errorType->code('E_FRONT_AUTH_CODE_EXPIRE')));
      } elseif (strtoupper($code) !== $oldCode) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_AUTH_CODE_ERROR'), $errorType->code('E_FRONT_AUTH_CODE_ERROR')));
      }

      //删除Session
      $sessionManager->offsetUnset(\Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE);

      //发送短信或者邮件验证码
      $type = (int) $params['type'];
      if (Constant::FRONT_USER_PHONE_LOGIN == $type) {
         return $this->appCaller->call(
                         Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'sendSmsCode', array(Constant::SMS_TYPE_REG, $params['key']));
      } elseif (Constant::FRONT_USER_EMAIL_LOGIN == $type) {
         return $this->appCaller->call(
                         Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'sendEmailCode', array($params['key']));
      }
   }

   /**
    * 前台忘记密码验证码验证
    * 
    * @param array $params <p>
    * 参数为数组, 必须包含键值:
    * <i>code</i>: 前台的验证码
    * <i>key</i>: 用户输入的邮箱或者手机号
    * <i>type</i>: 辨别邮箱或者手机号码, 1 为手机号, 2 为邮箱 </p>
    * @return boolean
    */
   public function checkForgetAuthCode($params)
   {
      $this->checkRequireFields($params, array('code', 'key', 'type'));
      $code = $params['code'];
      $sessionManager = $this->di->getShared('SessionManager');
      $oldCode = $sessionManager->offsetGet(\Cntysoft\FRONT_USER_S_KEY_FORGET_CHK_CODE);

      if (!$oldCode) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_AUTH_CODE_EXPIRE'), $errorType->code('E_FRONT_AUTH_CODE_EXPIRE')));
      } elseif (strtoupper($code) !== $oldCode) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_AUTH_CODE_ERROR'), $errorType->code('E_FRONT_AUTH_CODE_ERROR')));
      }

      //删除Session
      $sessionManager->offsetUnset(\Cntysoft\FRONT_USER_S_KEY_FORGET_CHK_CODE);
   }

   /**
    * 找回密码发送短信验证
    * 
    * @param type $params
    */
   public function sendCheckMsg($params)
   {
      $this->checkRequireFields($params, array('key', 'type'));
      $type = (int) $params['type'];
      if (Constant::FRONT_USER_PHONE_LOGIN == $type) {
         return $this->appCaller->call(
                         Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'sendSmsCode', array(Constant::SMS_TYPE_FORGET, $params['key']));
      } elseif (Constant::FRONT_USER_EMAIL_LOGIN == $type) {
         return $this->appCaller->call(
                         Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'sendEmailCode', array(Constant::SMS_TYPE_FORGET, $params['key']));
      }
   }

   /**
    * 检查邮箱是否已经使用
    * 
    * @param array $params
    * @return boolean
    */
   public function checkEmailExist($params)
   {
      $this->checkRequireFields($params, array('email'));
      $user = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER_MANAGER, 'getUserByEmail', array($params['email']));

      if (!empty($user)) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_EXIST'), $errorType->code('E_USER_EXIST')));
      }

      return true;
   }

   /**
    * 检查手机号码是否已经使用
    * 
    * @param array $params
    * @return boolean
    */
   public function checkPhoneExist($params)
   {
      $this->checkRequireFields($params, array('phone'));
      $user = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER, 'CheckUserByPhone', array($params['phone']));
      if (!empty($user)) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_EXIST'), $errorType->code('E_USER_EXIST')));
      }

      return true;
   }

   /**
    * 检查用户名是否已经使用
    * 
    * @param array $params
    * @return boolean
    */
   public function checkNameExist($params)
   {
      $this->checkRequireFields($params, array('name'));
      $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER, 'getUserByName', array($params['name']));
      return true;
   }

   public function checkLogin()
   {
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $uid = $user->getId();
      $count = $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_SHOPCART, 'getGoodsNum', array($uid)
      );
      $favorites = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_FAVORITES, 'getFavoritesListByCond', array(array('uid' => $uid, 'type' => Constant::FAVORITES_TYPE_GOODS), true)
      );
      $nickName = $user->getNickName();
      $level = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER, 'getUserGrade', array($uid)
      );
      $msg = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_MSG, 'getMessageList', array(array('uid=' . $uid . ' and status=1'), true)
      );
      $orders = $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersNumByCond', array(
         array('uid=?0 and deleted=?1',
            'bind'  => array($user->getId(), SHOP_CONST::ORDER_DELETED_FALSE),
            'group' => 'status'
         )
      ));
      $finishorders = $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersBy', array(
         array('uid=?0 and status=?1',
            'bind' => array($user->getId(), SHOP_CONST::ORDER_STATUS_FINISHED)
         ), false, null, 0, 0
      ));
      $ret['receive'] = $ret['evaluate'] = $ret['unpay'] = $ret['favgoods'] = 0;
      $finisheorder = array();
      foreach ($orders as $order) {
         if (SHOP_CONST::ORDER_STATUS_TRANSPORT == $order['status']) {
            $ret['receive'] = $order['rowcount'];
         } else if (SHOP_CONST::ORDER_STATUS_UNPAY == $order['status']) {
            $ret['unpay'] = $order['rowcount'];
         }
      }
      foreach ($finishorders as $value) {
         $finisheorder[] = $value->getId();
      }
      $ret['return'] = empty($finisheorder) ? 0 : $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_SERCIE, 'getServiceNumByCond', array(
                 array('status!=?0 and uid=?1', 'bind' => array(SHOP_CONST::ORDER_SERVICE_COMPLETED, $uid))
      ));
      $ret['evaluate'] = empty($finisheorder) ? 0 : $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersGoodsNumByCond', array(
                 array('status=?0 and orderId in (' . implode(',', $finisheorder) . ')', 'bind' => array(0))
      ));
      return array(
         'id'          => $uid,
         'name'        => $nickName ? $nickName : $user->getName(),
         'count'       => $count,
         'favoriteNum' => $favorites[1],
         'level'       => $level['grade'],
         'avatar'      => $user->getAvatar() ? $user->getAvatar() : '/Statics/Skins/Images/pc/usercenter/upload_head.png',
         'msg'         => $msg[1],
         'return'      => $ret['return'],
         'receive'     => $ret['receive'],
         'evaluate'    => $ret['evaluate']
      );
   }

   /**
    * 通过找回密码的方式重置密码, 这个方法直接将验证码和重置密码放在一块, 用户体验不是很好
    * 
    * @param array $params <p>
    * 参数是数组, 必须包含以下键值:
    * <i>code</i>: 短信验证码
    * <i>password</i>: 用户输入的重置密码
    * <i>key</i>: 用户的识别码, 用户手机号或者邮箱
    * </p>
    */
   public function resetPasswordWithCode($params)
   {
      $this->checkRequireFields($params, array('code', 'password', 'key'));
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'resetPasswordWithCode', array($params['password'], $params['key'], $params['code']));
   }

   /**
    * 通过找回密码的方式重置密码, 这个方法直接将验证码和重置密码放在一块, 用户体验不是很好
    * (分两步已校验过短信验证码)
    * 
    * @param array $params <p>
    * 参数是数组, 必须包含以下键值:
    * <i>password</i>: 用户输入的重置密码
    * <i>key</i>: 用户的识别码, 用户手机号或者邮箱
    * </p>
    */
   public function resetPasswordWithoutCode($params)
   {
      $this->checkRequireFields($params, array('password', 'key'));
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'resetPasswordWithoutCode', array($params['password'], $params['key']));
   }

   /**
    * 修改用户密码
    * 
    * @param array $params <p>
    * 用户修改密码, 这个接口需要用户登录之后才能调用, 主要的参数有两个:
    * <i>oldPassword</i>: 用户的原始密码
    * <i>newPasswrod</i>:  用户的新密码
    * </p>
    * @return boolean
    */
   public function resetPassword($params)
   {
      $this->checkRequireFields($params, array('oldPassword', 'newPassword'));
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'resetPassword', array($params['oldPassword'], $params['newPassword']));
   }

   /**
    * 更新用户信息
    * 
    * @param array $params
    * @return boolean
    */
   public function update($params)
   {
      //首先去掉一些敏感数据
      $unsetKeys = array('id', 'type', 'password', 'status', 'detailId', 'phone', 'experience');

      foreach ($params as $key => $value) {
         if (in_array($key, $unsetKeys)) {
            unset($params[$key]);
         }
      }

      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());

      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER, 'updateUser', array($curUser->getId(), $params));
   }

   /**
    * 添加收货地址信息
    * 
    * @param array $params
    * @return boolean
    */
   public function addOrderAddress($params)
   {
      $this->checkRequireFields($params, array('phone', 'name', 'province', 'city', 'district', 'address', 'isDefault'));
      $filter = new StripTags();
      $params['address'] = $filter->filter($params['address']);
      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());
      if ($params['isDefault']) {
         $this->setAddressDefaultFalse($curUser->getId());
      }
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'addAddress', array($curUser->getId(), $params));
   }

   /**
    * 修改收货地址信息
    * 
    * @param array $params
    * @return boolean
    */
   public function updateOrderAddress($params)
   {
      $this->checkRequireFields($params, array('id', 'phone', 'name', 'province', 'city', 'district', 'address', 'isDefault'));
      $filter = new StripTags();
      $params['address'] = $filter->filter($params['address']);
      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'updateAddress', array($params['id'], $curUser->getId(), $params));
   }

   /**
    * 设为默认收货地址
    * 
    * @param array $params
    * @return boolean
    */
   public function setOrderAddressDefault($params)
   {
      $this->checkRequireFields($params, array('id'));

      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());
      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'updateAddress', array($params['id'], $curUser->getId(), array('isDefault' => 1)));
   }

   /**
    * 重置默认地址
    * 
    * @param integer $uid
    */
   public function setAddressDefaultFalse($uid)
   {
      $address = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'getDefaultAddressByUser', array($uid));
      if ($address) {
         $address->setIsDefault(0);
         $this->appCaller->call(
                 Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'updateAddress', array($address->getId(), $address->getUid(), $address->toArray()));
      }
   }

   /**
    * 删除收货地址信息
    * 
    * @param array $params
    * @return boolean
    */
   public function deleteOrderAddress($params)
   {
      $this->checkRequireFields($params, array('id'));
      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());

      return $this->appCaller->call(
                      Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ADDRESS, 'deleteAddress', array($params['id'], $curUser->getId()));
   }

   /**
    * 删除收货地址信息
    * 
    * @param array $params
    * @return boolean
    */
   public function getMsgText($params)
   {
      $this->checkRequireFields($params, array('msgId'));
      $curUser = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'getCurUser', array());

      $msg = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_MSG, 'getMessageById', array($curUser->getId(), $params['msgId']));
      if ($msg) {
         $ret = $msg->text->toArray();
         $ret['sendTime'] = date('Y-m-d', $ret['sendTime']);
         return $ret;
      } else {
         return false;
      }
   }

   /**
    * 
    * @param array $params <p>
    * 这里的参数当中必须包含键值为<i>file</i>的数值, 并且他的值是 base64 编码的图片信息
    * </p>
    * @return array
    */
   public function uploadUserImg($params)
   {
      $this->checkRequireFields($params, array('file'));
      $file = $params['file'];
      if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $file, $result)) {
         $fileType = $result[2];
         $fileContent = str_replace($result[1], '', $file);
         return $this->appCaller->call(
                         Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_ACL, 'saveUserImage', array($fileType, $fileContent));
      } else {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_SAVE_USER_IMAGE_ERROR'), $errorType->code('E_SAVE_USER_IMAGE_ERROR')));
      }
   }

   /**
    * 处理用户中心上传图片
    * 
    * @return array
    */
   public function upload()
   {
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      @set_time_limit(5 * 60);

      $cfg = ConfigProxy::getFrameworkConfig('Net');
      $params = array(
         'uploadDir'     => '/Data/UploadFiles/Apps/ZhuChao/UserCenter',
         'overwrite'     => false,
         'enableFileRef' => true,
         'randomize'     => true,
         'createSubDir'  => true,
         'enableNail'    => false,
         'useOss'        => $cfg->upload->useOss
      );
      $request = $this->di->get('request');
      //这里不做检查，相关参数没指定已经有默认的参数了
      //探测分片信息
      $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
      $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
      $params['chunk'] = $chunk;
      $params['total_chunk'] = $chunks;

      $uploadPath = $params['uploadDir'];
      unset($params['uploadDir']);
      $params['uploadDir'] = $uploadPath;
      //在这里强制的不启用缩略图
      $params['enableNail'] = false;
      $uploader = new Upload($params);
      $files = $request->getUploadedFiles();
      //在这里是否需要检测是否有错误, 探测到错误的时候抛出异常
      return $uploader->saveUploadFile(array_shift($files));
   }

   /**
    * 装修公司添加成员
    * @return type
    */
   public function addMember($params)
   {
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      if (Constant::FRONT_USER_STATUS_LOCK == $user->getStatus()) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_LOCKED'), $errorType->code('E_USER_LOCKED')));
      }
      $params['decoratorId'] = $user->getId();
      if ($user->getType() == 3) {
         $this->appCaller->call(
                 Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_DECORATOR, 'addMember', array($params));
      }
      return $user;
   }

   /**
    * 更新装修公司成员信息
    * @param array $params
    * @return type
    */
   public function updateMember($params)
   {
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      if (Constant::FRONT_USER_STATUS_LOCK == $user->getStatus()) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_LOCKED'), $errorType->code('E_USER_LOCKED')));
      }
      $params['decoratorId'] = $user->getId();
      if ($user->getType() == 3) {
         $id = $params['id'];
         unset($params['id']);
         $this->appCaller->call(
                 Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_DECORATOR, 'updateMember', array($id, $params));
      }
      return $user;
   }

   /**
    * 获得图片url
    * @param type $item
    * @param type $width
    * @param type $height
    * @return string
    */
   public function getImgcdn($url, $width, $height)
   {
      if (!isset($url) || empty($url)) {
         $url = 'Static/lazyicon.png';
      }
      return \Cntysoft\Kernel\get_image_cdn_url_operate($url, array('w' => $width, 'h' => $height, 'e' => 1, 'c' => 1));
   }

   /**
    * 为当前登录用户添加收藏
    * 
    * @param array $params
    */
   public function addFavorites(array $params)
   {
      $this->checkRequireFields($params, array('id', 'type'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $uid = $curUser->getId();
      $data = array('collectTime' => time());

      $type = (int) $params['type'];
      if (!in_array($type, array(1, 2, 3, 4))) {
         return;
      }
      $data['type'] = $type;
      $data['uid'] = $uid;
      $data['infoId'] = (int) $params['id'];

      $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER_MANAGER, 'addFavorites', array($uid, $data)
      );
   }

   /**
    * 评价商品
    * 
    * @param array $params
    */
   public function addComment(array $params)
   {
      $this->checkRequireFields($params, array('ordergoodsId', 'content', 'star'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $uid = $curUser->getId();
      $orderGoods = $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrderGoodsById', array($params['ordergoodsId'])
      );
      $order = $orderGoods->getOrder();
      if ($order->getUid() != $uid || $order->getStatus() != SHOP_CONST::ORDER_STATUS_FINISHED) {
         return false;
      }
      $filter = new StripTags();
      $params['content'] = $filter->filter($params['content']);
      $data = array(
         'pid'     => 0,
         'gid'     => $orderGoods->getGid(),
         'uid'     => $uid,
         'content' => $params['content'],
         'time'    => time(),
         'star'    => $params['star'],
         'status'  => MAR_CONST::COMMENT_S_NOT_VERIFY
      );
      $this->appCaller->call(
              MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COMMENT, 'addCommented', array($orderGoods, $data)
      );
   }

   /**
    * 申请退换货
    * 
    * @param array $params
    */
   public function addReApply(array $params)
   {
      $this->checkRequireFields($params, array('ordergoodsId', 'reason', 'reType'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $uid = $curUser->getId();
      $servicelist = $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_SERCIE, 'getServiceListByCond', array(
         array('uid=?0', 'bind' => array($curUser->getId())), true, 'id desc', 0, 0
      ));
      $enabledId = array();
      foreach ($servicelist as $service) {
         $enabledId[] = $service->getOrderGoodsId();
      }
      if (in_array($params['ordergoodsId'], $enabledId)) {
         return false;
      }
      $orderGoods = $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrderGoodsById', array($params['ordergoodsId'])
      );
      $order = $orderGoods->getOrder();
      if ($order->getUid() != $uid || $order->getStatus() != SHOP_CONST::ORDER_STATUS_FINISHED) {
         return false;
      }
      $status = 1;
      $serviceType = '';
      switch ((int) $params['reType']) {
         case 1:
            $status = SHOP_CONST::ORDER_SERVICE_RETURN_REQUESTED;
            $serviceType = '申请退货';
            break;
         case 2:
            $status = SHOP_CONST::ORDER_SERVICE_EXCHANGE_REQUESTED;
            $serviceType = '申请换货';
            break;
         case 3:
            $status = SHOP_CONST::ORDER_SERVICE_REPAIR_REQUESTED;
            $serviceType = '申请维修';
            break;
         default:
            break;
      }
      $filter = new StripTags();
      $params['reason'] = $filter->filter($params['reason']);
      $data = array(
         'uid'          => $uid,
         'orderGoodsId' => $params['ordergoodsId'],
         'serviceType'  => $serviceType,
         'reason'       => $params['reason'],
         'requestTime'  => time(),
         'status'       => $status
      );
      $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_SERCIE, 'addService', array($data)
      );
   }

   /**
    * 删除当前用户指定类型的收藏
    * 
    * @param array $params
    */
   public function deleteFavorites(array $params)
   {
      $this->checkRequireFields($params, array('infoId', 'type'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $uid = $curUser->getId();
      $type = (int) $params['type'];
      if (!in_array($type, array(1, 2, 3, 4))) {
         return;
      }
      $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_FAVORITES, 'deleteFavorites', array($uid, $params)
      );
   }

   /**
    * 取消订单
    * @param array $params
    */
   public function cancelOrder($params)
   {
      $this->checkRequireFields($params, array('orderNum'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();

      $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'cancelOrderReuqest', array((int) $curUser->getId(), (int) $params['orderNum'], $curUser->getName(), '取消订单')
      );
   }

   /**
    * 取消订单
    * @param array $params
    */
   public function receiveConfirm($params)
   {
      $this->checkRequireFields($params, array('orderNum'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();

      $this->appCaller->call(
              SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'receiveOrderReuqest', array((int) $curUser->getId(), (int) $params['orderNum'], $curUser->getName(), '确认收货')
      );
   }

   /**
    * 获取用户的收藏数
    * 
    * @param array $params
    * @return array
    */
   public function getFavoritesCount()
   {
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $rows = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER_MANAGER, 'getFavoritesByUid', array($curUser->getId())
      );

      $ret = array();
      foreach ($rows as $row) {
         $ret[$row['type']] = $row['count'];
      }

      return $ret;
   }

   /**
    * 返回收藏店铺信息还未使用
    * @return type
    */
   public function getCollectList($params)
   {
      $this->checkRequireFields($params, array('page', 'type', 'limit'));
      $acl = $this->di->get('FrontUserAcl');
      $curUser = $acl->getCurUser();
      $offset = ((int) $params['page'] - 1) * $params['limit'];
      $limit = $params['limit'];
      $infoList = $this->appCaller->call(
              Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USER_MANAGER, 'getFavoritesListByUidAndType', array($curUser->getId(), $params['type'], false, 'collectTime Desc', $offset, $limit)
      );
      $ret = array();
      if (3 == $params['type']) {
         foreach ($infoList as $item) {
            $single = array();
            $merchant = $this->appCaller->call(
                    MERCHANT_CONST::MODULE_NAME, MERCHANT_CONST::APP_NAME, MERCHANT_CONST::APP_API_MGR, 'getMerchantInfo', array($item->getInfoId())
            );
            $single['img'] = $this->getImgcdn($merchant->getShopLogo(), 120, 75);
            $single['name'] = $merchant->getName();
            $single['url'] = $this->getMerchantUrl($merchant->getAbbr());
            $single['collectTime'] = date('Y-m-d', $item->getCollectTime());
            array_push($ret, $single);
         }
         return $ret;
      }
      if (2 == $params['type']) {
         foreach ($infoList as $item) {
            $good = $this->appCaller->call(
                    GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_MGR, 'getGoodsInfo', array($item->getInfoId()));
            $info['id'] = $good->getId();
            $info['title'] = $good->getTitle();
            $info['price'] = $good->getPrice();
            $info['img'] = $this->getImgcdn($good->getImg(), 120, 75);
            array_push($ret, $info);
         }
         return $ret;
      }
      if (1 == $params['type']) {
         foreach ($infoList as $item) {
            $single = $this->appCaller->call(
                    ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'getGInfo', array((int) $item->getInfoId()));
            $info['id'] = $single->getId();
            $info['title'] = $single->getTitle();
            $node = $this->getNodeById($single->getNodeId());
            $info['node'] = $node->getText();
            $info['img'] = $this->getImgcdn($single->getDefaultPicUrl()[0], 120, 75);
            $info['time'] = date('Y-m_d h:i:s', $single->getInputTime());
            array_push($ret, $info);
         }
         return $ret;
      }
      if (4 == $params['type']) {
         foreach ($infoList as $item) {
            $single = $this->appCaller->call(
                    ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'getGInfo', array((int) $item->getInfoId()));
            $info['id'] = $single->getId();
            $info['title'] = $single->getTitle();
            $node = $this->getNodeById($single->getNodeId());
            $info['node'] = $node->getText();
            $info['img'] = $this->getImgcdn($single->getDefaultPicUrl()[0], 600, 375);
            $info['time'] = date('Y-m_d h:i:s', $single->getInputTime());
            array_push($ret, $info);
         }
         return $ret;
      }
   }

   /**
    * 获取指定店铺的地址
    * 
    * @param string $abbr
    * @return string
    */
   public function getMerchantUrl($abbr)
   {
      $baseUrl = SYS_RUNTIME_MODE == SYS_RUNTIME_MODE_PRODUCT ? \Cntysoft\MERCHANT_DOMAIN : \Cntysoft\MERCHANT_DOMAIN_DEBUG;
      return 'http://' . $abbr . '.' . $baseUrl;
   }

   /**
    * 根据栏目id获得栏目信息
    */
   public function getNodeById($nodeId)
   {
      return $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNode', array($nodeId));
   }
   
   public function getWechatAppIdAndAppSecret()
   {
      $config = ConfigProxy::getFrameworkConfig('Pay');
      if(!isset($config['wechatpay']) || !isset($config['wechatpay']['APPID']) || !isset($config['wechatpay']['APPSECRET']) || !isset($config['wechatpay']['MCH_ID']) || !isset($config['wechatpay']['NOTIFY_URL'])){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NO_CONFIG_WECHATPAY_ERROR'), $errorType->code('E_NO_CONFIG_WECHATPAY_ERROR')
                 ), $errorType);
      }
      return $config->wechatpay;
   }
}