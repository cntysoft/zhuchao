<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\GoodsMgr\Constant as GOOD_CONST;
use App\Shop\MarketMgr\Constant as MARKET_CONST;
use App\Shop\UserCenter\Constant as USER_CONST;
class Product extends AbstractScript
{
   /**
    * 获取商品的规格信息
    * 
    * @param array $params
    * @return array
    */
   public function getGoodsStdAttrs($params)
   {
      $productId = $params['productId'];
      $goods = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, 
              GOOD_CONST::APP_NAME, 
              GOOD_CONST::APP_API_GOODS, 
              'getGoodsInfo', 
              array($productId)
              );
      $select = array();
      $name = $goods->getTitle();
      $stdAttrs = $goods->getStdAttrs();
      foreach ($stdAttrs as $stdAttr) {
         $combinations = $stdAttr->getCombination();
         $normalPrice = $stdAttr->getNormalPrice();
         $price = $stdAttr->getPrice();
         $images = $stdAttr->getImages();
         $stock = $stdAttr->getStock();
         $id = $stdAttr->getId();
         $key = '';
         foreach ($combinations as $combination) {
            $key .= ' ' . md5($combination);
         }
         $key = substr($key, 1);
         $select[$key] = array(
            'price'       => $price,
            'normalPrice' => $normalPrice,
            'images'      => $images,
            'stock'       => $stock,
            'id'          => $id,
            'attrs'       => $combinations,
            'name'        => $name
         );
      }
      return $select;
   }

   /**
    * 获取商品规格的信息
    * 
    * @param array $params
    * @return array
    */
   public function getGoodsInfo($params)
   {
      if (!isset($params['gid']) || !isset($params['stdId'])) {
         
      }
      $id = $params['gid'];
      $stdId = $params['stdId'];
      $goodsInfo = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, 
              GOOD_CONST::APP_NAME, 
              GOOD_CONST::APP_API_GOODS, 
              'getGoodsInfo', 
              array($id));
      $stdAttr = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, 
              GOOD_CONST::APP_NAME, 
              GOOD_CONST::APP_API_GOODS, 
              'getStdAttrInfo', 
              array($stdId));
      return array(
         'image' => $goodsInfo->getImg(),
         'title' => $goodsInfo->getTitle(),
         'price' => $stdAttr->getPrice()
      );
   }
   /**
    * 根据商品id获取商品的评论数量
    * 
    * @param array $params
    * @return integer
    */
   public function getAccessCount(array $params)
   {
      $gid = (int)$params[0];
      $count = $this->appCaller->call(
              MARKET_CONST::MODULE_NAME, 
              MARKET_CONST::APP_NAME, 
              MARKET_CONST::APP_API_COMMENT, 
              'getProductCommentList', 
              array(true, 2, $gid));
      return $count[1];
   }
   /**
    * 根据商品id获取商品的评论信息
    * 
    * @param array $params
    * @return type
    */
   public function getAccess(array $params)
   {
      $gid = (int)$params['gid'];
      $page = $params['page'];
      $limit = $params['limit'];
      $count = $this->appCaller->call(
              MARKET_CONST::MODULE_NAME, 
              MARKET_CONST::APP_NAME, 
              MARKET_CONST::APP_API_COMMENT, 
              'getProductCommentList', 
              array(true, 2, $gid,($page-1)*$limit,$limit));
      $ret = array();
      foreach ($count[0] as $value) {
         $uid = $value->getUid();
         $userInfo = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, 
                 USER_CONST::APP_NAME, 
                 USER_CONST::APP_API_USER, 
                 'read', array($uid));
         $ret[] = array(
            'content' => $value->getContent(),
            'time' => date('Y-m-d H:i:s',$value->getTime()),
            'avatar' => $userInfo[0]->getAvatar(),
            'name' => $userInfo[0]->getName(),
            'star' => $value->getStar()
         );
      }
      return $ret;
   }
   /**
    * 查询商品
    * 
    * @param array $params
    * @return type
    */
   public function queryGoods(array $params)
   {
      $categoryId = $params['categoryId'];
      $page = $params['page'];
      $pageSize = $params['pageSize'];
      $goods = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, 
              GOOD_CONST::APP_NAME, 
              GOOD_CONST::APP_API_GOODS, 
              'queryGoods', 
              array($categoryId, $page,$pageSize));
      return $goods['docs'];
   }

}