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
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use Cntysoft\Kernel;

class ProductList extends AbstractLabelScript
{
   protected $outputNum = 0;
   protected $statusEnable = array(
      PRODUCT_CONST::PRODUCT_STATUS_PEEDING,
      PRODUCT_CONST::PRODUCT_STATUS_VERIFY,
      PRODUCT_CONST::PRODUCT_STATUS_REJECTION,
      PRODUCT_CONST::PRODUCT_STATUS_SHELF,
   );

   public function getProductList()
   {
      $curUser = $this->getCurUser();
      $page = $this->getPageParam();
      $query = $this->getQuery();
      $cond = array();
      
      $cond[] = 'providerId='.$curUser->getId();
      if(isset($query['keyword']) && $query['keyword']){
         $cond[] = "(brand like '%".$query['keyword']."%' or title like '%".$query['keyword']."%' or description like '%".$query['keyword']."%' )";
      }
      $status = $this->getStatus();
      $cond[] = 'status='.$status;
      $queryCond = array(implode(' and ', $cond));

      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductList',
         array($queryCond, true, 'id DESC', $page['offset'], $page['limit'])
      );
   }
   
   public function getStatus()
   {
      $status = PRODUCT_CONST::PRODUCT_STATUS_VERIFY;
      $query = $this->getQuery();
      if(isset($query['status']) && $query['status']){
         if(in_array($query['status'], $this->statusEnable)){
            $status = (int)$query['status'];
         }
      }
      
      return $status;
   }
   
   public function getPageUrl($pageId)
   {
      return '/product/'.$pageId.'.html';
   }
   
   /**
    * 获取该供应商全部的商品
    * 
    * @return type
    */
   public function getProductAll()
   {
      $curUser = $this->getCurUser();
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductList',
         array(array('providerId='.$curUser->getId()), false, 'id DESC', 0, 0)
      );
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
   
   /**
    * 获取网址中的查询信息
    * 
    * @return array
    */
   public function getQuery()
   {
      return $this->di->get('request')->getQuery();
   }
   
   public function getCurUser()
   {
      return $this->appCaller->call(
         PROVIDER_CONST::MODULE_NAME,
         PROVIDER_CONST::APP_NAME,
         PROVIDER_CONST::APP_API_MGR,
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
         return '/Statics/Skins/Pc/Images/lazyicon.png';
      }
   }
   
   /**
    * 获取商品的网址
    * 
    * @param string number
    * @return string
    */
   public function getProductUrl($number)
   {
      return 'http://'.  \Cntysoft\RT_SYS_SITE_NAME.'/item/'.$number.'.html';
   }
}