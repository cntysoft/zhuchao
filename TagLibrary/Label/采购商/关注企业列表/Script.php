<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Buyer;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;

class Collection extends AbstractLabelScript
{
   protected $outputNum = 0;

   protected static $chinaArea = null;

   /**
    * 获取指定code的下级地区信息
    * 
    */
   public function getArea($code)
   {
      $chinaArea = $this->getChinaArea();
      if($code){
         return $chinaArea->getArea($code);
      }else{
         return '';
      }      
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
   
   public function getCollection()
   {
      $curUser = $this->getCurUser();
      $page = $this->getPageParam();

      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_COLLECT,
         'getCollectList',
         array(array('buyerId='.$curUser->getId()), true, 'id DESC', $page['offset'], $page['limit'])
      );
   }

   public function getPageUrl($pageId)
   {
      return '/product/'.$pageId.'.html';
   }
   
   /**
    * 获取信息分页参数
    *
    * @return array
    */
   protected function getPageParam()
   {
      $enablePage = $this->getParam('enablePage');
      $outputNum = $this->getOutputNum();
      if ($enablePage) {
         $routeInfo = $this->getRouteInfo();
         $pageId = isset($routeInfo['pageId']) && $routeInfo['pageId'] > 0 ? $routeInfo['pageId'] : 1;
         return array(
            'limit'  => $outputNum,
            'offset' => ($pageId - 1) * $outputNum
         );
      } else {
         return array(
            'limit'  => $outputNum,
            'offset' => 0
         );
      }
   }

   /**
    * 　获取分页相关的参数
    *
    * @param integer $total
    * @return array
    */
   public function getPaging($total)
   {
      $routeInfo = $this->getRouteInfo();
      $currentPage = isset($routeInfo['pageId']) && $routeInfo['pageId'] > 0 ? $routeInfo['pageId'] : 1;
      $num = $this->getOutputNum();
      $pageNum = (int) ceil($total / $num);
      $currentPage < $pageNum ? $currentPage : $pageNum;
      return array(
         'total'   => $pageNum,
         'current' => $currentPage
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
   
   public function getCurUser()
   {
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'getCurUser'
      );
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
   
   /**
    * 获取商品的网址
    * 
    * @param integer $id
    * @return string
    */
   public function getProduct($id)
   {
      return '/item/'.$id.'.html';
   }
}