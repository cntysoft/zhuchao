<?php
/**
 * Cntysoft OpenEngine
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\Sys\Searcher\Constant as SEARCH_CONST;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;
/**
 * 信息搜索
 */
class Search extends AbstractLabelScript
{
   protected $outputNum = null;
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
    * 获取并处理搜索的各种参数
    */
   public function getKeys()
   {
      $request = $this->di->get('request');
      $keys = $request->getQuery();
      $routeInfo = $this->getRouteInfo();
      $page = (int)$routeInfo['pageId'] > 0 ? (int)$routeInfo['pageId'] - 1 : 0;
      unset($keys['_url']);
      $ret = array('page' => $page, 'size' => 12, 'sort' => array(), 'keyword' => '');
      $ret['size'] = $this->invokeParams['outputNum'];
      
      $sort = array(
         1 => 'inputtime',
         2 => 'hits',
         3 => 'price',
         4 => 'grade'
      );
      $type = array(
         1 => '-',
         2 => '+'
      );

      if (isset($keys['sort'])) {
         $sortUrl = explode('_', $keys['sort']);
         if (array_key_exists($sortUrl[0], $sort) && array_key_exists($sortUrl[1], $type)) {
            $ret['sort'] = array(
               'key' => $sort[$sortUrl[0]],
               'type' => $type[$sortUrl[1]]
            );
         }
         unset($keys['sort']);
      }else{
         $ret['sort'] = array(
            'key' => 'grade',
            'type' => '-'
         );
      }

      if (isset($keys['价格'])){
         $price = preg_split('/[^0-9]+/', $keys['价格']);

         if(!isset($price[1]) && strpos($keys['价格'], '以上')){
            $price[1] = 99999999;
         }else if(!isset($price[1]) && strpos($keys['价格'], '以下')){
            $price[1] = $price[0];
            $price[0] = 0;
         }else{
            $price[1] = 99999999;
         }
         $keys['price'] = array(
            0 => (int)$price[0],
            1 => (int)$price[1]
         );
         unset($keys['价格']);
      }

      if(isset($keys['keyword'])){
         $ret['keyword'] = $keys['keyword'];
         unset($keys['keyword']);
      }

      $ret['filter'] = $keys;

      return $ret;
   }
   
   public function searchProduct()
   {
      $keys = $this->getKeys();
      
      return $this->appCaller->call(
         SEARCH_CONST::MODULE_NAME, 
         SEARCH_CONST::APP_NAME, 
         SEARCH_CONST::APP_API_SEARCHER, 
         'query', 
         array($keys['keyword'], $keys['page'], $keys['size'], $keys['filter'], $keys['sort'], true)
      );
   }
   
   public function getQueryUrl()
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
   
   public function getGoodsStdAttrs($gid)
   {
      $goodsStdAttrs = $this->appCaller->call(
              PRODUCT_CONST::MODULE_NAME, 
              PRODUCT_CONST::APP_NAME, 
              PRODUCT_CONST::APP_API_PRODUCT_MGR, 
              'getGoodsStdAttrs', 
              array($gid));
      return $goodsStdAttrs;
   }
   
   /**
    * 获取分页相关的参数
    *
    * @param integer $total
    * @return array
    */
   public function getPaging($total)
   {
      $routeInfo = $this->getRouteInfo();
      $currentPage = isset($routeInfo['pageId']) ? $routeInfo['pageId'] : 1;
      $num = $this->getOutputNum();
      $pageNum = (int) ceil($total / $num);
      $currentPage < $pageNum ? $currentPage : $pageNum;
      return array(
         'total'          => $pageNum,
         'current'        => $currentPage
      );
   }
   
   /**
    * 
    * @param string $number
    * @return string
    */
   public function getProductUrl($number)
   {
      return '/item/' . $number . '.html';
   }
   
   /**
    * 获取图片的地址
    * 
    * @param string $url
    * @parma array $params
    */
   public function getImageCdnUrl($url, $params = array())
   {
      if($url){
         $params += array('c' => 1, 'e' => 1);
         return Kernel\get_image_cdn_url_operate($url, $params);
      }else{
         return 'Statics/Skins/Pc/Images/lazyicon.png';
      }
   }
   
   public function getProduct($id)
   {
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME, 
         PRODUCT_CONST::APP_NAME, 
         PRODUCT_CONST::APP_API_PRODUCT_MGR, 
         'getProductById', 
         array($id)
      );
   }
   
   /**
    * 获取列表的输出数
    *
    * @return integer
    */
   public function getOutputNum()
   {
      if (null == $this->outputNum) {
         if (!isset($this->invokeParams['outputNum'])) {
            $this->outputNum = 15;
         } else {
            $this->outputNum = $this->invokeParams['outputNum'];
         }
      }

      return $this->outputNum;
   }
   
   public function getPageUrl($pageId)
   {
      return '/query/'.$pageId.'.html';
   }
}
