<?php

namespace App\Site\CmMgr\Model;

use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Article extends BaseModel
{
   private $id = null;
   private $keywords = null;
   private $copyFrom = null;
   private $content = null;
   private $fileRefs = null;
   private $imgRefMap = null;
   public function getSource()
   {
      return "app_site_cmmgr_u_article";
   }

   public function getId()
   {
      return (int)$this->id;
   }

   /**
    * @return \App\Site\CmMgr\Model\Article
    */
   public function setId($id)
   {
      $this->id = (int)$id;
      return $this;
   }


   public function getKeywords()
   {
      return $this->keywords;
   }

   /**
    * @return \App\Site\CmMgr\Model\Article
    */
   public function setKeywords($keywords)
   {
      $this->keywords = $keywords;
      return $this;
   }

   public function getCopyFrom()
   {
      return $this->copyFrom;
   }

   /**
    * @return \App\Site\CmMgr\Model\Article
    */
   public function setCopyFrom($copyFrom)
   {
      $this->copyFrom = $copyFrom;
      return $this;
   }

   public function getContent()
   {
      return $this->content;
   }

   /**
    * @return \App\Site\CmMgr\Model\Article
    */
   public function setContent($content)
   {
      $this->content = $content;
      return $this;
   }

   public function getFileRefs()
   {
      return $this->fileRefs;
   }

   /**
    * @return \App\Site\CmMgr\Model\Article
    */
   public function setFileRefs($fileRefs)
   {
      $this->fileRefs = $fileRefs;
      return $this;
   }

   /**
    * @return null
    */
   public function getImgRefMap()
   {
      return unserialize($this->imgRefMap);
   }

   /**
    * @param null $imgRefMap
    */
   public function setImgRefMap($imgRefMap)
   {
      $this->imgRefMap = serialize($imgRefMap);
   }


}

