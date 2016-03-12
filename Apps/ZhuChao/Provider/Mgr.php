<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Provider;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\ZhuChao\Provider\Model\BaseInfo as BaseModel;
use App\ZhuChao\Provider\Model\Profile as ProfileModel;
/**
 * 站点管理员角色管理
 */
class Mgr extends AbstractLib
{
   /**
    * 添加供应商信息
    * 
    * @param array $data
    */
   public function addProvider($data)
   {
      unset($data['id']);
      $baseInfo = new BaseModel();
      $requires = $baseInfo->getRequireFields(array('id', 'profileId'));
      $data += array(
         'loginTimes'             => 0,
         'status'                 => Constant::PROVIDER_STATUS_NORMAL, //默认正常
         'loginErrorTimes'        => 0,
         'registerTime'           => time(),
         'lastLoginTime'          => time(),
         'currentLoginErrorTimes' => 0,
         'lastLoginIp'            => ''
      );
      Kernel\ensure_array_has_fields($data, $requires);
      if ($this->providerNameExist($data['name'])) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_NAME_EXIST'), $errorType->code('E_USER_NAME_EXIST')), $this->getErrorTypeContext());
      }
      if ($this->providerPhoneExist($data['phone'])) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_PHONE_EXIST'), $errorType->code('E_USER_PHONE_EXIST')), $this->getErrorTypeContext());
      }

      $di = Kernel\get_global_di();
      $security = $di->getShared('security');
      $data['password'] = $security->hash($data['password']);
      //处理用户的基本信息和详细信息
      $profile = new ProfileModel();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $profile->assignBySetter($profileData);
         $profile->create();
         $baseInfo->setProfileId($profile->getId());
         $baseInfo->assignBySetter($data);
         $baseInfo->create();

         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 
    * @param int $id
    * @param array $data
    */
   public function updateProvider($id, $data)
   {
      $id = (int) $id;
      unset($data['id']);
      $provider = $this->getProvider($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_IS_NOT_EXIST', $id), $errorType->code('E_USER_IS_NOT_EXIST')
                 ), $this->getErrorTypeContext());
      }
      $profile = $provider->getProfile();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (isset($data['password'])) {
            $di = Kernel\get_global_di();
            $security = $di->getShared('security');
            $data['password'] = $security->hash($data['password']);
         }
         $profile->assignBySetter($profileData);
         $profile->save();

         $provider->assignBySetter($data);
         $provider->save();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 查看用户名称是否存在，存在返回 true，不存在返回 false
    * 
    * @param string $name
    * @return boolean
    */
   public function providerNameExist($name)
   {
      $provider = BaseModel::findFirst("name = '$name'");
      return $provider ? true : false;
   }

   /**
    * 查看用户手机号码是否存在，存在返回 true，不存在返回 false
    * 
    * @param string $phone
    * @return boolean
    */
   public function providerPhoneExist($phone)
   {
      $provider = BaseModel::findFirst("phone = '$phone'");
      return $provider ? true : false;
   }

   /**
    * 修改供应商的状态
    * 
    * @param int $id
    * @param int $status
    * @return boolean
    */
   public function changeStatus($id, $status)
   {
      $provider = BaseModel::findFirst($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_NOT_EXIST'), $errorType->code('E_USER_NOT_EXIST')), $this->getErrorTypeContext());
      }

      $provider->setStatus($status);
      return $provider->save();
   }

   /**
    * 获取供应商信息
    * 
    * @param type $id
    * @return App\ZhuChao\Provider\Model\BaseInfo
    */
   public function getProvider($id)
   {
      $provider = BaseModel::findFirst($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_NOT_EXIST'), $errorType->code('E_USER_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $provider;
   }

   /**
    * 根据名称获取供应商
    * 
    * @param string $name
    * @return 
    */
   public function getProviderByName($name)
   {
      return BaseModel::findFirst("name = '$name'");
   }
   
   /**
    * 根据手机号码获取供应商
    * 
    * @param string $phone
    * @return 
    */
   public function getProviderByPhone($phone)
   {
      return BaseModel::findFirst("phone = '$phone'");
   }
}