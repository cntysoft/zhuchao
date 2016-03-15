<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Provider\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
use Phalcon\Mvc\Model\Relation;
class Company extends BaseModel
{
   protected $id;
   protected $providerId;
   protected $name;
   protected $type;
   protected $tradeMode;
   protected $products;
   protected $province;
   protected $city;
   protected $district;
   protected $address;
   protected $postCode;
   protected $website;
   protected $description;
   protected $logo;
   protected $status;
   protected $inputTime;
   protected $profileId;

   public function getSource()
   {
      return 'app_zhuchao_provider_company_info';
   }

   public function initialize()
   {
      $this->hasOne('profileId', 'App\ZhuChao\Provider\Model\CompanyProfile', 'id', array(
         'alias'      => 'profile',
         'foreignKey' => array(
            'action' => Relation::ACTION_CASCADE
         )
      ));
      $this->hasOne('providerId', 'App\ZhuChao\Provider\Model\BaseInfo', 'id', array(
         'alias' => 'provider'
      ));
   }

   public function getId()
   {
      return (int) $this->id;
   }

   public function getProviderId()
   {
      return (int) $this->providerId;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getType()
   {
      return (int) $this->type;
   }

   public function getTradeMode()
   {
      return (int) $this->tradeMode;
   }

   public function getProducts()
   {
      return $this->products;
   }

   public function getProvince()
   {
      return (int) $this->province;
   }

   public function getCity()
   {
      return (int) $this->city;
   }

   public function getDistrict()
   {
      return (int) $this->district;
   }

   public function getAddress()
   {
      return $this->address;
   }

   public function getPostCode()
   {
      return (int) $this->postCode;
   }

   public function getWebsite()
   {
      return $this->website;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getLogo()
   {
      return $this->logo;
   }

   public function getStatus()
   {
      return (int) $this->status;
   }

   public function getInputTime()
   {
      return (int) $this->inputTime;
   }

   public function getProfileId()
   {
      return (int) $this->profileId;
   }

   public function setId($id)
   {
      $this->id = (int) $id;
   }

   public function setProviderId($providerId)
   {
      $this->providerId = (int) $providerId;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setType($type)
   {
      $this->type = (int) $type;
   }

   public function setTradeMode($tradeMode)
   {
      $this->tradeMode = (int) $tradeMode;
   }

   public function setProducts($products)
   {
      $this->products = $products;
   }

   public function setProvince($province)
   {
      $this->province = (int) $province;
   }

   public function setCity($city)
   {
      $this->city = (int) $city;
   }

   public function setDistrict($district)
   {
      $this->district = (int) $district;
   }

   public function setAddress($address)
   {
      $this->address = $address;
   }

   public function setPostCode($postCode)
   {
      $this->postCode = (int) $postCode;
   }

   public function setWebsite($website)
   {
      $this->website = $website;
   }

   public function setDescription($description)
   {
      $this->description = $description;
   }

   public function setLogo($logo)
   {
      $this->logo = $logo;
   }

   public function setStatus($status)
   {
      $this->status = (int) $status;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int) $inputTime;
   }

   public function setProfileId($profileId)
   {
      $this->profileId = (int) $profileId;
   }

}