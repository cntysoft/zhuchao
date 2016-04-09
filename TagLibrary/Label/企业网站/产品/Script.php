<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Company;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\Yunzhan\Product\Constant as PRODUCT_CONST;
use Cntysoft\Kernel;
class Product extends AbstractLabelScript
{
   protected $outputNum = null;
   protected $query = null;
   protected $tree = null;

   /**
    * 获取商品信息
    * @return type
    */
   public function getProductByNumber()
   {
      $number = $this->getRouteInfo()['number'];
      return $this->appCaller->call(
                      PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number));
   }

   public function getProductList()
   {
      $page = $this->getPageParam();
      $query = $this->getQuery();
      $cond = array();
      $orderBy = 'id DESC';
      $groupId = 0;
      if (isset($query['keyword']) && $query['keyword']) {
         $cond[] = "(brand like '%" . $query['keyword'] . "%' or title like '%" . $query['keyword'] . "%' or description like '%" . $query['keyword'] . "%' )";
      }
      if (isset($query['group'])) {
         $groupId = $query['group'];
      }
      if (isset($query['sort']) && $query['sort']) {
         switch ((int) $query['sort']) {
            case 2:$orderBy = 'hits DESC';
               break;
            case 3:$orderBy = 'price ASC';
               break;
            case 4:$orderBy = 'price DESC';
               break;
            default:
               break;
         }
      }
      $cond[] = 'status=' . PRODUCT_CONST::PRODUCT_STATUS_VERIFY;
//      $queryCond = array(implode(' and ', $cond));

      return $this->appCaller->call(
                      PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_GROUP_MGR, 'queryProductByGroup', array($cond, $groupId, $this->invokeParams['enablePage'], $orderBy, $page['offset'], $page['limit'])
      );
   }

   /**
    * 获取产品分组树
    * 
    */
   public function getProudctGroupTree()
   {
      if (null == $this->tree) {
         $this->tree = $this->appCaller->call(
                 PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_GROUP_MGR, 'getGroupTree', array()
         );
      }
      return $this->tree;
   }

   public function getPageUrl($pageId)
   {
      return '/productlist/' . $pageId . '.html';
   }

   public function getInfoUrl($item)
   {
      return '/item/' . $item . '.html';
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
      if (null == $this->query) {
         $this->query = $this->di->get('request')->getQuery();
      }
      return $this->query;
   }

   /**
    * 生成网址中的查询信息ｕrl
    * 
    * @return array
    */
   public function getQueryUrl()
   {
      $query = $this->getQuery();
      $ret = '';
      if (count($query) > 1) {
         unset($query['_url']);
         $ret.='?';
         foreach ($query as $key => $value) {
            $ret.=$key . '=' . $value . '&';
         }
         $ret = substr($ret, 0, strlen($ret) - 1);
      }
      return $ret;
   }

   /**
    * 获取cdn图片的地址
    * 
    * @param string $imgUrl
    * @param integer $width
    * @param integer $height
    * @return string
    */
   public function getImgUrl($imgUrl, $width, $height)
   {
      if ($imgUrl) {
         return $width && $height ? Kernel\get_image_cdn_url_operate($imgUrl, array('w' => $width, 'h' => $height, 'e' => 1, 'c' => 1)) :
                 Kernel\get_image_cdn_url_operate($imgUrl, array());
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
   public function getProduct($id)
   {
      return '/item/' . $id . '.html';
   }

}