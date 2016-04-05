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
use App\Yunzhan\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use Cntysoft\Kernel;

class GroupList extends AbstractLabelScript
{
   protected $outputNum = null;
   
   public function getProductGroupList()
   {
      $query = $this->getQuery();
      $group = 0;
      if(isset($query['group'])){
         $group = (int)$query['group'];
      }
      $page = $this->getPageParam();
      
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_GROUP_MGR,
         'getProductByGroup',
         array(array(PRODUCT_CONST::PRODUCT_STATUS_PEEDING, PRODUCT_CONST::PRODUCT_STATUS_VERIFY, PRODUCT_CONST::PRODUCT_STATUS_REJECTION, PRODUCT_CONST::PRODUCT_STATUS_SHELF), $group, true, 'productId DESC', $page['offset'], $page['limit'])
      );
   }
   
   public function getGroup($groupId)
   {
      $curUser = $this->getCurUser();
      $tree = $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_GROUP_MGR,
         'getGroupTree',
         array($curUser->getId())
      );
      
      $group = $tree->getValue($groupId);
      
      if($group){
         return $group;
      }else{
         return '';
      }
   }
   
   public function getGroupTree()
   {
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_GROUP_MGR,
         'getGroupTree',
         array()
      );
   }
    
   public function getPageUrl($pageId)
   {
      return '/group/'.$pageId.'.html?type=2';
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
   public function getImageCdnUrl($imgUrl)
   {
      if($imgUrl){
         return Kernel\get_image_cdn_url($imgUrl);
      }else{
         return 'Statics/Skins/Pc/Images/lazyicon.png';
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