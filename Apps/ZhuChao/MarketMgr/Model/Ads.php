<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MarketMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
class Ads extends BaseModel
{
   private $id;
   private $name;
   private $contentUrl;
   private $locationId;
   private $bgcolor;
   private $startTime;
   private $endTime;
   private $sort;
   private $image;
   private $fileRefs;
   
   public function getSource()
   {
      return 'app_zhuchao_marketmgr_ads';
   }
   public function getId()
   {
      return $this->id;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getContentUrl()
   {
      return $this->contentUrl;
   }

   public function getLocationId()
   {
      return $this->locationId;
   }

   public function getBgcolor()
   {
      return $this->bgcolor;
   }

   public function getStartTime()
   {
      return $this->startTime;
   }

   public function getEndTime()
   {
      return $this->endTime;
   }

   public function getSort()
   {
      return $this->sort;
   }

   public function getImage()
   {
      return $this->image;
   }

   public function getFileRefs()
   {
      return $this->fileRefs;
   }

   public function setId($id)
   {
      $this->id = $id;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setContentUrl($contentUrl)
   {
      $this->contentUrl = $contentUrl;
   }

   public function setLocationId($locationId)
   {
      $this->locationId = $locationId;
   }

   public function setBgcolor($bgcolor)
   {
      $this->bgcolor = $bgcolor;
   }

   public function setStartTime($startTime)
   {
      $this->startTime = $startTime;
   }

   public function setEndTime($endTime)
   {
      $this->endTime = $endTime;
   }

   public function setSort($sort)
   {
      $this->sort = $sort;
   }

   public function setImage($image)
   {
      $this->image = $image;
   }

   public function setFileRefs($fileRefs)
   {
      $this->fileRefs = $fileRefs;
   }


}