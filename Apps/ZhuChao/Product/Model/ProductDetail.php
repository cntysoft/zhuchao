<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class ProductDetail extends BaseModel
{
   protected $id;
   protected $advertText;
   protected $keywords;
   protected $attribute;
   protected $unit;
   protected $minimum;
   protected $stock;
   protected $images;
   protected $introduction;
   protected $imgRefMap;
   protected $fileRefs;

   public function getSource()
   {
      return 'app_zhuchao_product_detail';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getAdvertText()
   {
      return $this->advertText;
   }

   public function getKeywords()
   {
      return explode(',', $this->keywords);
   }

   public function getAttribute()
   {
      return unserialize($this->attribute);
   }

   public function getUnit()
   {
      return $this->unit;
   }

   public function getMinimum()
   {
      return (int)$this->minimum;
   }

   public function getStock()
   {
      return (int)$this->stock;
   }

   public function getImages()
   {
      return unserialize($this->images);
   }

   public function getIntroduction()
   {
      return $this->introduction;
   }

   public function getImgRefMap()
   {
      return unserialize($this->imgRefMap);
   }

   public function getFileRefs()
   {
      return explode(',', $this->fileRefs);
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setAdvertText($advertText)
   {
      $this->advertText = $advertText;
   }

   public function setKeywords($keywords)
   {
      $this->keywords = implode(',', $keywords);
   }

   public function setAttribute($attribute)
   {
      $this->attribute = serialize($attribute);
   }

   public function setUnit($unit)
   {
      $this->unit = $unit;
   }

   public function setMinimum($minimum)
   {
      $this->minimum = (int)$minimum;
   }

   public function setStock($stock)
   {
      $this->stock = (int)$stock;
   }

   public function setImages($images)
   {
      $this->images = serialize($images);
   }

   public function setIntroduction($introduction)
   {
      $this->introduction = $introduction;
   }

   public function setImgRefMap($imgRefMap)
   {
      $this->imgRefMap = serialize($imgRefMap);
   }

   public function setFileRefs($fileRefs)
   {
      $this->fileRefs = implode(',', $fileRefs);
   }

}
