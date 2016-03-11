<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Profile extends BaseModel
{
   private $id;
   private $realName;
   private $sex;
   private $department;
   private $position;
   private $email;
   private $showPhone;
   private $qq;
   private $tel;
   private $fax;
   
   public function getSource()
   {
      return 'app_zhuchao_provider_profile';
   }
   
   public function getId()
   {
      return $this->id;
   }

   public function getRealName()
   {
      return $this->realName;
   }

   public function getSex()
   {
      return $this->sex;
   }

   public function getDepartment()
   {
      return $this->department;
   }

   public function getPosition()
   {
      return $this->position;
   }

   public function getEmail()
   {
      return $this->email;
   }

   public function getShowPhone()
   {
      return $this->showPhone;
   }

   public function getQq()
   {
      return $this->qq;
   }

   public function getTel()
   {
      return $this->tel;
   }

   public function getFax()
   {
      return $this->fax;
   }

   public function setId($id)
   {
      $this->id = $id;
      return $this;
   }

   public function setPid($pid)
   {
      $this->pid = $pid;
      return $this;
   }

   public function setRealName($realName)
   {
      $this->realName = $realName;
      return $this;
   }

   public function setSex($sex)
   {
      $this->sex = $sex;
      return $this;
   }

   public function setDepartment($department)
   {
      $this->department = $department;
      return $this;
   }

   public function setPosition($position)
   {
      $this->position = $position;
      return $this;
   }

   public function setEmail($email)
   {
      $this->email = $email;
      return $this;
   }

   public function setShowPhone($showPhone)
   {
      $this->showPhone = $showPhone;
      return $this;
   }

   public function setQq($qq)
   {
      $this->qq = $qq;
      return $this;
   }

   public function setTel($tel)
   {
      $this->tel = $tel;
      return $this;
   }

   public function setFax($fax)
   {
      $this->fax = $fax;
      return $this;
   }


}
