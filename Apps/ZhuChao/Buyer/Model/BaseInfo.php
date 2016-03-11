<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Buyer\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
class BaseInfo extends BaseModel
{
   protected $id;
   protected $name;
   protected $phone;
   protected $password;
   protected $registerTime;
   protected $registerIp;
   protected $loginTimes;
   protected $lastLoginIp;
   protected $lastLoginTime;
   protected $lastLogoutTime;
   protected $lastModifyPwdTime;
   protected $loginErrorTimes;
   protected $profileId;
   protected $status;

   public function getSource()
   {
      return 'app_zhuchao_buyer_base_info';
   }

   public function initialize()
   {
      $this->belongsTo('profileId', 'App\ZhuChao\Buyer\Model\Profile', 'id', array(
         'alias' => 'profile'
      ));
   }

   public function getId()
   {
      return (int) $this->id;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getPhone()
   {
      return $this->phone;
   }

   public function getPassword()
   {
      return $this->password;
   }

   public function getRegisterTime()
   {
      return (int) $this->registerTime;
   }

   public function getRegisterIp()
   {
      return $this->registerIp;
   }

   public function getLoginTimes()
   {
      return (int) $this->loginTimes;
   }

   public function getLastLoginIp()
   {
      return $this->lastLoginIp;
   }

   public function getLastLoginTime()
   {
      return (int) $this->lastLoginTime;
   }

   public function getLastLogoutTime()
   {
      return (int) $this->lastLogoutTime;
   }

   public function getLastModifyPwdTime()
   {
      return (int) $this->lastModifyPwdTime;
   }

   public function getLoginErrorTimes()
   {
      return (int) $this->loginErrorTimes;
   }

   public function getProfileId()
   {
      return (int) $this->profileId;
   }

   public function getStatus()
   {
      return (int) $this->status;
   }

   public function setId($id)
   {
      $this->id = (int) $id;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setPhone($phone)
   {
      $this->phone = $phone;
   }

   public function setPassword($password)
   {
      $this->password = $password;
   }

   public function setRegisterTime($registerTime)
   {
      $this->registerTime = (int) $registerTime;
   }

   public function setRegisterIp($registerIp)
   {
      $this->registerIp = $registerIp;
   }

   public function setLoginTimes($loginTimes)
   {
      $this->loginTimes = (int) $loginTimes;
   }

   public function setLastLoginIp($lastLoginIp)
   {
      $this->lastLoginIp = $lastLoginIp;
   }

   public function setLastLoginTime($lastLoginTime)
   {
      $this->lastLoginTime = (int) $lastLoginTime;
   }

   public function setLastLogoutTime($lastLogoutTime)
   {
      $this->lastLogoutTime = (int) $lastLogoutTime;
   }

   public function setLastModifyPwdTime($lastModifyPwdTime)
   {
      $this->lastModifyPwdTime = (int) $lastModifyPwdTime;
   }

   public function setLoginErrorTimes($loginErrorTimes)
   {
      $this->loginErrorTimes = (int) $loginErrorTimes;
   }

   public function setProfileId($profileId)
   {
      $this->profileId = (int) $profileId;
   }

   public function setStatus($status)
   {
      $this->status = (int) $status;
   }

}