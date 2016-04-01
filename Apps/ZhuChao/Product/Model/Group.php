<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Group extends BaseModel
{
   protected $id;
   protected $pid;
   protected $providerId;
   protected $name;
   protected $inputTime;
   
   public function getSource()
   {
      return 'app_zhuchao_product_group';
   }
   
   public function initialize()
   {
      $this->hasMany('id', 'App\ZhuChao\Product\Model\Product2Group', 'groupId', array(
         'alias' => 'p2g'
      ));
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getPid()
   {
      return (int)$this->pid;
   }

   public function getProviderId()
   {
      return (int)$this->providerId;
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

   public function setProviderId($providerId)
   {
      $this->providerId = (int)$providerId;
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
