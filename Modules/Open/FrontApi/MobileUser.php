<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\UserCenter\Constant;
use Cntysoft\Kernel;
use KeleShop\Framework\Net\Upload;
use App\Shop\ShopFlow\Constant as SHOP_CONST;
use App\Shop\MarketMgr\Constant as MAR_CONST;
use App\Shop\GoodsMgr\Constant as GOOD_CONST;
use App\Site\Category\Constant as CategoryConst;
use App\Site\Content\Constant as ContentConst;
/**
 * 主要是处理前端用户中心通用的Ajax调用
 * 
 * @package FrontApi
 */
class MobileUser extends AbstractScript
{
   /**
    * 获取用户消息
    * 
    * @param array $params
    * @return array
    */
   public function getMessageList(array $params)
   {
      $this->checkRequireFields($params, array('status', 'page', 'limit'));
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $msglist = $this->appCaller->call(Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_MSG, 'getMessageList', array(
         array('uid=?0 and status=?1', 'bind' => array($user->getId(), $params['status'])), false, ' id desc', $params['limit'] * $params['page'], $params['limit']
              )
      );
      $ret = array();
      foreach ($msglist as $value) {
         $message = $value->text;
         $msg = $message->getMsgText();
         $item['content'] = strlen($msg) > 80 ? mb_substr($msg, 0, 80) . '...' : $msg;
         $item['umid'] = $value->getId();
         $item['time'] = $this->formatDate($message->getSendTime());
         $ret[] = $item;
      }
      return $ret;
   }

