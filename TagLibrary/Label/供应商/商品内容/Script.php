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
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;

class ProductChange extends AbstractLabelScript
{
   protected $tree = null;
   /**
    * 获取指定商品的信息
    * 
    * @return 
    */
   public function getProduct()
   {
      $number = $this->getNumber();
      
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductByNumber',
         array($number)
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
   
   /**
    * 获取商品的编码
    * 
    * @return string
    */
   public function getNumber()
   {
      $number = '';
      if(isset($this->invokeParams['number']) && $this->invokeParams['number']){
         $number = $this->invokeParams['number'];
      }else{
         $routeInfo = $this->getRouteInfo();
         $number = $routeInfo['number'];
      }
      
      return $number;
   }
   
}