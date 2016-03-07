<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\MarketMgr\Constant as COUPON_CONST;
use App\Shop\UserCenter\Constant as U_CONST;
use App\Shop\MarketMgr\Model\CouponGrant as CouponGrantModel;
class Coupon extends AbstractScript
{
   /**
    * 获取优惠券列表
    * 
    * @param array $params
    * @return array
    */
   public function getCouponList($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      if ($params['status'] == 1) {
         $cond = ' and creationTime > ' . time();
      } else if ($params['status'] == 2) {
         $cond = ' and creationTime <= ' . time() . ' and outTime > ' . time();
      } else if ($params['status'] == 3) {
         $cond = ' and outTime <= ' . time();
      } else if ($params['status'] == 4) {
         $cond = ' and restNum =0 ';
      } else {
         return array();
      }
      $resultlist = $this->appCaller->call(
              COUPON_CONST::MODULE_NAME, COUPON_CONST::APP_NAME, COUPON_CONST::APP_API_COUPON, 'getCouponList', array(
         'status < 3 ' . $cond, TRUE, 'id desc', ( $params['page'] - 1) * $params['pageSize'], $params['pageSize']
      ));
      $couponlist = $resultlist[0];
      foreach ($couponlist as $coupon) {
         $couponarray = $coupon->toArray(true);
         $couponarray['outTime'] = date('Y-m-d', $couponarray['outTime']);
         $couponarray['creationTime'] = date('Y-m-d', $couponarray['creationTime']);
         $couponarray['useNum'] = CouponGrantModel::count(array(
                    'couponId=?0 and status=?1',
                    'bind' => array($couponarray['id'], COUPON_CONST::USER_COUPON_USEED)
         ));
         $couponarray['person'] = $couponarray['totalNum'] - $couponarray['restNum'];
         $ret[] = $couponarray;
      }
      return $ret;
   }

   public function getUserLevel()
   {
      $userLevels = $this->appCaller->call(
              U_CONST::MODULE_NAME, U_CONST::APP_NAME, U_CONST::APP_API_LEVEL, 'getLevelList', array(
         FALSE, 'id ASC'
      ));
      foreach ($userLevels as $level) {
         $item['id'] = $level->getId();
         $item['name'] = $level->getGrade();
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 添加优惠券
    * 
    * @param array $params
    * @return App\Shop\MarketMgr\Model\CouponInfo
    */
   public function saveCouponInfo(array $params = array())
   {
      $this->checkRequireFields($params, array(
         'id', 'name', 'faceValue', 'miniConsume', 'totalNum', 'creationTime', 'outTime', 'neededLevel', 'totalGoods', 'goodsIds'
      ));
      $date = split('-', $params['outTime']);
      $date2 = split('-', $params['creationTime']);
      $outTime = mktime(23, 59, 59, $date[1], $date[2], $date[0]);
      $createTime = mktime(0, 0, 0, $date2[1], $date2[2], $date2[0]);
      $params['outTime'] = $outTime;
      $params['creationTime'] = $createTime;

      if ($params['id']) {
         $this->appCaller->call(
                 COUPON_CONST::MODULE_NAME, COUPON_CONST::APP_NAME, COUPON_CONST::APP_API_COUPON, 'updateCouponInfo', array($params['id'], $params)
         );
      } else {
         $params['restNum'] = $params['totalNum'];
         $this->appCaller->call(
                 COUPON_CONST::MODULE_NAME, COUPON_CONST::APP_NAME, COUPON_CONST::APP_API_COUPON, 'addCouponInfo', array($params)
         );
      }
   }

   /**
    * 获取优惠券
    * 
    * @param array $params
    * @return array
    */
   public function getCouponInfo(array $params)
   {
      $this->checkRequireFields($params, array(
         'id'
      ));
      $coupon = $this->appCaller->call(
              COUPON_CONST::MODULE_NAME, COUPON_CONST::APP_NAME, COUPON_CONST::APP_API_COUPON, 'getCouponInfo', array($params['id'])
      );
      $ret = $coupon->toArray(true);
      $goodslist = array();
      foreach ($coupon->goodslist as $item) {
         array_push($goodslist, $item->getId());
      }
      $ret['goodsIds'] = $goodslist;
      $ret['outTime'] = date('Y-m-d', $ret['outTime']);
      $ret['creationTime'] = date('Y-m-d', $ret['creationTime']);
      return $ret;
   }

   /**
    * 删除优惠券
    * 
    * @param array $params
    */
   public function deleteCouponInfo(array $params)
   {
      $this->checkRequireFields($params, array(
         'id'
      ));
      $this->appCaller->call(
              COUPON_CONST::MODULE_NAME, COUPON_CONST::APP_NAME, COUPON_CONST::APP_API_COUPON, 'setCouponStatus', array($params['id'], 3)
      );
   }

}