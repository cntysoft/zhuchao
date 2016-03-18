<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\CmMgr\Model;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;
/**
 * 内容模型字段相关字段模型
 */
class Fields extends BaseModel
{
   private $id;
   private $system;
   private $virtual;
   private $name;
   private $tip;
   private $description;
   private $alias;
   private $fieldType;
   private $require;
   private $display;
   private $defaultValue;
   private $priority;
   private $uiOption;
   private $mid;
   private $type;
   public function getSource()
   {
      return 'app_site_cmmgr_cmodel_fields';
   }
   public function initialize()
   {
      parent::initialize();
      $this->belongsTo(
         'mid',
         'App\Yunzhan\CmMgr\Model\Content',
         'id',
         array('alias' => 'contentModel')
      );
   }

   public function getId()
   {
      return (int)$this->id;
   }

   public function getTip()
   {
      return $this->tip;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getSystem()
   {
      return (int)$this->system;
   }

   public function getVirtual()
   {
      return (int)$this->virtual;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getAlias()
   {
      return $this->alias;
   }

   public function getFieldType()
   {
      return $this->fieldType;
   }

   public function getRequire()
   {
      return (int)$this->require;
   }

   public function getDisplay()
   {
      return (int)$this->display;
   }

   public function getDefaultValue()
   {
      return $this->defaultValue;
   }

   /**
    * @return array
    */
   public function getUiOption()
   {
      return unserialize($this->uiOption);
   }

   public function getModel()
   {
      return $this->model;
   }

   /**
    * @return int
    */
   public function getPriority()
   {
      return (int)$this->priority;
   }

   /**
    * @param int $id
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setId($id)
   {
      $this->id = $id;
      return $this;
   }

   /**
    * @param string $system
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setSystem($system)
   {
      $this->system = (int)$system;
      return $this;
   }
   /**
    * @param boolean $virtual
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setVirtual($virtual)
   {
      $this->virtual = (int)$virtual;
      return $this;
   }

   /**
    * @param string $tip
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setTip($tip)
   {
      $this->tip = $tip;
      return $this;
   }

   /**
    * @param string $description
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setDescription($description)
   {
      $this->description = $description;
      return $this;
   }

   /**
    * @param string $name
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setName($name)
   {
      $this->name = $name;
      return $this;
   }

   /**
    * @param string $alias
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setAlias($alias)
   {
      $this->alias = $alias;
      return $this;
   }

   /**
    * @param string $fieldType
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setFieldType($fieldType)
   {
      $this->fieldType = $fieldType;
      return $this;
   }

   /**
    * @param boolean $require
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setRequire($require)
   {
      $this->require = (int)$require;
      return $this;
   }

   /**
    * @param boolean $display
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setDisplay($display)
   {
      $this->display = (int)$display;
      return $this;
   }

   /**
    * @param string $defaultValue
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setDefaultValue($defaultValue)
   {
      $this->defaultValue = $defaultValue;
      return $this;
   }

   /**
    * @param array $uiOption
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setUiOption(array $uiOption)
   {
      $this->uiOption = serialize($uiOption);
      return $this;
   }

   /**
    * @param int $priority
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function setPriority($priority)
   {
      $this->priority = (int)$priority;
      return $this;
   }
   public function getMid()
   {
      return (int)$this->mid;
   }

   public function setMid($mid)
   {
      $this->mid = (int)$mid;
      return $this;
   }

   public function getType()
   {
      return (int)$this->type;
   }

   public function setType($type)
   {
      $this->type = (int)$type;
      return $this;
   }
}