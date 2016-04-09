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

   public function getSource()
   {
      return 'app_zhuchao_product_used_category';
   }
   
   public function initialize()
   {
      parent::initialize();
   }
   
   function getId()
   {
      return (int)$this->providerId;
   }

   function getCategoryId()
   {
      return (int)$this->categoryId;
   }

   function setId($id)
   {
      $this->id = (int)$id;
   }

   function setCategoryId($categoryId)
   {
      $this->categoryId = (int)$categoryId;
   }

}