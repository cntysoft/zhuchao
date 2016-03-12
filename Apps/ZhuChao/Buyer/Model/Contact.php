<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Contact extends BaseModel
{
   protected $id;
   protected $buyerId;
   protected $productId;
   protected $name;
   protected $phone;
   protected $inputTime;
   
   public function getSource()
   {
      return 'app_zhuchao_buyer_address_info';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getBuyerId()
   {
      return (int)$this->buyerId;
   }

   public function getProductId()
   {
      return (int)$this->productId;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getPhone()
   {
      return $this->phone;
   }

   public function getInputTime()
   {
      return (int)$this->inputTime;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setBuyerId($buyerId)
   {
      $this->buyerId = (int)$buyerId;
   }

   public function setProductId($productId)
   {
      $this->productId = (int)$productId;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setPhone($phone)
   {
      $this->phone = $phone;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

}

