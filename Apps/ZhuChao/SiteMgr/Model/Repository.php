<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\SiteMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Repository extends BaseModel
{
   private $id;
   private $name;
   private $level;

   public function getSource()
   {
      return 'app_zhuchao_sitemgr_site';
   }

   public function getId()
   {
      return (int) $this->id;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getLevel()
   {
      return (int) $this->level;
   }

   public function setId($id)
   {
      $this->id = (int) $id;
      return $this;
   }

   public function setName($name)
   {
      $this->name = $name;
      return $this;
   }

   public function setLevel($level)
   {
      $this->level = (int) $level;
      return $this;
   }

}