<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\Product\Constant as GOODS_CONST;
use App\ZhuChao\MarketMgr\Constant as MAR_CONST;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY;
use App\Yunzhan\Setting\Constant as YUNZHAN_SETCONST;
use Cntysoft\Framework\Utils\ChinaArea;
class Goods extends AbstractLabelScript
{
   protected $chinaArea = null;

   /**
    * 获取商品信息
    * @return type
    */
   public function getProductByNumber()
   {
      $number = $this->getRouteInfo()['number'];
      return $this->appCaller->call(
                      GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number));
   }

   public function getCurUser()
   {
      return $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'getCurUser');
   }

   public function getCategoryInfoByIdentifier($identifier)
   {
      return $this->appCaller->call(CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNodeByIdentifier', array($identifier));
   }

   /**
    * 获取PID为0的节点信息
    * @return type
    */
   public function getSuperNodes()
   {
      return $this->appCaller->call(
                      CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getChildren', array(0));
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
    * 获取节点树
    * @return type
    */
   public function getNodeTree()
   {
      return $this->appCaller->call(
                      CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNodeTree');
   }

   /**
    * 根据节点ID，获取节点路径
    *
    * @param string $id
    * @return array
    */
   public function getNodePath($id)
   {
      $tree = $this->getNodeTree();
      $ret = array();
      if (!$tree->isNodeExist($id)) {
         return $ret;
      }
      return $tree->getChildren($id);
   }

   /**
    * 检查是否登录
    * @return boolean
    */
   public function checkLogin()
   {
      return $this->appCaller->call(
                      BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'isLogin');
   }

   /**
    * 检查是否收藏该商品
    * 
    * @return type
    */
   public function checkCollect($id)
   {
      $curUser = $this->getCurUser();
      return $this->appCaller->call(
                      BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_COLLECT, 'checkCollect', array($curUser->getId(), $id));
   }

   /**
    * 获取商品列表
    * @param array $cond
    * @param boolean $total 是否分页
    * @param string $orderBy
    * @param integer $offset
    * @param integer $limit
    * @return list
    */
   public function getGoodsList(array $cond, $total, $orderBy, $offset, $limit)
   {
      $cond = array_merge($cond, array("status = 3"));
      return $this->appCaller->call(
                      GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductList', array($cond, $total, $orderBy, $offset, $limit));
   }

   /**
    * 从CDN上获取图片
    * @param type $source
    * @param type $width
    * @param type $height
    * @return type
    */
   public function getImageFromCdn($source, $width = null, $height = null)
   {
      if (!$width || !$height) {
         return $source ? \Cntysoft\Kernel\get_image_cdn_url($source) : 'Statics/Skins/Pc/Images/lazyicon.png';
      } else {
         return $source ? \Cntysoft\Kernel\get_image_cdn_url_operate($source, array('w' => $width, 'h' => $height, 'c' => 1, 'e' => 1)) : 'Statics/Skins/Pc/Images/lazyicon.png';
      }
   }

   public function getAreaFromCode($code)
   {
      $chinaArea = $this->getChinaArea();
      if ($code == null) {
         return "暂无";
      } else {
         return $chinaArea->getArea($code);
      }
   }

   public function getChinaArea()
   {
      if (null == $this->chinaArea) {
         $this->chinaArea = new ChinaArea();
      }
      return $this->chinaArea;
   }

   /**
    * 检查节点是否存在
    * @param string $identifier
    * @return boolean
    */
   public function checkNodeIdentifier($identifier)
   {
      return $this->appCaller->call(
                      CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'checkNodeIdentifier', array($identifier));
   }

   /**
    * 获取节点的字节点信息
    * @param string $identilier
    * @return 
    */
   public function getSubNodesByIdentifier($identifier)
   {
      $nodeInfo = $this->getNodeInfoByIdentifier($identifier);
      $nodeId = $nodeInfo->getId();
      $childNodes = $this->appCaller->call(
              CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getSubNodes', array($nodeId));
      return $childNodes;
   }

   /**
    * 获取节点信息
    * @param string $identifier
    * @return 
    */
   public function getNodeInfoByIdentifier($identifier)
   {
      $this->checkNodeIdentifier($identifier);
      $nodeInfo = $this->appCaller->call(
              CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($identifier));
      return $nodeInfo;
   }

   /**
    * 获取文章列表（不带分页）
    * @param int $nodeId
    * @return type
    */
   public function getInfoListByNodeAndStatusNotPage($nodeId, $offset = 0, $limit = null)
   {
      if ($limit == null) {
         $limit = 5;
      }
      $generalInfo = $this->appCaller->call(
              CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, false, 'inputTime DESC', $offset, $limit));
      return $generalInfo;
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
    * 根据位置id获取该位置下的广告
    * 
    * @param integer $companyid 企业id
    */
   public function getSiteSetting($companyid)
   {
      $config = $this->appCaller->call(
              YUNZHAN_SETCONST::MODULE_NAME, YUNZHAN_SETCONST::APP_NAME, YUNZHAN_SETCONST::APP_API_CFG, 'getItemsByGroup', array('Site',$companyid)
      );
      $ret = array('facade' => array(), 'environment' => array());
      foreach ($config as $one) {
         $key = $one->getKey();
         $value = $one->getValue();
         if ('Facade' == $key) {
            $value = unserialize($value);
            foreach ($value as $val) {
               $ret['facade'][] = array(
                  'image' => $val[0],
                  'id'    => $val[1]
               );
            }
         }
         if ('Environment' == $key) {
            $value = unserialize($value);
            foreach ($value as $val) {
               $ret['environment'][] = array(
                  'image' => $val[0],
                  'id'    => $val[1]
               );
            }
         }
      }
      return $ret;
   }

   public function getBuyerSiteName()
   {
      return \Cntysoft\RT_BUYER_SITE_NAME;
   }

   public function getProductUrl($number)
   {
      return '/item/' . $number . '.html';
   }

}