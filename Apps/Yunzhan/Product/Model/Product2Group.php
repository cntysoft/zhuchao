<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\Yunzhan\Product\Model;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;

class Product2Group extends BaseModel
{  
   protected $productId;
   protected $groupId;
   
   public function getSource()
   {
      return 'join_product_group';
   }
   
   public function getProductId()
   {
      return (int)$this->productId;
   }

   public function getGroupId()
   {
      return (int)$this->groupId;
   }

   public function setProductId($productId)
   {
      $this->productId = (int)$productId;
   }

   public function setGroupId($groupId)
   {
      $this->groupId = (int)$groupId;
   }

}
