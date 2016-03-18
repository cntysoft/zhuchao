<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\CmMgr\Model;
use Phalcon\Mvc\Model\Relation;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;
/**
 * 内容模型数据对象
 */
class Content extends BaseModel
{
   private $id;
   private $key;
   private $name;
   private $description;
   private $itemName;
   private $itemUnit;
   private $enabled;
   private $icon;
   private $defaultTemplateFile;
   private $editor;
   private $dataSaver;
   private $buildIn;
   private $extraConfig;
   public function initialize()
   {
      parent::initialize();
      $this->hasMany(
         'id',
         'App\Yunzhan\CmMgr\Model\Fields',
         'mid',
         array(
            'alias'      => 'fields',
            'foreignKey' => array(
               'action' => Relation::ACTION_CASCADE
            )
         )
      );
   }
   public function getSource()
   {
      return 'app_site_cmmgr_cmodel';
   }
   public function getId()
   {
      return (int)$this->id;
   }

   public function getKey()
   {
      return $this->key;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getItemName()
   {
      return $this->itemName;
   }

   public function getItemUnit()
   {
      return $this->itemUnit;
   }

   public function getEnabled()
   {
      return (boolean) $this->enabled;
   }
   public function getIcon()
   {
      return $this->icon;
   }

   public function getDefaultTemplateFile()
   {
      return $this->defaultTemplateFile;
   }

   public function getEditor()
   {
      return $this->editor;
   }

   public function getDataSaver()
   {
      return $this->dataSaver;
   }

   public function getBuildIn()
   {
      return (boolean)$this->buildIn;
   }

   public function getExtraConfig()
   {
      return unserialize($this->extraConfig);
   }

   public function setId($id)
   {
      $this->id = (int)$id;
      return $this;
   }

   public function setKey($key)
   {
      $this->key = $key;
      return $this;
   }

   public function setName($name)
   {
      $this->name = $name;
      return $this;
   }

   public function setDescription($description)
   {
      $this->description = $description;
      return $this;
   }

   public function setItemName($itemName)
   {
      $this->itemName = $itemName;
      return $this;
   }

   public function setItemUnit($itemUnit)
   {
      $this->itemUnit = $itemUnit;
      return $this;
   }


   public function setEnabled($enabled)
   {
      $this->enabled = (int) $enabled;
      return $this;
   }

   public function setIcon($icon)
   {
      $this->icon = $icon;
      return $this;
   }

   public function setDefaultTemplateFile($defaultTemplateFile)
   {
      $this->defaultTemplateFile = $defaultTemplateFile;
      return $this;
   }

   public function setEditor($editor)
   {
      $this->editor = $editor;
      return $this;
   }

   public function setDataSaver($dataSaver)
   {
      $this->dataSaver = $dataSaver;
      return $this;
   }

   public function setBuildIn($flag)
   {
      $this->buildIn = (int) $flag;
      return $this;
   }

   public function setExtraConfig(array $extraConfig)
   {
      $this->extraConfig = serialize($extraConfig);
      return $this;
   }

   public function getModelFields()
   {
      return $this->fields;
   }
}