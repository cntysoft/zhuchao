<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Setting\Model;
use ZhuChao\Phalcon\Mvc\Model as BaseModel;
/**
 * 系统配置模型
 */
class Config extends BaseModel
{
   private $key;
   private $value;
   private $group;

   public function getSource()
   {
      return 'sys_m_std_config';
   }

   public function getKey()
   {
      return $this->key;
   }

   public function getValue()
   {
      return $this->value;
   }

   public function getGroup()
   {
      return $this->group;
   }

   public function setKey($key)
   {
      $this->key = $key;
      return $this;
   }

   public function setValue($value)
   {
      $this->value = $value;
      return $this;
   }

   public function setGroup($group)
   {
      $this->group = $group;
      return $this;
   }

}