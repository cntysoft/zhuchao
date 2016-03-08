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

class C2Map extends BaseModel
{
   private $categoryId;
   private $modelId;
   private $defaultTemplateFile;

   public function initialize()
   {
      $this->belongsTo(
         'categoryId',
         'App\Site\Category\Model\Node',
         'id',
         array(
            'alias' => 'node'
         )
      );
      $this->belongsTo(
         'modelId',
         'App\Site\CmMgr\Model\Content',
         'id',
         array(
            'alias' => 'contentModel'
         )
      );
   }

   public function getSource()
   {
      return 'join_category_content_model_template';
   }

   public function getCategoryId()
   {
      return (int)$this->categoryId;
   }

   public function getModelId()
   {
      return (int)$this->modelId;
   }

   public function getDefaultTemplateFile()
   {
      return $this->defaultTemplateFile;
   }

   public function setCategoryId($categoryId)
   {
      $this->categoryId = (int)$categoryId;
      return $this;
   }

   public function setModelId($modelId)
   {
      $this->modelId = (int)$modelId;
      return $this;
   }

   public function setDefaultTemplateFile($defaultTemplateFile)
   {
      $this->defaultTemplateFile = $defaultTemplateFile;
      return $this;
   }

}