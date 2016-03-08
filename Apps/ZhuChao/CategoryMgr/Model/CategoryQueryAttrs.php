<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\CategoryMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * 查询属性模型对象
 */
class CategoryQueryAttrs extends BaseModel
{
   protected $id;
   protected $categoryId;
   protected $name;
   protected $optValues;
   public function getSource()
   {
      return 'app_zhuchao_categorymgr_category_query_attrs';
   }
   public function getId()
   {
      return (int)$this->id;
   }

   public function getCategoryId()
   {
      return (int)$this->categoryId;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getOptValues()
   {
      return $this->optValues;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setCategoryId($categoryId)
   {
      $this->categoryId = (int)$categoryId;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setOptValues($optValues)
   {
      $this->optValues = $optValues;
   }
}