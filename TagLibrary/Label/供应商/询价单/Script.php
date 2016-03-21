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
use App\ZhuChao\MessageMgr\Constant as MessageMgr_Const;
use Cntysoft\Kernel;
class Inquiry extends AbstractLabelScript
{
   public function getInquiryList($status)
   {
      
      $inquiry = $this->appCaller->call(MessageMgr_Const::MODULE_NAME, MessageMgr_Const::APP_NAME, MessageMgr_Const::APP_API_, 'getInquiryList', array($cond, true, $orderBy, $limit, $offset));
      return $inquiry;
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
                      PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MGR, 'getCurUser'
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
      if ($imgUrl) {
         return Kernel\get_image_cdn_url_operate($imgUrl, $arguments);
      } else {
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
      return 'http://' . \Cntysoft\SYS_SITE_NAME_DEVEL . '/item/' . $id . '.html';
   }

}