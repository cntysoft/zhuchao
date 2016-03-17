<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Product\Constant as P_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;
/**
 * 主要是处理采购商产品相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Product extends AbstractScript
{
   protected $tree = null;
   /**
    * 获取指定分类的子分类列表
    * 
    * @param array $params
    * @return array
    */
   public function getChildCategory(array $params)
   {
      $this->checkRequireFields($params, array('categoryId'));
      
      $list = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'getChildren',
         array((int)$params['categoryId'])
      );
      
      $ret =  array();
      foreach($list as $category){
         $item = array(
            'id' => $category->getId(),
            'text' => $category->getName()
         );
         
         array_push($ret, $item);
      }
      
      return $ret;
   }
   
   /**
    * 搜索分类
    * 
    * @param array $params
    */
   public function searchCategory(array $params)
   {
      $this->checkRequireFields($params, array('key'));
      
      $list = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'searchCategory',
         array($params['key'], CATEGORY_CONST::NODE_TYPE_DETAIL_CATEGORY, false, 'id DESC', 0, 0)
      );
      
      $ret = array();
      
      if(count($list)){
         foreach ($list as $category){
            $item = array();
            $this->getParentCategory($category->getId(), $item);
            $item = array_reverse($item);
            $ret[] = $item;
         }
      }
      
      return $ret;
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