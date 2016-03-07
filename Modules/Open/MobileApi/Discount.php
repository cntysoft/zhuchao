<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2015 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\MarketMgr\Constant as MARKETMGRCONST;
use App\Shop\GoodsMgr\Constant as GOODSCONST;
class Discount extends AbstractScript
{
   /**
    * 获取折扣信息列表
    * 
    * @param array $params
    * <b>status : 折扣状态</b>
    * <b>page : 页码</b>
    * <b>pageSize : 页码容量</b>
    * @return array 
    * <b>id : 折扣id</b>
    * <b>name : 折扣名称</b>
    * <b>status : 折扣状态</b>
    * <b>discount : 打折折扣</b>
    * <b>count : 该折扣下的商品数量</b>
    */
   public function getDiscountsList(array $params = array())
   {
      $this->checkRequireFields($params, array('status', 'page', 'pageSize'));
      $offset = ((int)$params['page'] - 1) * (int)$params['pageSize'];
      $limit = (int)$params['pageSize'];
      $discounts = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, 
              MARKETMGRCONST::APP_NAME, 
              MARKETMGRCONST::APP_API_DISCOUNT, 
              'getDiscountListByStatus', array($params['status'],false,'id DESC',$offset,$limit));
      $data = array();
      foreach ($discounts as $discount) {
         $goodsList = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, 
              MARKETMGRCONST::APP_NAME, 
              MARKETMGRCONST::APP_API_DISCOUNT, 
              'getDiscountGoodsList', array($discount->getId()));
         $data[] = array(
            'id' => $discount->getId(),
            'name' => $discount->getName(),
            'status' => $discount->getStatus(),
            'discount' => $discount->getDiscount(),
            'count' => count($goodsList)
         );
      }
      return $data;
   }
   /**
    * 添加折扣信息
    * 
    * @param array $params
    * <b>status : 折扣状态</b>
    * <b>name : 折扣名称</b>
    * <b>discount : 打折折扣</b>
    * <b>type : 折扣种类 如果为0则是部分商品打折此时必须有$params['idList'],如果为1则是全部商品打折不需要$params['idList']</b>
    * @return array 如果添加成功则返回'success'(数组形式)
    */
   public function addDiscount(array $params = array())
   {
      $this->checkRequireFields($params, array('status', 'name', 'discount', 'type'));
      if ($params['type'] == 0) {
         if (!array_key_exists('idList', $params)) {
            throw new Exception('params error', 10000);
         }
         $discountInfo = array(
            'name'     => $params['name'],
            'discount' => $params['discount'],
            'status'   => $params['status'],
            'goodsIds' => $params['idList']
         );
         $discount = $this->appCaller->call(
                 MARKETMGRCONST::MODULE_NAME, 
                 MARKETMGRCONST::APP_NAME, 
                 MARKETMGRCONST::APP_API_DISCOUNT, 
                 'addDiscountInfo', array($discountInfo));
         $data = array(
            'success'
         );
         return $data;
      }
      $goodsList = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, 
              GOODSCONST::APP_NAME, 
              GOODSCONST::APP_API_GOODS, 
              'getGoodsListAll');
      $idList = array();
      foreach ($goodsList as $value) {
         $idList[] = $value->getId();
      }
      $discountInfo = array(
         'name'     => $params['name'],
         'discount' => $params['discount'],
         'status'   => $params['status'],
         'goodsIds' => $idList
      );
      $discount = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, 
              MARKETMGRCONST::APP_NAME, 
              MARKETMGRCONST::APP_API_DISCOUNT, 
              'addDiscountInfo', array($discountInfo));
      $data = array(
         'success'
      );
      return $data;
   }

   public function getDiscountInfo(array $params = array())
   {
      $this->checkRequireFields($params, array("id"));
      $discount = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, 
              MARKETMGRCONST::APP_NAME, 
              MARKETMGRCONST::APP_API_DISCOUNT, 
              'getDiscountInfo', array($params['id']));
      $idList = $this->getGoodsIdListById($params);
      $ret = array();
      $ret['id'] = $params['id'];
      $ret['name'] = $discount->getName();
      $ret['discount'] = $discount->getDiscount();
      $ret['status'] = $discount->getStatus();
      $ret['idList'] = $idList;
      $ret['count'] = count($idList);
      return $ret;
   }

   public function getGoodsIdListById(array $params = array())
   {
      $this->checkRequireFields($params, array("id"));
      $goodsList = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, 
              MARKETMGRCONST::APP_NAME, 
              MARKETMGRCONST::APP_API_DISCOUNT, 
              'getDiscountGoodsList', array($params['id']));
      $idList = array();
      foreach ($goodsList as $value) {
         $idList[] = $value->getId();
      } 
      return $idList;
   }
   
   public function updateDiscount(array $params = array())
   {
      $this->checkRequireFields($params, array('id', 'status', 'name', 'discount', 'type'));
      if ($params['type'] == 0) {
         if (!array_key_exists('idList', $params)) {
            throw new Exception('params error', 10000);
         }
         $discountInfo = array(
            'name'     => $params['name'],
            'discount' => $params['discount'],
            'status'   => $params['status'],
            'goodsIds' => $params['idList']
         );
         $discount = $this->appCaller->call(
                 MARKETMGRCONST::MODULE_NAME, 
                 MARKETMGRCONST::APP_NAME, 
                 MARKETMGRCONST::APP_API_DISCOUNT, 
                 'updateDiscountInfo', array($params['id'],$discountInfo));
         $data = array(
            'success'
         );
         return $data;
      }
      $goodsList = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, 
              GOODSCONST::APP_NAME, 
              GOODSCONST::APP_API_GOODS, 
              'getGoodsListAll');
      $idList = array();
      foreach ($goodsList as $value) {
         $idList[] = $value->getId();
      }
      $discountInfo = array(
         'name'     => $params['name'],
         'discount' => $params['discount'],
         'status'   => $params['status'],
         'goodsIds' => $idList
      );
      $discount = $this->appCaller->call(
                 MARKETMGRCONST::MODULE_NAME, 
                 MARKETMGRCONST::APP_NAME, 
                 MARKETMGRCONST::APP_API_DISCOUNT, 
                 'updateDiscountInfo', array($params['id'],$discountInfo));
      $data = array(
         'success'
      );
      return $data;
   }
   
   /**
    * 删除折扣
    * 
    * @param array $params
    */
   public function deleteDiscount(array $params = array())
   {
      $this->checkRequireFields($params, array("id"));
      $discount = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, MARKETMGRCONST::APP_NAME, MARKETMGRCONST::APP_API_DISCOUNT, 'getDiscountInfo', array($params['id']));
      if (!$discount->getStatus()) {
         $this->appCaller->call(
                 MARKETMGRCONST::MODULE_NAME, MARKETMGRCONST::APP_NAME, MARKETMGRCONST::APP_API_DISCOUNT, 'deleteDiscountInfo', array($params['id']));
      }
   }

   /**
    * 修改折扣状态
    * 
    * @param array $params
    */
   public function changeDiscountStatus(array $params = array())
   {
      $this->checkRequireFields($params, array("id", "status"));
      $discount = $this->appCaller->call(
              MARKETMGRCONST::MODULE_NAME, MARKETMGRCONST::APP_NAME, MARKETMGRCONST::APP_API_DISCOUNT, 'getDiscountInfo', array($params['id']));
      if (in_array($params['status'], array(0, 1))) {
         $discount->setStatus($params['status']);
      }
      $discount->save();
   }

}