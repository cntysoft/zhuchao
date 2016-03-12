<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class ProductDetail extends BaseModel
{
   protected $id;
   protected $productId;
   protected $adverText;
   protected $keyWords;
   protected $attribute;
   protected $unit;
   protected $minimum;
   protected $stock;
   protected $images;
   protected $description;
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

   public function getProductId()
   {
      return (int)$this->productId;
   }

   public function getAdverText()
   {
      return $this->adverText;
   }

   public function getKeyWords()
   {
      return $this->keyWords;
   }

   public function getAttribute()
   {
      return $this->attribute;
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
      return $this->images;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getImgRefMap()
   {
      return $this->imgRefMap;
   }

   public function getFileRefs()
   {
      return $this->fileRefs;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setProductId($productId)
   {
      $this->productId = (int)$productId;
   }

   public function setAdverText($adverText)
   {
      $this->adverText = $adverText;
   }

   public function setKeyWords($keyWords)
   {
      $this->keyWords = $keyWords;
   }

   public function setAttribute($attribute)
   {
      $this->attribute = $attribute;
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
      $this->images = $images;
   }

   public function setDescription($description)
   {
      $this->description = $description;
   }

   public function setImgRefMap($imgRefMap)
   {
      $this->imgRefMap = $imgRefMap;
   }

   public function setFileRefs($fileRefs)
   {
      $this->fileRefs = $fileRefs;
   }

}
