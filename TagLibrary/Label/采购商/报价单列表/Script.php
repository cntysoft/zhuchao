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
use App\ZhuChao\MessageMgr\Constant as MESSAGEMGR_CONST;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel;

class QuotationList extends AbstractLabelScript
{
   protected $outputNum = 0;

   public function getQuotationList()
   {
      $curUser = $this->getCurUser();
      $page = $this->getPageParam();

      return $this->appCaller->call(
         MESSAGEMGR_CONST::MODULE_NAME,
         MESSAGEMGR_CONST::APP_NAME,
         MESSAGEMGR_CONST::APP_API_OFFER,
         'getInquiryList',
         array(array('uid='.$curUser->getId()), true, 'id DESC', $page['limit'], $page['offset'])
      );
   }

   public function getPageUrl($pageId)
   {
      return '/quotationlist/'.$pageId.'.html';
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
         return '/Statics/Skins/Pc/Images/lazyicon.png';
      }
   }
   
   /**
    * 获取商品的网址
    * 
    * @param integer $id
    * @return string
    */
   public function getProductUrl($id)
   {
      return 'http://'.\Cntysoft\SYS_SITE_NAME_DEVEL.'/item/'.$id.'.html';
   }
   
   /**
    * 获取商品的网址
    * 
    * @param integer $id
    * @return string
    */
   public function getQuotationUrl($id)
   {
      return '/quotation/'.$id.'.html';
   }
}