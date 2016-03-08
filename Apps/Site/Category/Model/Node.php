<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Category\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
use Cntysoft\Kernel;
use Phalcon\Mvc\Model\Query\Builder as QueryBuilder;
/**
 * 节点模型对象
 */
class Node extends BaseModel
{
   private $id;
   private $pid;
   private $text;
   private $dirname;
   private $nodeIdentifier;
   private $priority;
   private $showOnListParent;
   private $createDate;
   private $nodeType;
   private $openType;
   private $showOnMenu;
   private $listTemplateFile;
   private $coverTemplateFile;
   private $itemOpenType;
   private $description;
   private $linkUrl;
   private $metaKeywords;
   private $metaDescription;
   private $contentModels;

   public function getSource()
   {
      return 'app_site_category_tree';
   }
   public function setId($id)
   {
      $this->id = (int)$id;
      return $this;
   }
   public function getId()
   {
      return (int) $this->id;
   }

   public function getPid()
   {
      return (int) $this->pid;
   }

   public function getText()
   {
      return $this->text;
   }

   public function getDirname()
   {
      return $this->dirname;
   }

   public function getNodeIdentifier()
   {
      return $this->nodeIdentifier;
   }

   public function getShowOnListParent()
   {
      return (int) $this->showOnListParent;
   }


   public function getPriority()
   {
      return (int) $this->priority;
   }

   public function getCreateDate()
   {
      return $this->createDate;
   }

   public function getNodeType()
   {
      return (int) $this->nodeType;
   }

   public function getOpenType()
   {
      return (int) $this->openType;
   }

   public function getShowOnMenu()
   {
      return (int) $this->showOnMenu;
   }

   public function setShowOnMenu($flag)
   {
      $this->showOnMenu = (int) $flag;
      return $this;
   }

   public function getListTemplateFile()
   {
      return $this->listTemplateFile;
   }

   public function getCoverTemplateFile()
   {
      return $this->coverTemplateFile;
   }

   public function getItemOpenType()
   {
      return (int) $this->itemOpenType;
   }

   public function getDescription()
   {
      return $this->description;
   }

   public function getLinkUrl()
   {
      return $this->linkUrl;
   }

   public function getMetaKeywords()
   {
      return $this->metaKeywords;
   }

   public function getMetaDescription()
   {
      return $this->metaDescription;
   }

   public function getContentModels()
   {
      if (null === $this->id) {
         return null;
      }
      //这个地方要多表链接了
      $builder = new QueryBuilder();
      $builder->addFrom('App\Site\CmMgr\Model\Content', 'content');
      $builder->leftJoin('App\Site\Category\Model\C2Map', 'content.id = map.modelId', 'map');
      $builder->where('map.categoryId = ?0');
      $query = $builder->getQuery();
      return $query->execute(array(
         0 => $this->id
      ));
   }

   public function setPid($pid)
   {
      $this->pid = (int) $pid;
      return $this;
   }

   public function setText($text)
   {
      $this->text = $text;
      return $this;
   }

   public function setDirname($dirname)
   {
      $this->dirname = $dirname;
      return $this;
   }

   public function setNodeIdentifier($nodeIdentifier)
   {
      $this->nodeIdentifier = $nodeIdentifier;
      return $this;
   }

   public function setShowOnListParent($showOnListParent)
   {
      $this->showOnListParent = (int) $showOnListParent;
      return $this;
   }


   public function setPriority($priority)
   {
      $this->priority = (int) $priority;
      return $this;
   }

   public function setCreateDate($createDate)
   {
      $this->createDate = (int)$createDate;
      return $this;
   }

   public function setNodeType($nodeType)
   {
      $this->nodeType = (int) $nodeType;
      return $this;
   }

   public function setOpenType($openType)
   {
      $this->openType = (int) $openType;
      return $this;
   }

   public function setListTemplateFile($listTemplateFile)
   {
      $this->listTemplateFile = $listTemplateFile;
      return $this;
   }

   public function setCoverTemplateFile($coverTemplateFile)
   {
      $this->coverTemplateFile = $coverTemplateFile;
      return $this;
   }

   public function setItemOpenType($itemOpenType)
   {
      $this->itemOpenType = (int) $itemOpenType;
      return $this;
   }

   public function setDescription($description)
   {
      $this->description = $description;
      return $this;
   }

   public function setLinkUrl($url)
   {
      $this->linkUrl = $url;
      return $this;
   }

   public function setMetaKeywords($metaKeywords)
   {
      $this->metaKeywords = $metaKeywords;
      return $this;
   }

   public function setMetaDescription($metaDescription)
   {
      $this->metaDescription = $metaDescription;
      return $this;
   }


   public function toArray($byGetter = false, array $skips = array())
   {
      $ret = parent::toArray($byGetter, $skips);
      if (!$byGetter) {
         $ret['contentModels'] = $this->getContentModels();
      }
      return $ret;
   }

}