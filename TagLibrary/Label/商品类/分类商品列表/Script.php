<?php
/**
 * Cntysoft OpenEngine
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use Cntysoft\Framework\Utils\ChinaArea;
use App\ZhuChao\Product\Constant as GOOD_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY;
/**
 * 商品详情页标签
 */
class ProductClassify extends AbstractLabelScript
{
   /**
    * @var object 当前登陆用户信息 
    */
   public $acl = null;
   /**
    * 保存中国省市区编码处理对象的静态属性
    * 
    * @var Cntysoft\Framework\Utils\ChinaArea
    */
   protected static $chinaArea = null;

   /**
    * 获取指定code的下级地区信息
    * 
    */
   public function getArea($company)
   {
      $chinaArea = $this->getChinaArea();
      $ret = '';
      if ($chinaArea->getArea((int) $company->getProvince()) != 'China Area Tree') {
         $ret.=$chinaArea->getArea((int) $company->getProvince());
      }
      if ($chinaArea->getArea((int) $company->getCity()) != 'China Area Tree') {
         $ret.=$ret == $chinaArea->getArea((int) $company->getCity()) ? '' : ' ' . $chinaArea->getArea((int) $company->getCity());
      }
      $ret.='';
      return $ret;
   }

   /**
    * 获取省市区管理对象
    * 
    * @return Cntysoft\Framework\Utils\ChinaArea
    */
   protected function getChinaArea()
   {
      if (null == self::$chinaArea) {
         self::$chinaArea = new ChinaArea();
      }

      return self::$chinaArea;
   }

   /**
    * 获取当前登陆用户信息
    * 
    * @return object 当前登陆用户信息 
    */
   public function getCurUser()
   {
      if (null == $this->acl) {
         $this->acl = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ACL, 'getCurUser');
      }
      return $this->acl;
   }

   /**
    * 获取广告位的位置信息
    * 
    * @param string $port 设备端
    * @param string $module 模块端
    * @param string $location 位置信息
    * @return integer 返回该位置的位置id
    */
   public function getAdsLocationId($port, $module, $location)
   {
      return $this->appCaller->call(
                      MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsLocationId', array($port, $module, $location));
   }

   /**
    * 根据位置id获取该位置下的广告
    * 
    * @param integer $locationId 广告位置id
    * @return object 广告列表对象
    */
   public function getAds($locationId)
   {
      $ads = $this->appCaller->call(
              MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsList', array($locationId, 'sort asc'));
      return $ads;
   }

   /**
    * 获取叶子节点列表信息
    * 
    * @return object 
    */
   public function getLeafNodes()
   {
      $nodes = $this->appCaller->call(
              CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getLeafNodes');
      return $nodes;
   }

   /**
    * 根据分类id获取该分类的信息
    * 
    * @param integer $categoryId 分类id
    * @return object 分类信息
    */
   public function getProductClassifyInfo($categoryId)
   {
      $info = $this->appCaller->call(
              CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNode', array($categoryId));
      return $info;
   }

   /**
    * 查询商品
    * 
    * @param integer $categoryId 分类id
    * @param integer $page 分页的page
    * @param ingeter $pageSize 每页的数量
    * @param array $attrFilter 查询属性
    * @return object 商品列表
    */
   public function queryGoods($categoryId = null, $page = null, $pageSize = null, array $attrFilter = array())
   {
      if (null == $categoryId) {
         $categoryId = $this->getRouteInfo()['categoryId'];
      }
      if (!$page) {
         $page = $this->getRouteInfo()['pageId'];
      }
      if (!$pageSize) {
         $pageSize = $this->invokeParams['outputNum'];
      }
      if (empty($attrFilter)) {
         $attrFilter = $this->getQueryFromUrl();
      }
      $sort = array(
         1 => 'inputTime',
         2 => 'hits',
         3 => 'grade',
         4 => 'price'
      );
      $type = array(
         1 => '-',
         2 => '+'
      );
      $orderBy = array();
      if (isset($attrFilter['sort'])) {
         $sortUrl = explode('_', $attrFilter['sort']);
         unset($attrFilter['sort']);
         if (array_key_exists($sortUrl[0], $sort) && array_key_exists($sortUrl[1], $type)) {
            $orderBy[$sort[$sortUrl[0]]] = $type[$sortUrl[1]];
         }
      }
      if (isset($attrFilter['价格'])) {
         $price = preg_split('/[^0-9]+/', $attrFilter['价格']);
         $attrFilter['price'] = array(
            0 => $price[0],
            1 => $price[1]
         );
         unset($attrFilter['价格']);
      }
      $attrFilter['status'] = GOOD_CONST::PRODUCT_STATUS_VERIFY;
      $goods = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_PRODUCT_MGR, 'queryGoods', array($categoryId, $page, $pageSize, $attrFilter, $orderBy));
      return $goods;
   }

   /**
    * 根据分类id获取该分类下的筛选属性
    * 
    * @param integer $categoryId 分类id
    * @return object
    */
   public function getQueryAttrs($categoryId)
   {
      $queryInfo = $this->appCaller->call(
              CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNodeQueryAttrs', array($categoryId));
      return $queryInfo;
   }

   /**
    * 获取URL上的查询信息
    * 
    * @return string
    */
   public function getQueryParams()
   {
      $ret = $this->di->get('request')->getQuery();
      unset($ret['_url']);
      $queryUrl = '?';
      foreach ($ret as $key => $value) {
         $queryUrl .= $key . '=' . $value . '&';
      }
      $queryUrl = substr($queryUrl, 0, strlen($queryUrl) - 1);
      return $queryUrl;
   }

   /**
    * 获取URL上的查询信息,数组形式
    * 
    * @return array
    */
   public function getQueryFromUrl()
   {
      $ret = $this->di->get('request')->getQuery();
      unset($ret['_url']);
      return $ret;
   }

   /**
    * 获取URL上的查询信息,数组形式
    * 
    * @return array
    */
   public function getNodeParents($nid)
   {
      $nodes = $this->appCaller->call(
              CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNodeTree');
      return $nodes->getParents($nid, true);
   }

   /**
    * 根据指定条件获取商品列表
    * 
    * @param array $cond 查询条件
    * @param boolean $total 是否分页
    * @param string $orderBy 排序方式
    * @param integer $offset 起始
    * @param ingeter $limit 每页多少个
    * @return object 商品对象列表
    */
   public function getGoodsListBy(array $cond = array(), $total = false, $orderBy = 'inputTime desc', $offset = 0, $limit = null)
   {
      if (null == $limit) {
         $limit = $this->invokeParams['outputNum'];
      }
		$cond = array_merge($cond, array("status = 3"));
      $goodsList = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_PRODUCT_MGR, 'getProductList', array($cond, $total, $orderBy, $offset, $limit));
      return $goodsList;
   }

   /**
    * 返回当前登陆用户是否收藏了该商品
    * 
    * @param integer $gid
    * @return boolean
    */
   public function isSetFavorite($gid)
   {
      $curUser = $this->getCurUser();
      $bool = $this->appCaller->call(
              USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_FAVORITES, 'checkFavoritesExist', array($curUser->getId(), $gid, 1));
      return $bool;
   }

   /**
    * 获取图片，无图使用默认图片
    * 
    * @param integer $img
    * @return string
    */
   public function getImgUrl($img, $width, $height)
   {
      return $img ? \Cntysoft\Kernel\get_image_cdn_url($img, $width, $height) : '/Statics/Images/Global/lazyicon.png';
   }

   public function getProductUrl($number)
   {
      return '/item/'.$number.'.html';
   }
}