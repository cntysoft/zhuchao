<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\CategoryMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Category extends BaseModel
{
   protected $id;
   protected $pid;
   protected $name;
   protected $createTime;
   protected $nodeType;
   protected $identifier;
   protected $img;

   public function getSource()
   {
      return 'app_zhuchao_categorymgr_goods_category';
   }

   public function initialize()
   {
      //普通属性
      $this->hasMany('id', 'App\ZhuChao\CategoryMgr\Model\CategoryAttrs', 'nodeId', array(
         'alias' => 'attrs'
      ));
      //查询属性
      $this->hasMany('id', 'App\ZhuChao\CategoryMgr\Model\CategoryQueryAttrs', 'categoryId', array(
         'alias' => 'queryAttrs'
      ));
      //规格
      $this->hasMany('id', 'App\ZhuChao\CategoryMgr\Model\CategoryStdAttrs', 'nodeId', array(
         'alias' => 'stdAttrs'
      ));
   }

   /**
    * @return mixed
    */
   public function getId()
   {
      return (int) $this->id;
   }

   /**
    * @param mixed $id
    */
   public function setId($id)
   {
      $this->id = (int) $id;
   }

   /**
    * @return mixed
    */
   public function getPid()
   {
      return (int) $this->pid;
   }

   /**
    * @param mixed $pid
    */
   public function setPid($pid)
   {
      $this->pid = (int) $pid;
   }

   /**
    * @return mixed
    */
   public function getName()
   {
      return $this->name;
   }

   /**
    * @param mixed $name
    */
   public function setName($name)
   {
      $this->name = $name;
   }

   /**
    * @return mixed
    */
   public function getCreateTime()
   {
      return (int) $this->createTime;
   }

   /**
    * @param mixed $createTime
    */
   public function setCreateTime($createTime)
   {
      $this->createTime = (int) $createTime;
   }

   /**
    * @return mixed
    */
   public function getNodeType()
   {
      return (int) $this->nodeType;
   }

   /**
    * @param mixed $nodeType
    */
   public function setNodeType($nodeType)
   {
      $this->nodeType = (int) $nodeType;
   }

   public function getIdentifier()
   {
      return $this->identifier;
   }

   public function getImg()
   {
      return $this->img;
   }

   public function setIdentifier($identifier)
   {
      $this->identifier = $identifier;
      return $this;
   }

   public function setImg($img)
   {
      $this->img = $img;
      return $this;
   }

}