   protected function getCouponIds()
   {
      $ret = array();
      $enablecoupon = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'getCouponListAll', array(
         array(
            ' outTime > ' . time()
         )
      ));
      foreach ($enablecoupon as $coupon) {
         $ret[] = $coupon->getId();
      }
      return $ret;
   }

   /**
    * 获取用户优惠券列表
    * 
    * @param array $params
    * @return array
    */
   public function getCouponList(array $params)
   {
      $this->checkRequireFields($params, array('status', 'page', 'limit'));
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $couponlist = array();
      $url = '';
      switch ((int) $params['status']) {
         case 1:
            $couponIds = $this->getCouponIds();
            if (empty($couponIds)) {
               break;
            }
            $cond = array(
               'uid=?0 and status=?1 and couponId in (' . implode(',', $couponIds) . ')',
               'bind' => array($user->getId(), MAR_CONST::USER_COUPON_UNUSE)
            );
            $couponlist = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'getUserCouponList', array(
               $cond, false, 'id desc', $params['limit'] * $params['page'], $params['limit']
                    )
            );
            $url = 'href="/"';
            break;
         case 2:
            $cond = array(
               'uid=?0 and status=?1',
               'bind' => array($user->getId(), MAR_CONST::USER_COUPON_USEED)
            );
            $couponlist = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'getUserCouponList', array(
               $cond, false, 'id desc', $params['limit'] * $params['page'], $params['limit']
                    )
            );
            break;
         case 3:
            $couponIds = $this->getCouponIds();
            $range = '';
            if (!empty($couponIds)) {
               $range = 'and couponId not in (' . implode(',', $couponIds) . ')';
            }
            $cond = array(
               'uid=?0 and status!=?1 ' . $range,
               'bind' => array($user->getId(), MAR_CONST::USER_COUPON_USEED)
            );
            $couponlist = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'getUserCouponList', array(
               $cond, false, 'id desc', $params['limit'] * $params['page'], $params['limit']
                    )
            );
            break;

         default:
            break;
      }
      $ret = array();
      foreach ($couponlist as $value) {
         $coupon = $value->getCouponInfo();
         $item = $coupon->toArray(true);
         $item['start'] = $this->formatDate($item['creationTime']);
         $item['end'] = $this->formatDate($item['outTime']);
         $item['url'] = $url;
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 获取用户订单列表
    * 
    * @param array $params
    * @return array
    */
   public function getOrderList(array $params)
   {
      $this->checkRequireFields($params, array('status', 'page', 'limit'));
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $orderlist = array();
      switch ((int) $params['status']) {
         case 1:
            $cond = array(
               'uid=?0',
               'bind' => array($user->getId())
            );
            $orderlist = $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersBy', array(
               $cond, false, 'status asc,id desc', $params['page'] * $params['limit'], $params['limit']
                    )
            );
            break;
         case 2:
            $orderStatus = SHOP_CONST::ORDER_STATUS_UNPAY;
            $enablestatus = true;
            break;
         case 3:
            $orderStatus = SHOP_CONST::ORDER_STATUS_TRANSPORT;
            $enablestatus = true;
            break;
         case 4:
            $orderStatus = SHOP_CONST::ORDER_STATUS_FINISHED;
            $enablestatus = true;
            break;
         default:
            break;
      }
      if ($enablestatus) {
         $cond = array(
            'uid=?0 and status=?1',
            'bind' => array($user->getId(), $orderStatus)
         );
         $orderlist = $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersBy', array(
            $cond, false, 'id desc', $params['page'] * $params['limit'], $params['limit']
                 )
         );
      }
      return $this->formatOrderlist($orderlist);
   }

   /**
    * 格式化订单
    * 
    * @param integer $orderlist
    * @return string
    */
   protected function formatOrderlist($orderlist)
   {
      $ret = array();
      foreach ($orderlist as $order) {
         $orderstatus = (int) $order->getStatus();
         $orderNum = $order->getNumber();
         $chstatus = $tail = '';
         switch ($orderstatus) {
            case 1:
               $chstatus = '等待付款';
               $tail = '<a href="/pay.html?orderId=' . $orderNum . '" >立即支付</a>';
               break;
            case 2:
               $chstatus = '已支付';
               break;
            case 3:
               $chstatus = '等待收货';
               break;
            case 4:
               $chstatus = '已完成';
               break;
            case 6:
               $chstatus = '正在取消';
               break;
            case 7:
               $chstatus = '已取消';
               break;
            default:
               break;
         }
         $goodslist = $order->getGoods();
         $retgoods = array();
         foreach ($goodslist as $ordergoods) {
            $gid = $ordergoods->getGid();
            $attr = $this->getGoodsAttrs($gid, $ordergoods->getStdAttrs());
            $item2['goodsattr'] = $attr;
            $item2['goodsbtn'] = $chstatus == 4 ? '' : '';
            $item2['goodsurl'] = $this->getGoodsUrl($gid);
            $item2['goodsImg'] = $ordergoods->getImg();
            $item2['goodstitle'] = $ordergoods->getTitle();
            $item2['goodscount'] = $ordergoods->getCount();
            $item2['goodsprice'] = $ordergoods->getPrice();
            $retgoods[] = $item2;
         }
         $item1['goodslist'] = $retgoods;
         $item1['ordernum'] = $orderNum;
         $item1['orderurl'] = $this->getOrderUrl($orderNum);
         $item1['chstatus'] = $chstatus;
         $item1['tailbtn'] = $tail;
         $ret[] = $item1;
      }
      return $ret;
   }

   /**
    * 获取订单商品的规格信息
    * 
    * @param integer $gid
    * @param array $stdattrs 
    * @return integer
    */
   protected function getGoodsAttrs($gid, $stdattrs)
   {
      $stdAttrs = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_GOODS, 'getStdAttrNameMap', array(
         $gid
              )
      );
      $keys = array_keys($stdAttrs);
      foreach ($keys as $key) {
         $stdAttrs[] = $key;
      }
      $ret = '';
      foreach ($stdattrs as $index => $value) {
         $ret.=$stdAttrs[$index] . ' ：' . $value . '   ';
      }
      return $ret;
   }

   /**
    * 获取收藏商品列表
    * 
    * @return type
    */
   public function getFavoriteList($params)
   {
      $this->checkRequireFields($params, array('type', 'page', 'limit'));
      $type = (int) $params['type'];
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $ret = array();
      switch ($type) {
         case Constant::FAVORITES_TYPE_GOODS:
            $gids = $this->getCollectionIds($user->getId(), $type);
            if (empty($gids)) {
               return $ret;
            }
            $cond = array(
               'id in (' . implode(',', $gids) . ')'
            );
            $goodslist = $this->appCaller->call(GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_GOODS, 'getGoodsListBy', array(
               $cond, false, 'id desc', $params['page'] * $params['limit'], $params['limit']
                    )
            );
            foreach ($goodslist as $goods) {
               $item['goodsurl'] = $this->getGoodsUrl($goods->getId());
               $item['img'] = $goods->getImg();
               $item['price'] = $goods->getPrice();
               $item['title'] = $goods->getTitle();
               $item['type'] = $type;
               $item['infoId'] = $goods->getId();
               $ret[] = $item;
            }
            return $ret;
         default:
            break;
      }
      return $ret;
   }

   /**
    * 按收藏类型获取用户收藏信息的所有id
    * 
    * @param integer $uid
    * @param integer $type 
    * @return array
    */
   protected function getCollectionIds($uid, $type)
   {
      $ret = array();
      $collections = $this->appCaller->call(Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_FAVORITES, 'getFavoritesListByCond', array(
         array('uid' => $uid, 'type' => $type), false, null, 0, 0
      ));
      foreach ($collections as $collection) {
         $ret[] = $collection->getInfoId();
      }
      return $ret;
   }

   /**
    * 获取待评价商品列表
    * 
    * @return type
    */
   public function getUserAssessList($params)
   {
      $this->checkRequireFields($params, array('page', 'limit'));
      $completeOrderIds = $this->getOrderIds();
      if (empty($completeOrderIds)) {
         return array();
      }
      $ordergoodsList = $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrderGoodsBy', array(
         array('status=?0 and orderId in (' . implode(',', $completeOrderIds) . ') ', 'bind' => array(0)),
         false, ' id desc', $params['page'] * $params['limit'], $params['limit']
      ));
      $ret = array();
      foreach ($ordergoodsList as $ordergoods) {
         $item['goodsurl'] = $this->getGoodsUrl($ordergoods->getGid());
         $item['img'] = $ordergoods->getImg();
         $item['title'] = $ordergoods->getTitle();
         $item['price'] = $ordergoods->getPrice();
         $item['commenturl'] = $this->getUserCommentUrl($ordergoods->getId());
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 获取已完成的订单id
    * 
    */
   protected function getOrderIds()
   {
      $acl = $this->di->get('FrontUserAcl');
      $user = $acl->getCurUser();
      $orderlist = $this->appCaller->call(SHOP_CONST::MODULE_NAME, SHOP_CONST::APP_NAME, SHOP_CONST::APP_API_MGR, 'getOrdersBy', array(
         array(
            'uid=?0 and status=?1',
            'bind' => array($user->getId(), SHOP_CONST::ORDER_STATUS_FINISHED)
         ), null, 'id desc', 0, 0
      ));
      $ret = array();
      foreach ($orderlist as $order) {
         $ret[] = $order->getId();
      }
      return $ret;
   }

   /**
    * 获取用户评论详情的URL
    *
    * @param integer $ordergoodsId
    * @return string
    */
   public function getUserCommentUrl($ordergoodsId)
   {
      return '/user/comment/' . $ordergoodsId . '.html';
   }

   /**
    * 获取订单详情的URL
    *
    * @param integer $orderId
    * @return string
    */
   public function getOrderUrl($orderId)
   {
      return '/user/order/' . $orderId . '.html';
   }

   /**
    * 
    * @param integer $gid
    * @return string
    */
   protected function getGoodsUrl($gid)
   {
      return '/product/' . $gid . '.html';
   }

   /**
    * 格式化日期
    * 
    * @param integer $timestamp
    * @return string
    */
   protected function formatDate($timestamp, $format = 'Y-m-d H:i')
   {
      return date($format, $timestamp);
   }

}