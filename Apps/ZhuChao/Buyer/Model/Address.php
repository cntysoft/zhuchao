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

class Address extends BaseModel
{
   protected $id;
   protected $buyerId;
   protected $username;
   protected $phone;
   protected $province;
   protected $city;
   protected $district;
   protected $address;
   protected $postCode;
   protected $isDefault;
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

   public function getUsername()
   {
      return $this->username;
   }

   public function getPhone()
   {
      return $this->phone;
   }

   public function getProvince()
   {
      return (int)$this->province;
   }

   public function getCity()
   {
      return (int)$this->city;
   }

   public function getDistrict()
   {
      return (int)$this->district;
   }

   public function getAddress()
   {
      return $this->address;
   }

   public function getPostCode()
   {
      return (int)$this->postCode;
   }

   public function getIsDefault()
   {
      return (int)$this->isDefault;
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

   public function setUsername($username)
   {
      $this->username = $username;
   }

   public function setPhone($phone)
   {
      $this->phone = $phone;
   }

   public function setProvince($province)
   {
      $this->province = (int)$province;
   }

   public function setCity($city)
   {
      $this->city = (int)$city;
   }

   public function setDistrict($district)
   {
      $this->district = (int)$district;
   }

   public function setAddress($address)
   {
      $this->address = $address;
   }

   public function setPostCode($postCode)
   {
      $this->postCode = (int)$postCode;
   }

   public function setIsDefault($isDefault)
   {
      $this->isDefault = (int)$isDefault;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

}

