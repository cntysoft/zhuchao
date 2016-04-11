<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Service\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Feedback extends BaseModel
{
   protected $id;
   protected $type;
   protected $text;
   protected $name;
   protected $phone;
   protected $email;
   protected $qq;
   protected $inputTime;
   protected $status;
   protected $identify;
   
   public function getSource()
   {
      return 'app_zhuchao_service_feedback';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getType()
   {
      return (int)$this->type;
   }

   public function getText()
   {
      return $this->text;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getPhone()
   {
      return $this->phone;
   }

   public function getEmail()
   {
      return $this->email;
   }

   public function getQq()
   {
      return $this->qq;
   }

   public function getInputTime()
   {
      return (int)$this->inputTime;
   }

   public function getStatus()
   {
      return (int)$this->status;
   }

   public function getIdentify()
   {
      return $this->identify;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setType($type)
   {
      $this->type = (int)$type;
   }

   public function setText($text)
   {
      $this->text = $text;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setPhone($phone)
   {
      $this->phone = $phone;
   }

   public function setEmail($email)
   {
      $this->email = $email;
   }

   public function setQq($qq)
   {
      $this->qq = $qq;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

   public function setStatus($status)
   {
      $this->status = (int)$status;
   }

   public function setIdentify($identify)
   {
      $this->identify = $identify;
   }


}
