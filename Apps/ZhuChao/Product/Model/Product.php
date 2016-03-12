<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Product extends BaseModel
{
   protected $id;
   protected $categoryId;
   protected $providerId;
   protected $companyId;
   protected $number;
   protected $brand;
   protected $title;
   protected $description;
   protected $hits;
   protected $defaultImage;
   protected $price;
   protected $grade;
   protected $star;
   protected $searchAttrMap;
   protected $indexGenerated;
   protected $isBatch;
   protected $inputTime;
   protected $updateTime;
   protected $status;
   
   public function getSource()
   {
      return 'app_zhuchao_product_base_info';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getCategoryId()
   {
      return (int)$this->categoryId;
   }

   public function getProviderId()
   {
      return (int)$this->providerId;
   }

   public function getCompanyId()
   {
      return (int)$this->companyId;
   }

   public function getNumber()
   {
      return $this->number;
   }

   public function getBrand()
   {
      return $this->brand;
   }

   public function getTitle()
   {
      return $this->title;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getHits()
   {
      return (int)$this->hits;
   }

   public function getDefaultImage()
   {
      return $this->defaultImage;
   }

   public function getPrice()
   {
      return $this->price;
   }

   public function getGrade()
   {
      return (int)$this->grade;
   }

   public function getStar()
   {
      return (int)$this->star;
   }

   public function getSearchAttrMap()
   {
      return $this->searchAttrMap;
   }

   public function getIndexGenerated()
   {
      return (int)$this->indexGenerated;
   }

   public function getIsBatch()
   {
      return (int)$this->isBatch;
   }

   public function getInputTime()
   {
      return (int)$this->inputTime;
   }

   public function getUpdateTime()
   {
      return (int)$this->updateTime;
   }

   public function getStatus()
   {
      return (int)$this->status;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setCategoryId($categoryId)
   {
      $this->categoryId = (int)$categoryId;
   }

   public function setProviderId($providerId)
   {
      $this->providerId = (int)$providerId;
   }

   public function setCompanyId($companyId)
   {
      $this->companyId = (int)$companyId;
   }

   public function setNumber($number)
   {
      $this->number = $number;
   }

   public function setBrand($brand)
   {
      $this->brand = $brand;
   }

   public function setTitle($title)
   {
      $this->title = $title;
   }

   public function setDescription($description)
   {
      $this->description = $description;
   }

   public function setHits($hits)
   {
      $this->hits = (int)$hits;
   }

   public function setDefaultImage($defaultImage)
   {
      $this->defaultImage = $defaultImage;
   }

   public function setPrice($price)
   {
      $this->price = $price;
   }

   public function setGrade($grade)
   {
      $this->grade = (int)$grade;
   }

   public function setStar($star)
   {
      $this->star = (int)$star;
   }

   public function setSearchAttrMap($searchAttrMap)
   {
      $this->searchAttrMap = $searchAttrMap;
   }

   public function setIndexGenerated($indexGenerated)
   {
      $this->indexGenerated = $indexGenerated;
   }

   public function setIsBatch($isBatch)
   {
      $this->isBatch = (int)$isBatch;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

   public function setUpdateTime($updateTime)
   {
      $this->updateTime = (int)$updateTime;
   }

   public function setStatus($status)
   {
      $this->status = (int)$status;
   }

}