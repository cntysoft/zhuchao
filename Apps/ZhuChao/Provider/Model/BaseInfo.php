<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
use Phalcon\Mvc\Model\Relation;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class BaseInfo extends BaseModel
{
   private $id;
   private $name;
   private $password;
   private $phone;
   private $registerTime;
   private $lastLoginTime;
   private $lastLoginIp;
   private $loginErrorTimes;
   private $loginTimes;
   private $currentLoginErrorTimes;
   private $status;
   private $profileId;

   public function getSource()
   {
      return 'app_zhuchao_provider_base';
   }

   public function initialize()
   {
      $this->hasOne('profileId', 'App\ZhuChao\Provider\Model\Profile', 'id', array(
         'alias'      => 'profile',
         'foreignKey' => array(
            'action' => Relation::ACTION_CASCADE
         )
      ));
   }

   public function getId()
   {
      return (int)$this->id;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getPassword()
   {
      return $this->password;
   }

   public function getPhone()
   {
      return $this->phone;
   }

   public function getRegisterTime()
   {
      return $this->registerTime;
   }

   public function getLastLoginTime()
   {
      return $this->lastLoginTime;
   }

   public function getLastLoginIp()
   {
      return $this->lastLoginIp;
   }

   public function getLoginErrorTimes()
   {
      return $this->loginErrorTimes;
   }

   public function getLoginTimes()
   {
      return $this->loginTimes;
   }

   public function getCurrentLoginErrorTimes()
   {
      return $this->currentLoginErrorTimes;
   }

   public function getStatus()
   {
      return $this->status;
   }

   public function getProfileId()
   {
      return $this->profileId;
   }

   public function setId($id)
   {
      $this->id = $id;
      return $this;
   }

   public function setName($name)
   {
      $this->name = $name;
      return $this;
   }

   public function setPassword($password)
   {
      $this->password = $password;
      return $this;
   }

   public function setPhone($phone)
   {
      $this->phone = $phone;
      return $this;
   }

   public function setRegisterTime($registerTime)
   {
      $this->registerTime = $registerTime;
      return $this;
   }

   public function setLastLoginTime($lastLoginTime)
   {
      $this->lastLoginTime = $lastLoginTime;
      return $this;
   }

   public function setLastLoginIp($lastLoginIp)
   {
      $this->lastLoginIp = $lastLoginIp;
      return $this;
   }

   public function setLoginErrorTimes($loginErrorTimes)
   {
      $this->loginErrorTimes = $loginErrorTimes;
      return $this;
   }

   public function setLoginTimes($loginTimes)
   {
      $this->loginTimes = $loginTimes;
      return $this;
   }

   public function setCurrentLoginErrorTimes($currentLoginErrorTimes)
   {
      $this->currentLoginErrorTimes = $currentLoginErrorTimes;
      return $this;
   }

   public function setStatus($status)
   {
      $this->status = $status;
      return $this;
   }

   public function setProfileId($profileId)
   {
      $this->profileId = $profileId;
      return $this;
   }

}
