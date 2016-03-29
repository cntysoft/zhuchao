<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\CmMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
class General extends BaseModel
{
   private $id;
   private $nodeId;
   private $isDeleted;
   private $cmodelId;
   private $itemId;
   private $title;
   private $priority;
   private $titleColor;
   private $titleStyle;
   private $editor;
   private $author;
   private $hits;
   private $inputTime;
   private $updateTime;
   private $infoGrade;
   private $status;
   private $passTime;
   private $defaultPicUrl;
   private $intro;
   private $indexGenerated;
   public function getSource()
   {
      return 'app_site_cmmgr_general_info';
   }
	public function initialize()
   {
      $this->hasOne('itemId', 'App\Site\CmMgr\Model\Article', 'id', array(
         'alias' => 'Detail'
      ));
   }

   public function getId()
   {
      return (int)$this->id;
   }

   public function getNodeId()
   {
      return (int)$this->nodeId;
   }

   public function getIsDeleted()
   {
      return (int)$this->isDeleted;
   }

   public function getCmodelId()
   {
      return (int)$this->cmodelId;
   }

   public function getItemId()
   {
      return (int)$this->itemId;
   }

   public function getTitle()
   {
      return $this->title;
   }

   public function getPriority()
   {
      return (int)$this->priority;
   }

   public function getTitleColor()
   {
      return $this->titleColor;
   }

   public function getTitleStyle()
   {
      return $this->titleStyle;
   }

   public function getEditor()
   {
      return $this->editor;
   }

   public function getAuthor()
   {
      return $this->author;
   }

   public function getHits()
   {
      return (int)$this->hits;
   }

   public function getInputTime()
   {
      return (int) $this->inputTime;
   }

   public function getUpdateTime()
   {
      return (int)$this->updateTime;
   }

   public function getInfoGrade()
   {
      return $this->infoGrade;
   }

   public function getStatus()
   {
      return (int)$this->status;
   }

   public function getPassTime()
   {
      return (int)$this->passTime;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
      return $this;
   }
   function getIntro()
   {
      return $this->intro;
   }

   function setIntro($intro)
   {
      $this->intro = $intro;
   }

   public function setNodeId($nodeId)
   {
      $this->nodeId = (int)$nodeId;
      return $this;
   }

   public function setIsDeleted($isDeleted)
   {
      $this->isDeleted = (int)$isDeleted;
      return $this;
   }

   public function setCmodelId($cmodelId)
   {
      $this->cmodelId = (int)$cmodelId;
      return $this;
   }

   public function setItemId($itemId)
   {
      $this->itemId = (int)$itemId;
      return $this;
   }

   public function setTitle($title)
   {
      $this->title = $title;
      return $this;
   }

   public function setPriority($priority)
   {
      $this->priority = (int) $priority;
      return $this;
   }

   public function setTitleColor($color)
   {
      $this->titleColor = $color;
      return $this;
   }

   public function setTitleStyle($style)
   {
      $this->titleStyle = $style;
      return $this;
   }

   public function setEditor($editor)
   {
      $this->editor = $editor;
      return $this;
   }

   public function setAuthor($author)
   {
      $this->author = $author;
      return $this;
   }

   public function setHits($hits)
   {
      $this->hits = (int)$hits;
      return $this;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int) $inputTime;
      return $this;
   }

   public function setUpdateTime($updateTime)
   {
      $this->updateTime = (int) $updateTime;
      return $this;
   }

   public function setInfoGrade($grade)
   {
      $this->infoGrade = $grade;
      return $this;
   }

   public function setStatus($status)
   {
      $this->status = (int)$status;
      return $this;
   }

   public function setPassTime($passTime)
   {
      $this->passTime = $passTime;
      return $this;
   }

   /**
    * @return mixed
    */
   public function getDefaultPicUrl()
   {
      return unserialize($this->defaultPicUrl);
   }

   /**
    * @param mixed $defaultPicUrl
    */
   public function setDefaultPicUrl($defaultPicUrl)
   {
      $this->defaultPicUrl = serialize($defaultPicUrl);
   }
   
   function getIndexGenerated()
   {
      return (boolean)$this->indexGenerated;
   }

   function setIndexGenerated($indexGenerated)
   {
      $this->indexGenerated = (boolean)$indexGenerated;
   }



}