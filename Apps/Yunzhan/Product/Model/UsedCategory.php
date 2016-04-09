<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Product\Model;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;

class UsedCategory extends BaseModel
{
   private $id;
   private $categoryId;
   private $categoryText;

   public function getSource()
   {
      return 'app_zhuchao_product_used_category';
   }
   
   public function initialize()
   {
      parent::initialize();
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getCategoryId()
   {
      return (int)$this->categoryId;
   }

   public function getCategoryText()
   {
      return $this->categoryText;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setCategoryId($categoryId)
   {
      $this->categoryId = (int)$categoryId;
   }

   public function setCategoryText($categoryText)
   {
      $this->categoryText = $categoryText;
   }

}