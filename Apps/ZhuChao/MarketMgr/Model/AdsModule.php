<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MarketMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
class AdsModule extends BaseModel
{
   private $id;
   private $pid;
   private $name;
   
   public function getSource()
   {
      return 'app_zhuchao_marketmgr_ads_module';
   }
   public function getId()
   {
      return $this->id;
   }

   public function getPid()
   {
      return $this->pid;
   }

   public function getName()
   {
      return $this->name;
   }

   public function setId($id)
   {
      $this->id = $id;
   }

   public function setPid($pid)
   {
      $this->pid = $pid;
   }

   public function setName($name)
   {
      $this->name = $name;
   }


}