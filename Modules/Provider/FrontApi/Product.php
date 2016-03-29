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
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use App\Yunzhan\Product\Constant as YUN_P_CONST;
use Cntysoft\Kernel;
/**
 * 主要是处理采购商产品相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Product extends AbstractScript
{
   protected $categoryTree = null;
   
   /**
    * 添加一个商品
    * 
    * @param array $params
    * @return 
    */
   public function addProduct(array $params)
   {
      $this->checkRequireFields($params, array('categoryId', 'brand', 'title', 'description', 'advertText', 'keywords', 'attribute', 'unit', 'minimum', 'stock', 'price', 'isBatch', 'images', 'introduction', 'imgRefMap', 'fileRefs', 'status'));
      $provider = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $company = $provider->getCompany();
      if(!$company || !$company->getSubAttr()){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')));
      }
      $companyId = $company ? $company->getId() : 0;
      $cndServer = Kernel\get_image_cdn_server_url() .'/';
      $src = '@.src';
      
      if(count($params['images'])){
         $images = $params['images'];
         $params['images'] = array();
         foreach ($images as $image){
            $item[0] = str_replace($src, '', str_replace($cndServer, '', $image[0]));
            $item[1] = $image[1];
            $params['images'][] = $item;
         }
      }

      if(count($params['imgRefMap'])){
         $imgRefMap = $params['imgRefMap'];
         $params['imgRefMap'] = array();
         foreach ($imgRefMap as $image){
            $item[0] = str_replace($src, '', str_replace($cndServer, '', $image[0]));
            $item[1] = $image[1];
            $params['imgRefMap'][] = $item;
         }
      }
      
      $status = 1;
      if(P_CONST::PRODUCT_STATUS_VERIFY == $params['status']){
         $status = $params['status'];
         $params['status'] = P_CONST::PRODUCT_STATUS_PEEDING;
      }
      $product = $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'addProduct',
         array($provider->getId(), $companyId, $params)
      );
      
      $params['status'] = $status;
      $params['number'] = $product->getNumber();
      //插入到商家云站数据库中
      $this->appCaller->call(
         YUN_P_CONST::MODULE_NAME,
         YUN_P_CONST::APP_NAME,
         YUN_P_CONST::APP_API_PRODUCT_MGR,
         'addProduct',
         array($params)
      );
   }
   
   
   /**
    * 添加一个商品
    * 
    * @param array $params
    * @return 
    */
   public function updateProduct(array $params)
   {
      $this->checkRequireFields($params, array('number', 'brand', 'title', 'description', 'advertText', 'keywords', 'attribute', 'unit', 'minimum', 'stock', 'price', 'isBatch', 'images', 'introduction', 'imgRefMap', 'fileRefs', 'status'));
      $provider = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $company = $provider->getCompany();
      if(!$company || !$company->getSubAttr()){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')));
      }
      $companyId = $company ? $company->getId() : 0;
      $cndServer = Kernel\get_image_cdn_server_url() .'/';
      $params['companyId'] = $companyId;
      $src = '@.src';
      if(count($params['images'])){
         $images = $params['images'];
         $params['images'] = array();
         foreach ($images as $image){
            $item[0] = str_replace($src, '', str_replace($cndServer, '', $image[0]));
            $item[1] = $image[1];
            $params['images'][] = $item;
         }
      }

      if(count($params['imgRefMap'])){
         $imgRefMap = $params['imgRefMap'];
         $params['imgRefMap'] = array();
         foreach ($imgRefMap as $image){
            $item[0] = str_replace($src, '', str_replace($cndServer, '', $image[0]));
            $item[1] = $image[1];
            $params['imgRefMap'][] = $item;
         }
      }

      $fhproduct = $this->getFhProductByNumber($params['number']);
      if(!$fhproduct){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_PRODUCT_MGR_NOT_EXIST'), $errorType->code('E_PRODUCT_MGR_NOT_EXIST')
         ));
      }
      $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'updateProduct',
         array($fhproduct->getId(), $params)
      );
      
      $shopprodcut = $this->getShopProductByNumber($params['number']);
      if($shopprodcut){
         //插入到商家云站数据库中
         $this->appCaller->call(
            YUN_P_CONST::MODULE_NAME,
            YUN_P_CONST::APP_NAME,
            YUN_P_CONST::APP_API_PRODUCT_MGR,
            'updateProduct',
            array($shopprodcut->getId(), $params)
         );
      }
   }
   
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
    * 删除指定的产品
    * 
    * @param array $params
    * @return boolean
    */
   public function deleteProduct(array $params)
   {
      $this->checkRequireFields($params, array('numbers'));
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $numbers = $params['numbers'];
      if(!is_array($numbers)){
         $numbers = array($numbers);
      }
      
      $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($curUser->getId(), $numbers, P_CONST::PRODUCT_STATUS_DELETE,'')
      );
      
      $this->appCaller->call(
         YUN_P_CONST::MODULE_NAME,
         YUN_P_CONST::APP_NAME,
         YUN_P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($numbers, YUN_P_CONST::PRODUCT_STATUS_DELETE)
      );
   }
   
   /**
    * 下架选中的商品
    * 
    * @param array $params
    * @return 
    */
   public function shelfProduct(array $params)
   {
      $this->checkRequireFields($params, array('numbers'));
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      
      $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($curUser->getId(), $params['numbers'], P_CONST::PRODUCT_STATUS_SHELF, '自主下架')
      );
      
      $this->appCaller->call(
         YUN_P_CONST::MODULE_NAME,
         YUN_P_CONST::APP_NAME,
         YUN_P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($params['numbers'], YUN_P_CONST::PRODUCT_STATUS_SHELF)
      );
   }
   
   /**
    * 上架商品
    * 
    * @param array $params
    * @return 
    */
   public function upshelfProduct(array $params)
   {
      $this->checkRequireFields($params, array('numbers'));
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      
      $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($curUser->getId(), $params['numbers'], P_CONST::PRODUCT_STATUS_VERIFY, '')
      );
      
      $this->appCaller->call(
         YUN_P_CONST::MODULE_NAME,
         YUN_P_CONST::APP_NAME,
         YUN_P_CONST::APP_API_PRODUCT_MGR,
         'changeProductsStauts',
         array($params['numbers'], YUN_P_CONST::PRODUCT_STATUS_VERIFY)
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
      $tree = $this->getCategoryTree();
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
    * 获取指定采购商的商品分组信息，支持两层结构
    */
   public function getProviderGroup()
   {
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $groupTree = $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_GROUP_MGR,
         'getGroupTree',
         array($curUser->getId())
      );
      $ret = array();
      $firstList = $groupTree->getChildren(0, 1, true);
      
      foreach($firstList as $group){
         $twsList = $groupTree->getChildren($group->getId(), 1, true);
         $two = array();
         if(count($twsList)){
            foreach($twsList as $two){
               $item = array(
                  'id' => $two->getId(),
                  'name' => $two->getName()
               );
               
               $two[] = $item;
            }
         }
         
         $ret[] = array(
            'id' => $group->getId(),
            'name' => $group->getName(),
            'children' => $two
         );
      }
      
      return $ret;
   }
   
   /**
    * 添加一个分组信息
    * 
    * @param array $params
    * @return boolean
    */
   public function addGroup(array $params)
   {
      $this->checkRequireFields($params, array('name'));
      $provider = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      
      if(isset($params['pid'])){
         $params['pid'] = (int)$params['pid'];
      }else{
         $params['pid'] = 0;
      }
      
      return $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_GROUP_MGR,
         'addGroup',
         array($provider->getId(), $params)
      );
   }
   
   /**
    * 获取分类树
    * 
    * @return 
    */
   public function getCategoryTree()
   {
      if(null == $this->tree){
         $this->categoryTree = $this->appCaller->call(
            CATEGORY_CONST::MODULE_NAME,
            CATEGORY_CONST::APP_NAME,
            CATEGORY_CONST::APP_API_MGR,
            'getNodeTree'
         );
      }
      
      return $this->categoryTree;
   }
   
   /**
    * 获取指定编码的产品信息
    * 
    * @param string $number
    * @return 
    */
   protected function getFhProductByNumber($number)
   {
      return $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'getProductByNumber',
         array($number)
      );
   }
   
   /**
    * 获取指定编码的产品信息
    * 
    * @param string $number
    * @return 
    */
   protected function getShopProductByNumber($number)
   {
      return $this->appCaller->call(
         YUN_P_CONST::MODULE_NAME,
         YUN_P_CONST::APP_NAME,
         YUN_P_CONST::APP_API_PRODUCT_MGR,
         'getProductByNumber',
         array($number)
      );
   }
}