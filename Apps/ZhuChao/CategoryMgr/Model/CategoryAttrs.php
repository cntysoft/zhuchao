<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\CategoryMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
 /**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class CategoryAttrs extends  BaseModel
{
   protected $id;
   protected $nodeId;
   protected $name;
   protected $optValue;
   protected $required;
   protected $group;
   public function getSource()
   {
      return 'app_zhuchao_categorymgr_category_attrs';
   }

   /**
    * @return mixed
    */
   public function getId()
   {
      return (int)$this->id;
   }

   /**
    * @param mixed $id
    */
   public function setId($id)
   {
      $this->id = (int)$id;
   }


   /**
    * @return mixed
    */
   public function getNodeId()
   {
      return (int)$this->nodeId;
   }

   /**
    * @param mixed $id
    */
   public function setNodeId($id)
   {
      $this->nodeId =  (int)$id;
   }

   /**
    * @return mixed
    */
   public function getRequired()
   {
      return (boolean)$this->required;
   }

   /**
    * @param mixed $required
    */
   public function setRequired($required)
   {
      $this->required = (int)$required;
   }

   /**
    * @return mixed
    */
   public function getName()
   {
      return $this->name;
   }

   /**
    * @param mixed $name
    */
   public function setName($name)
   {
      $this->name = $name;
   }

   /**
    * @return mixed
    */
   public function getOptValue()
   {
      return $this->optValue;
   }

   /**
    * @param mixed $optValue
    */
   public function setOptValue($optValue)
   {
      $this->optValue = $optValue;
   }

   /**
    * @return mixed
    */
   public function getGroup()
   {
      return $this->group;
   }

   /**
    * @param mixed $group
    */
   public function setGroup($group)
   {
      $this->group = $group;
   }
}