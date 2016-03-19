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
use Cntysoft\Kernel;

class ProductChange extends AbstractLabelScript
{
   protected $units = array('件', '个', '台', '套', '箱', '吨', '公斤', '米', '平方米', '立方米', '千米', '克', '千克', '升', '毫升', '张', '提', '筐', '批', '盒', '桶', '片', '斤', '根', '头', '只', '毫克', '微克', '双', '支', '瓶', '卷','份', '包', '袋', '盆', '棵', '条', '瓦');
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
   
   /**
    * 获取cdn图片的地址
    * 
    * @param string $imgUrl
    * @param array $arguments
    * @return string
    */
   public function getImageCdnUrl($imgUrl, $arguments = array())
   {
      if($imgUrl){
         return Kernel\get_image_cdn_url_operate($imgUrl, $arguments);
      }else{
         return '/Statics/Skins/Pc/lazyicon.png';
      }
   }
   
}