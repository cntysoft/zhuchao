<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Provider;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;

class Category extends AbstractLabelScript
{
   protected $tree = null;
   
   /**
    * 获取指定分类的子分类
    * 
    * @param integer $categoryId
    */
   public function getChildCategory($categoryId)
   {
      $tree = $this->getNodeTree();
      $children = $tree->getChildren($categoryId, 1, true);
      $ret = array();
      if(count($children)){
         foreach($children as $one){
            $item = array(
               'id' => $one->getId(),
               'text' => $one->getName()
            );
            
            array_push($ret, $item);
         }
      }
      
      return $ret;
   }
   
   /**
    * 获取指定分类的属性信息
    * 
    * @param integer $categoryId
    * @return array
    */
   public function getCategoryaAttrs($categoryId)
   {
      return $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'getNodeAttrs',
         array($categoryId)
      );
   }
   
   /**
    * 获取指定分类的父分类信息
    * 
    * @param integer $categoryId
    * @param array $ret
    * @return array
    */
   public function getParentCategory($categoryId, &$ret)
   {
      $tree = $this->getNodeTree();
      if(!count($ret)){
         $category = $tree->getValue($categoryId);
         $ret[] = array(
            'id' => $category->getId(),
            'text' => $category->getName()
         );
      }
      $parent = $tree->getParent($categoryId, true);
      if(0 != $parent->getId()){
         $ret[] = array(
            'id' => $parent->getId(),
            'text' => $parent->getName()
         );
         return $this->getParentCategory($parent->getId(), $ret);
      }else{
         return $ret;
      }
   }
   
   public function getQuery()
   {
      return $this->di->get('request')->getQuery();
   }
   /**
    * 获取分类树
    * 
    * @return 
    */
   public function getNodeTree()
   {
      if(null == $this->tree){
         $this->tree = $this->appCaller->call(
            CATEGORY_CONST::MODULE_NAME,
            CATEGORY_CONST::APP_NAME,
            CATEGORY_CONST::APP_API_MGR,
            'getNodeTree'
         );
      }
      
      return $this->tree;
   }
}