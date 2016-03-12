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

class Company extends BaseModel
{
   protected $id;
   protected $customer;
   protected $brand;
   protected $registerCapital;
   protected $registerYear;
   protected $registerProvince;
   protected $registerCity;
   protected $registerDistrict;
   protected $legalPerson;
   protected $bank;
   protected $bankAccount;
   
   public function getSource()
   {
      return 'app_zhuchao_provider_company_info';
   }

   public function getId()
   {
      return (int)$this->id;
   }

   public function getCustomer()
   {
      return $this->customer;
   }

   public function getBrand()
   {
      return $this->brand;
   }

   public function getRegisterCapital()
   {
      return (int)$this->registerCapital;
   }

   public function getRegisterYear()
   {
      return $this->registerYear;
   }

   public function getRegisterProvince()
   {
      return (int)$this->registerProvince;
   }

   public function getRegisterCity()
   {
      return (int)$this->registerCity;
   }

   public function getRegisterDistrict()
   {
      return (int)$this->registerDistrict;
   }

   public function getLegalPerson()
   {
      return $this->legalPerson;
   }

   public function getBank()
   {
      return $this->bank;
   }

   public function getBankAccount()
   {
      return $this->bankAccount;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setCustomer($customer)
   {
      $this->customer = $customer;
   }

   public function setBrand($brand)
   {
      $this->brand = $brand;
   }

   public function setRegisterCapital($registerCapital)
   {
      $this->registerCapital = (int)$registerCapital;
   }

   public function setRegisterYear($registerYear)
   {
      $this->registerYear = $registerYear;
   }

   public function setRegisterProvince($registerProvince)
   {
      $this->registerProvince = (int)$registerProvince;
   }

   public function setRegisterCity($registerCity)
   {
      $this->registerCity = (int)$registerCity;
   }

   public function setRegisterDistrict($registerDistrict)
   {
      $this->registerDistrict = (int)$registerDistrict;
   }

   public function setLegalPerson($legalPerson)
   {
      $this->legalPerson = $legalPerson;
   }

   public function setBank($bank)
   {
      $this->bank = $bank;
   }

   public function setBankAccount($bankAccount)
   {
      $this->bankAccount = $bankAccount;
   }
   
}
