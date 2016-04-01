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
use App\Yunzhan\Content\Constant as CONTENRT_CONST;
use App\ZhuChao\Provider\Constant as Provider_Constant;
use Cntysoft\Kernel;
class News extends AbstractLabelScript
{
   /**
    * 分页大小
    *
    * @var int
    */
   protected $outputNum;

   /**
    * 获取不同类型的信息列表
    * 
    * @param int $type
    * @param int $page
    * @return 
    */
   public function getNewsList($type, $page)
   {
      $orderBy = 'id desc';
      $pageSize = $this->getOutputNum();
      $offset = ($page - 1) * $pageSize;
      $inquiry = $this->appCaller->call(
              CONTENRT_CONST::MODULE_NAME, CONTENRT_CONST::APP_NAME, CONTENRT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(array(CONTENRT_CONST::NODE_COMPANY_ID, CONTENRT_CONST::NODE_INDUSTRY_ID), $type, CONTENRT_CONST::INFO_S_ALL, true, $orderBy, $offset, $pageSize)
      );
      return $inquiry;
   }

   /**
    * 获取不同类型的信息列表
    * 
    * @param int $type
    * @param int $page
    * @return 
    */
   public function getJobsList($type, $page)
   {
      $orderBy = 'id desc';
      $pageSize = $this->getOutputNum();
      $offset = ($page - 1) * $pageSize;
      $inquiry = $this->appCaller->call(
              CONTENRT_CONST::MODULE_NAME, CONTENRT_CONST::APP_NAME, CONTENRT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(array(CONTENRT_CONST::NODE_JOIN_ID), $type, CONTENRT_CONST::INFO_S_ALL, true, $orderBy, $offset, $pageSize)
      );
      return $inquiry;
   }

   /**
    * 获取不同类型的信息列表
    * 
    * @param int $type
    * @param int $page
    * @return 
    */
   public function getCaseList($type, $page)
   {
      $orderBy = 'id desc';
      $pageSize = $this->getOutputNum();
      $offset = ($page - 1) * $pageSize;
      $inquiry = $this->appCaller->call(
              CONTENRT_CONST::MODULE_NAME, CONTENRT_CONST::APP_NAME, CONTENRT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(array(CONTENRT_CONST::NODE_CASE_ID), $type, CONTENRT_CONST::INFO_S_ALL, true, $orderBy, $offset, $pageSize)
      );
      return $inquiry;
   }

   public function readInfo($id)
   {
      $info = $this->appCaller->call(
              CONTENRT_CONST::MODULE_NAME, CONTENRT_CONST::APP_NAME, CONTENRT_CONST::APP_API_MANAGER, 'read', array($id)
      );

      return $info[1];
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

   /**
    * 获取当前登录的用户
    * 
    * @return 
    */
   public function getCurUser()
   {
      return $this->appCaller->call(
                      Provider_Constant::MODULE_NAME, Provider_Constant::APP_NAME, Provider_Constant::APP_API_MGR, 'getCurUser'
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
         return 'Statics/Skins/Pc/Images/lazyicon.png';
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
      return 'http://' . \Cntysoft\RT_SYS_SITE_NAME . '/item/' . $id . '.html';
   }

}