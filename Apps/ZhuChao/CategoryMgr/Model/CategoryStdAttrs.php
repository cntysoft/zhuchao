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
class CategoryStdAttrs extends BaseModel
{
   private $id;
   private $name;
   private $nodeId;
   private $optValue;

   public function getSource()
   {
      return 'app_zhuchao_categorymgr_category_std_attrs';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getNodeId()
   {
      return (int)$this->nodeId;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
      return $this;
   }

   public function setName($name)
   {
      $this->name = $name;
      return $this;
   }

   public function setNodeId($nodeId)
   {
      $this->nodeId = (int)$nodeId;
      return $this;
   }

   public function getOptValue()
   {
      return $this->optValue;
   }

   public function setOptValue($optValue)
   {
      $this->optValue = $optValue;
      return $this;
   }

}