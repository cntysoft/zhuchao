<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderMobileApi;
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
      $this->checkRequireFields($params, array('categoryId', 'brand', 'title', 'description', 'advertText', 'keywords', 'attribute', 'unit', 'minimum', 'stock', 'price', 'isBatch', 'images', 'introduction', 'status'));
      $provider = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $company = $provider->getCompany();
      if(!$company || !$company->getSubAttr()){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')));
      }
      $companyId = $company ? $company->getId() : 0;
      $cndServer = Kernel\get_image_cdn_server_url() . '/';

      $fileRefs = array();
      $params['defaultImage'] = $params['images'][0]['url'];
      if (count($params['images'])) {
         $images = $params['images'];
         $params['images'] = array();
         foreach ($images as $image) {
            $result = explode('@', str_replace($cndServer, '', $image['url']));
            $item[0] = $result[0];
            $item[1] = $image['id'];
            $fileRefs[] = $image['id'];
            $params['images'][] = $item;
         }
      }
      $params['fileRefs'] = $fileRefs;
      $params['imgRefMap'] = array();
      $params['keywords']= explode(',', $params['keywords']);
      if (count($params['attribute'])) {
         $attrs = $params['attribute'];
         $attribute = array(
            '基本参数'  => array(),
            '自定义属性' => array()
         );
          foreach ($attrs as $attr) {
            if (!isset($attr['value'])) {
               $attr['value'] = '';
            }
            if ($attr['type'] == 1) {
              $attribute['基本参数'][$attr['name']] = $attr['value'];
            } else {
              $attribute['自定义属性'][$attr['name']] = $attr['value'];
            }
         }
          $params['attribute'] = $attribute;  
      }

      $status = 1;
      if(P_CONST::PRODUCT_STATUS_VERIFY == $params['status']){
         $status = $params['status'];
         $params['status'] = P_CONST::PRODUCT_STATUS_PEEDING;
      }
      $product = $this->appCaller->call(
            P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'addProduct', array($provider->getId(), $companyId, $params)
      );
      
      $params['number'] = $product->getNumber();
      $params['status'] = $status;
      //插入到商家云站数据库中
      $this->appCaller->call(
              YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'addProduct', array($params)
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
      $this->checkRequireFields($params, array('number', 'brand', 'title', 'description', 'advertText', 'keywords', 'attribute', 'unit', 'minimum', 'stock', 'price', 'isBatch', 'images', 'introduction', 'status'));
      $product = $this->appCaller->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($params['number'])
      );
      $fhproduct = $product->getDetail();
      if (!$fhproduct) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PRODUCT_MGR_NOT_EXIST'), $errorType->code('E_PRODUCT_MGR_NOT_EXIST')
         ));
      }
      $provider = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $company = $provider->getCompany();
      if(!$company || !$company->getSubAttr()){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')));
      }
      $companyId = $company ? $company->getId() : 0;
      $cndServer = Kernel\get_image_cdn_server_url() . '/';
      $params['companyId'] = $companyId;
      $params['providerId'] = $provider->getId();
      $params['keywords']= explode(',', $params['keywords']);
      $params['defaultImage'] = $params['images'][0]['url'];

      $params['fileRefs'] = array();
      $imgRefMap = $fhproduct->getImgRefMap();
      if (count($params['images'])) {
         $images = $params['images'];
         $params['images'] = array();
         foreach ($images as $image) {
            $result = explode('@', str_replace($cndServer, '', $image['url']));
            $item[0] = $result[0];
            $item[1] = $image['id'];
            array_push($params['fileRefs'], $item[1]);
            $params['images'][] = $item;
         }
      }
      if (count($imgRefMap)) {
         $params['imgRefMap'] = array();
         foreach ($imgRefMap as $image) {
            $result = explode('@', str_replace($cndServer, '', $image[0]));
            $item[0] = $result[0];
            $item[1] = $image[1];
            array_push($params['fileRefs'], $item[1]);
            $params['imgRefMap'][] = $item;
         }
      }

      if (count($params['attribute'])) {
         $attrs = $params['attribute'];
         $attribute = array(
            '基本参数'  => array(),
            '自定义属性' => array()
         );
         foreach ($attrs as $attr) {
             if(!isset($attr['value'])){
                 $attr['value'] = '';
             }
            if ($attr['type'] == 1) {
              $attribute['基本参数'][$attr['name']] = $attr['value'];
            } else {
              $attribute['自定义属性'][$attr['name']] = $attr['value'];
            }
         }
          $params['attribute'] = $attribute;  
      }
      $status = 1;
      if(P_CONST::PRODUCT_STATUS_VERIFY == $params['status']){
         $status = $params['status'];
         $params['status'] = P_CONST::PRODUCT_STATUS_PEEDING;
      }
      $this->appCaller->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'updateProduct', array($product->getId(), $params)
      );
      $shopprodcut = $this->getShopProductByNumber($params['number']);

      if ($shopprodcut) {
         $params['status'] = $status;
         //插入到商家云站数据库中
         $this->appCaller->call(
                 YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'updateProduct', array($shopprodcut->getId(), $params)
         );
      }else{
         $this->appCaller->call(
              YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'addProduct', array($params)
         );
      }
   }

   /**
    * 获取指定分类的子分类列表
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryListByPid(array $params)
   {
      $this->checkRequireFields($params, array('pId'));

      $list = $this->appCaller->call(
              CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getChildren', array((int) $params['pId'])
      );

      $ret = array();
      foreach ($list as $category) {
         if ($category->getNodeType() == 1) {
            $categoryChildren = $this->appCaller->call(
                    CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getChildren', array((int) $category->getId())
            );
            if (count($categoryChildren) == 0) {
               continue;
            }
         }

         $item = array(
            'id'   => $category->getId(),
            'name' => $category->getName(),
            'pId'  => $category->getPId()
         );
         if ($category->getNodeType() == 2) {
            $item['leaf'] = true;
         } else {
            $item['leaf'] = false;
         }
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
              CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'searchCategory', array($params['key'], CATEGORY_CONST::NODE_TYPE_DETAIL_CATEGORY, false, 'id DESC', 0, 0)
      );

      $ret = array();

      if (count($list)) {
         foreach ($list as $category) {
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
      $this->checkRequireFields($params, array('numberList'));
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $numbers = $params['numberList'];
      if (!is_array($numbers)) {
         $numbers = array($numbers);
      }

      $this->appCaller->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'changeProductsStauts', array($curUser->getId(), $numbers, P_CONST::PRODUCT_STATUS_DELETE, '')
      );

      $this->appCaller->call(
              YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'changeProductsStauts', array($numbers, YUN_P_CONST::PRODUCT_STATUS_DELETE)
      );
   }

   /**
    * 修改商品状态
    * @param array $params
    * @return 
    */
   public function changeProductStatus(array $params)
   {
      $this->checkRequireFields($params, array('numberList', 'status'));
      $curUser = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $comment = '';
      $status = (int) $params['status'];
      if ($status == 5) {
         $comment = '自主下架';
      }
      $this->appCaller->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'changeProductsStauts', array($curUser->getId(), $params['numberList'], $status, $comment)
      );
      $this->appCaller->call(
              YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'changeProductsStauts', array($params['numberList'], $status)
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
      if (!count($ret)) {
         $category = $tree->getValue($categoryId);
         $ret[] = array(
            'id'   => $category->getId(),
            'text' => $category->getName()
         );
      }
      $parent = $tree->getParent($categoryId, true);
      if (0 != $parent->getId()) {
         $ret[] = array(
            'id'   => $parent->getId(),
            'text' => $parent->getName()
         );
         return $this->getParentCategory($parent->getId(), $ret);
      } else {
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
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_GROUP_MGR, 'getGroupTree', array($curUser->getId())
      );
      $ret = array();
      $firstList = $groupTree->getChildren(0, 1, true);

      foreach ($firstList as $group) {
         $twsList = $groupTree->getChildren($group->getId(), 1, true);
         $two = array();
         if (count($twsList)) {
            foreach ($twsList as $two) {
               $item = array(
                  'id'   => $two->getId(),
                  'name' => $two->getName()
               );

               $two[] = $item;
            }
         }

         $ret[] = array(
            'id'       => $group->getId(),
            'name'     => $group->getName(),
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

      if (isset($params['pid'])) {
         $params['pid'] = (int) $params['pid'];
      } else {
         $params['pid'] = 0;
      }

      return $this->appCaller->call(
                      P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_GROUP_MGR, 'addGroup', array($provider->getId(), $params)
      );
   }

   /**
    * 获取分类树
    * 
    * @return 
    */
   public function getCategoryTree()
   {
      if (null == $this->tree) {
         $this->categoryTree = $this->appCaller->call(
                 CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getNodeTree'
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
   public function getProductByNumber($params)
   {
      $this->checkRequireFields($params, array('number'));
      $product = $this->appCaller->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($params['number'])
      );
      $detail = $product->getDetail();
      $detail = $detail->toarray();
      $product = $product->toArray();
      $info = array_merge($detail, $product);
      $info['images'] = unserialize($info['images']);
      $info['attribute'] = unserialize($info['attribute']);
      foreach ($info['images'] as $key => $image) {
         $info['images'][$key]['cdn'] = \Cntysoft\Kernel\get_image_cdn_url($image[0]);
         $info['images'][$key]['id'] = $image[1];
         $info['images'][$key]['base'] = \Cntysoft\Kernel\get_image_cdn_server_url();
         $info['images'][$key]['url'] = $image[0];
         unset($info['images'][$key][0]);
         unset($info['images'][$key][1]);
      }
      $attrs = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getNodeNormalAttrs', array($product['categoryId']));
      $retAttrs = array();
      foreach ($attrs as $key => $attr) {
         array_push($retAttrs, array(
            'name'     => $attr->getName(),
            'val'      => explode(',', $attr->getOptValue()),
            'required' => $attr->getRequired(),
            'type'     => 1,
            'value'    => $info['attribute']['基本参数'][$attr->getName()],
            'multi'    => false
         ));
      }

      foreach ($info['attribute']['自定义属性'] as $key => $value) {
         array_push($retAttrs, array(
            'name'  => $key,
            'value' => $value,
            'type'  => 2
         ));
      }
      $info['attribute'] = $retAttrs;
      $info['categoryName'] = $this->getAllParentName($info['categoryId']);
      unset($info['id']);
      unset($info['imgRefMap']);
      unset($info['fileRefs']);
      unset($info['detailId']);
      unset($info['searchAttrMap']);
      unset($info['indexGenerated']);
      unset($info['grade']);
      unset($info['star']);
      unset($info['inputTime']);
      unset($info['updateTime']);
      unset($info['comment']);
      unset($info['providerId']);
      unset($info['companyId']);
      unset($info['defaultImage']);
      unset($info['hits']);
      return $info;
   }

   private function getAllParentName($cid)
   {
      $ret = array();
      $this->getParentCategory($cid, $ret);
      $text = '';
      foreach ($ret as $category) {
         $text = $category['text'] . '/' . $text;
      }
      return substr($text, 0, strlen($text) - 1);
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
                      YUN_P_CONST::MODULE_NAME, YUN_P_CONST::APP_NAME, YUN_P_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number)
      );
   }

   /**
    * 根据状态获得当前供应商商品列表
    * @param type $params
    * @return array
    * 
    */
   public function getProductListByStatus($params)
   {
      $cond = array();
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      $page = (int) $params['page'];
      $page >= 1 ? '' : $page = 1;
      $pageSize = (int) $params['pageSize'];
      $pageSize >= 1 ? $pageSize : $pageSize = 1;
      $user = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $cond = 'providerId =' . $user->getId() . ' and status=' . $params['status'];
      $offset = ($page - 1) * $pageSize;
      $products = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'getProductList', array(array($cond), true, 'id DESC', $offset, $pageSize));
      $ret = array();
      foreach ($products[0] as $item) {
         array_push($ret, array(
            "id"           => $item->getId(),
            "number"       => $item->getNumber(),
            "brand"        => $item->getBrand(),
            "title"        => $item->getTitle(),
            "description"  => $item->getDescription(),
            "defaultImage" => \Cntysoft\Kernel\get_image_cdn_url($item->getDefaultImage()),
            "price"        => $item->getPrice(),
            "comment"      => $item->getComment(),
            "status"       => $item->getStatus(),
            "url"          => 'http://' . \Cntysoft\RT_SYS_SITE_NAME . '/item/' . $item->getNumber() . '.html'
         ));
      }
      return $ret;
   }

   /**
    * 根据分类id获得分类属性
    * @param type $params
    */
   public function getCategoryAttrsById($params)
   {
      $this->checkRequireFields($params, array('nodeId'));
      $attrs = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getNodeNormalAttrs', array($params['nodeId']));
      $ret = array();
      foreach ($attrs as $attr) {
         $ret[] = array(
            'name'     => $attr->getName(),
            'val'      => explode(',', $attr->getOptValue()),
            'required' => $attr->getRequired(),
            'type'     => 1
         );
      }
      return $ret;
   }

   public function getProductListByCid($params)
   {
      $this->checkRequireFields($params, array('cId', 'page', 'pageSize', 'status'));
      $cond = array();
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      $page = (int) $params['page'];
      $page >= 1 ? '' : $page = 1;
      $pageSize = (int) $params['pageSize'];
      $pageSize >= 1 ? $pageSize : $pageSize = 1;
      $user = $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser');
      $cond = 'providerId=' . $user->getId() . ' and status=' . $params['status'] . ' and categoryId=' . $params['cId'];
      $offset = ($page - 1) * $pageSize;
      $products = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_PRODUCT_MGR, 'getProductList', array(array($cond), true, 'id DESC', $offset, $pageSize));
      $ret = array();
      foreach ($products[0] as $item) {
         array_push($ret, array(
            "id"           => $item->getId(),
            "number"       => $item->getNumber(),
            "brand"        => $item->getBrand(),
            "title"        => $item->getTitle(),
            "description"  => $item->getDescription(),
            "defaultImage" => \Cntysoft\Kernel\get_image_cdn_url($item->getDefaultImage()),
            "price"        => $item->getPrice(),
            "comment"      => $item->getComment(),
            "status"       => $item->getStatus()
         ));
      }
      return $ret;
   }

}