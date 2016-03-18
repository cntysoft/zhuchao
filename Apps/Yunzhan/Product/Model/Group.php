<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\Yunzhan\Product\Model;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;

class Group extends BaseModel
{
   protected $id;
   protected $pid;
   protected $name;
   protected $inputTime;
   
   public function getSource()
   {
      return 'app_zhuchao_product_group';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getPid()
   {
      return (int)$this->pid;
   }

   public function getName()
   {
      return $this->name;
   }

   public function getInputTime()
   {
      return (int)$this->inputTime;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setPid($pid)
   {
      $this->pid = (int)$pid;
   }

   public function setName($name)
   {
      $this->name = $name;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

}
