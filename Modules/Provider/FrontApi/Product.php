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
      $provider = $this->getCurUser();
      $company = $provider->getCompany();
      $companyId = $company ? $company->getId() : 0;
      $cndServer = Kernel\get_image_cdn_server_url() .'/';

      if(count($params['images'])){
         $images = $params['images'];
         $params['images'] = array();
         foreach ($images as $image){
            $item[0] = str_replace($cndServer, '', $image[0]);
            $item[1] = $image[1];
            $params['images'][] = $item;
         }
      }

      if(count($params['imgRefMap'])){
         $imgRefMap = $params['imgRefMap'];
         $params['imgRefMap'] = array();
         foreach ($imgRefMap as $image){
            $item[0] = str_replace($cndServer, '', $image[0]);
            $item[1] = $image[1];
            $params['imgRefMap'][] = $item;
         }
      }

      $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'addProduct',
         array($provider->getId(), $companyId, $params)
      );
      
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
      $this->checkRequireFields($params, array('ids'));
      $curUser = $this->getCurUser();
      $ids = $params['ids'];
      if(!is_array($ids)){
         $ids = array((int)$ids);
      }
      
      return $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'setProductDelete',
         array($curUser->getId(), $ids)
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
      $this->checkRequireFields($params, array('ids'));
      $curUser = $this->getCurUser();
      $ids = $params['ids'];
      if(!is_array($ids)){
         $ids = array((int)$ids);
      }
      
      return $this->appCaller->call(
         P_CONST::MODULE_NAME,
         P_CONST::APP_NAME,
         P_CONST::APP_API_PRODUCT_MGR,
         'setProductshelf',
         array($curUser->getId(), $ids, '自主下架')
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
      $curUser = $this->getCurUser();
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
      $provider = $this->getCurUser();
      
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
    * 获取当前登陆的供应商
    * 
    * @return 
    */
   public function getCurUser()
   {
      return $this->appCaller->call(
         PROVIDER_CONST::MODULE_NAME,
         PROVIDER_CONST::APP_NAME,
         PROVIDER_CONST::APP_API_MGR,
         'getCurUser'
      );
   }
}