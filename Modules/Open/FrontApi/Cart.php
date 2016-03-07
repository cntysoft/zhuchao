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
use App\Shop\ShopFlow\Constant as CART_CONST;
use App\Shop\UserCenter\Constant as USER_CONST;
use App\Shop\MarketMgr\Constant as MAR_CONST;
use App\Shop\Setting\Constant as SETTING_CONST;
use App\Shop\ShopFlow\Model\ShopCar as ShopCarModel;
use App\Shop\GoodsMgr\Model\GoodsStdAttr as StdAttrModel;
use App\Shop\UserCenter\Model\Favorites as FavoritesModel;
use App\Shop\MarketMgr\Model\CouponGrant as GrantModel;
use App\Shop\ShopFlow\Model\OrderGoods as OrderGoodsModel;
use App\Shop\MarketMgr\Model\CouponInfo as CouponModel;
use Cntysoft\Framework\Utils\ChinaArea;
use Cntysoft\Framework\Pay\WeChatPay\NativePay;
use Cntysoft\Framework\Pay\WeChatPay\JsApiPay;
use Cntysoft\Framework\Pay\WeChatPay\ShareFunction;
use Cntysoft\Kernel\ConfigProxy;
use Cntysoft\Kernel;
class Cart extends AbstractScript
{
   /**
    * @var \Cntysoft\Framework\Utils\ChinaArea 
    */
   public $chinaarea = null;
   /**
    * @var object 当前登陆用户信息 
    */
   public $acl = null;
   /**
    * 获取当前登陆用户信息
    * 
    * @return object 当前登陆用户信息 
    */
   public function getCurUser()
   {
      if (null == $this->acl) {
         $this->acl = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, 
                 USER_CONST::APP_NAME, 
                 USER_CONST::APP_API_ACL, 
                 'getCurUser');
      }
      return $this->acl;
   }

   /**
    * 获取购物车商品数量
    * 
    * @return integer <b>返回购物车商品数量</b>
    */
   public function getGoodsNum()
   {
      $curUser = $this->getCurUser();
      $count = $this->appCaller->call(
              CART_CONST::MODULE_NAME, 
              CART_CONST::APP_NAME, 
              CART_CONST::APP_API_SHOPCART, 
              'getGoodsNum', array($curUser->getId()));
      return $count;
   }

   /**
    * 通过购物车ID获取该商品的库存
    * 
    * @param array $params <b>0 : 购物车id</b>
    * @return integer <b>返回该购物车记录中的商品的库存数量</b>
    */
   public function getStock(array $params)
   {
      $cart = ShopCarModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $params[0]
                 )
      ));
      $stdId = $cart->getStdId();
      $std = StdAttrModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $stdId
                 )
      ));
      return $std->getStock();
   }

   /**
    * 通过商品规格ID获取商品库存
    * 
    * @param array $params <b>0 : 商品规格id</b>
    * @return integer <b>返回该规格id下的商品库存数量</b>
    */
   public function getStockByStdId(array $params)
   {
      $stdId = $params[0];
      $std = StdAttrModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $stdId
                 )
      ));
      return $std->getStock();
   }

   /**
    * 购物车设置单个选中
    * 
    * @param array $params 
    * <b>id : 购物车id</b>
    * <b>bool : 是否选中</b>
    * @return array
    * <b>totalPrice : 购物车中的商品总价格</b>
    * <b>submit : 购物车中选中的状态的商品数量</b>
    */
   public function setSubmit(array $params)
   {
      $curUser = $this->getCurUser();
      $cart = ShopCarModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $params['id']
                 )
      ));
      $cart->setSubmit($params['bool']);
      $cart->save();
      $totalPrice = 0;
      $carts = ShopCarModel::find(array(
                 'uid = ?1 AND submit = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => 1
                 )
      ));
      foreach ($carts as $value) {
         $totalPrice += $value->getCount() * $value->getPrice();
      }
      $submit = count($carts);
      return array(
         'totalPrice' => $totalPrice,
         'submit'     => $submit
      );
   }

   /**
    * 购物车设置全部选中
    * 
    * @param array $params <b>0 : 设置是否选中</b>
    * @return array
    * <b>totalPrice : 购物车中的选中商品总价格</b>
    * <b>submit : 购物车中选中的状态的商品数量</b>
    */
   public function setSubmitAll(array $params)
   {
      $curUser = $this->getCurUser();
      $bool = $params[0];
      $carts = ShopCarModel::find(array(
                 'uid = ?1',
                 'bind' => array(
                    1 => $curUser->getId()
                 )
      ));
      foreach ($carts as $cart) {
         $cart->setSubmit($bool);
         $cart->save();
      }
      $totalPrice = 0;
      $carts = ShopCarModel::find(array(
                 'uid = ?1 AND submit = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => 1
                 )
      ));
      foreach ($carts as $value) {
         $totalPrice += $value->getCount() * $value->getPrice();
      }
      $submit = count($carts);
      return array(
         'totalPrice' => $totalPrice,
         'submit'     => $submit
      );
   }

   /**
    * 更改购物车商品数目
    * 
    * @param array $params
    * <b>id : 购物车id</b>
    * <b>num : 购物车中的商品数量</b>
    * @return array
    * <b>totalPrice : 购物车中的选中商品总价格</b>
    * <b>submit : 购物车中选中的状态的商品数量</b>
    * <b>smallPrice : 该购物车的价格小计</b>
    */
   public function setNum(array $params)
   {
      $curUser = $this->getCurUser();
      $id = $params['id'];
      $num = $params['num'];
      $goods = ShopCarModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $id
                 )
      ));
      $goods->setCount($num);
      $goods->setSubmit(1);
      $goods->save();
      $smallPrice = $goods->getCount() * $goods->getPrice();
      $carts = ShopCarModel::find(array(
                 'uid = ?1 AND submit = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => 1
                 )
      ));
      foreach ($carts as $value) {
         $totalPrice += $value->getCount() * $value->getPrice();
      }
      $submit = count($carts);
      return array(
         'totalPrice' => $totalPrice,
         'submit'     => $submit,
         'smallPrice' => $smallPrice
      );
   }

   /**
    * 创建订单
    * 
    * @param array $params
    * <b>fapiao : 发票抬头/不开发票</b>
    * <b>payType : 支付方式</b>
    * <b>delivery : 送货时间</b>
    * <b>addressId : 收货地址ID</b>
    * <b>[couponId] : 优惠券ID</b>
    * @return string <b>订单号</b>
    */
   public function createOrder(array $params)
   {
      //检查优惠券
      $couponId = null;
      if (isset($params['couponId'])) {
         $couponId = $params['couponId'];
         if (!is_numeric($couponId)) {
            $errorType = ErrorType::getInstance();
            Kernel\throw_exception(new Exception(
                    $errorType->msg('E_COUPON_NUM_ERROR'), $errorType->code('E_COUPON_NUM_ERROR')));
         }
      }
      //整理订单的信息
      $curUser = $this->getCurUser();
      $info = array(
         'receipt'      => array(
            'title'   => $params['fapiao'],
            'content' => '明细'
         ),
         'payType'      => $params['payType'],
         'deliveryTime' => isset($params['delivery']) ? $params['delivery'] : 1
      );
      $address = $this->appCaller->call(
              USER_CONST::MODULE_NAME, 
              USER_CONST::APP_NAME, 
              USER_CONST::APP_API_ADDRESS, 
              'getAddress', 
              array($params['addressId'], $curUser->getId())
      );
      $info['address'] = array(
         'name'    => $address->getName(),
         'phone'   => $address->getPhone(),
         'address' => $this->getArea($address->getProvince()) . ' ' . $this->getArea($address->getCity()) . ' ' . $this->getArea($address->getDistrict()) . ' ' . $address->getAddress(),
         'tel'     => ''
      );
      $info['comment'] = '暂无';
      $carts = $this->appCaller->call(
              CART_CONST::MODULE_NAME, 
              CART_CONST::APP_NAME, 
              CART_CONST::APP_API_SHOPCART, 
              'getGoodsBySubmit', 
              array($curUser->getId(), 1));
      $goods = array();
      $totalPrice = 0;
      $faceValue = 0;
      $gids = array();
      //开启事务，准备生成订单
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         foreach ($carts as $cart) {
            $gid = $cart->getGid();
            $goodsPrice = $cart->getPrice() * $cart->getCount();
            $totalPrice += $goodsPrice;
            $goods[] = array(
               'gid'      => $gid,
               'attrId'   => $cart->getStdId(),
               'count'    => $cart->getCount(),
               'title'    => $cart->getTitle(),
               'price'    => $cart->getPrice(),
               'img'      => $cart->getImg(),
               'stdAttrs' => $cart->getAttrs()
            );
            array_push($gids, array(
               $gid => $goodsPrice
            ));
         }
         if ($couponId) {
            $coupon = $this->appCaller->call(
                    MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'checkCouponAvailable', array($couponId, $totalPrice, $gids));
            if ($coupon) {
               //使用优惠券
               $faceValue = $coupon->getFaceValue();
               $grant = GrantModel::findFirst(array(
                          'uid = ?1 AND couponId = ?2',
                          'bind' => array(
                             1 => $curUser->getId(),
                             2 => $couponId
                          )
               ));
               $grant->setStatus(1);
               $grant->save();
            }
         }
         $info['goods'] = $goods;
         $info['totalPrice'] = $totalPrice;
         $info['discount'] = $faceValue;
         //计算运费
         $freightInfo = $this->appCaller->call(
                 SETTING_CONST::MODULE_NAME, 
                 SETTING_CONST::APP_NAME, 
                 SETTING_CONST::APP_API_BASEINFO, 
                 'getExpressInfo');
         $fee = isset($freightInfo[0]) ? (isset($freightInfo[1]) ? ($totalPrice < $freightInfo[1] ? $freightInfo[0] : 0) : $freightInfo[0]) : 0;
         $info['freight'] = (int) $fee;
         //计算总价
         $info['price'] = $totalPrice + $info['freight'] - $info['discount'];
         $info['deleted'] = CART_CONST::ORDER_DELETED_FALSE;
         $ret = $this->appCaller->call(
                 CART_CONST::MODULE_NAME, 
                 CART_CONST::APP_NAME, 
                 CART_CONST::APP_API_MGR, 
                 'addOrder', array($curUser->getId(), $info));
         $db->commit();
         return $ret->getNumber();
      } catch (\Exception $ex) {
         $db->rollback();
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception($errorType->msg('E_ADD_ORDER_ERROR'), $errorType->code('E_ADD_ORDER_ERROR'));
      }
   }

   /**
    * 添加商品进购物车
    * 
    * @param array $params 
    * <b>id : 商品规格id</b>
    * <b>num : 要加入的数量</b>
    * @return array
    * <b>gid : 该商品的商品ID</b>
    * <b>stdId : 该商品的规格ID</b>
    */
   public function addToCart(array $params)
   {
      $curUser = $this->getCurUser();
      if (!isset($params['id']) || !isset($params['num']) || !isset($params['gid'])) {
         return false;
      }
      $stdId = $params['id'];
      $num = $params['num'];
      $gid = $params['gid'];
      $goods = $this->appCaller->call(GOOD_CONST::MODULE_NAME, GOOD_CONST::APP_NAME, GOOD_CONST::APP_API_GOODS, 'getGoodsInfo', array($gid));
      if($goods->getStatus() != 1){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_GOODS_STATUS_NONE'), $errorType->code('E_GOODS_STATUS_NONE')));
      }
      $stock = $this->getStockByStdId(array($stdId));
      if ($stock == 0) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_STOCK_NONE'), $errorType->code('E_STOCK_NONE')));
      }
      $isHave = ShopCarModel::findFirst(array(
                 'uid = ?1 AND stdId = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => $stdId
                 )
      ));
      if ($isHave) {
         $nowNum = $isHave->getCount();
         $stdAttr = StdAttrModel::findFirst($stdId);
         $stock = $stdAttr->getStock();
         if ($num + $nowNum > $stock) {
            $errorType = ErrorType::getInstance();
            Kernel\throw_exception(new Exception(
                    $errorType->msg('E_HAS_CHAOGUO_STOCK'), $errorType->code('E_HAS_CHAOGUO_STOCK')));
         }
         $isHave->setCount($num + $nowNum);
         $isHave->save();
         return array(
            'gid'   => $isHave->getGid(),
            'stdId' => $stdId
         );
      }
      $stdAttr = $this->appCaller->call(
              GOOD_CONST::MODULE_NAME, 
              GOOD_CONST::APP_NAME, 
              GOOD_CONST::APP_API_GOODS, 
              'getStdAttrInfo', array($stdId));
      $price = $stdAttr->getPrice();
      $image = $goods->getImg();
      $title = $goods->getTitle();
      $attrs = $stdAttr->getStdMap();

      $cart = new ShopCarModel();
      $cart->setUid($curUser->getId());
      $cart->setGid($gid);
      $cart->setCount($num);
      $cart->setTitle($title);
      $cart->setSubmit(1);
      $cart->setStdId($stdId);
      $cart->setPrice($price);
      $cart->setImg($image);
      $cart->setAttrs($attrs);
      $cart->save();
      return array(
         'gid'   => $gid,
         'stdId' => $stdId
      );
   }
   /**
    * 删除购物车中的商品
    * 
    * @param array $params <b>购物车id组</b>
    * @return array
    * <b>totalPrice : 删除后的当前用户的购物车可提交的商品总价格</b>
    * <b>submit : 删除后的当前用户可提交的购物车数量</b>
    * <b>totalNum : 删除后的当前用户的购物车总数量</b>
    */
   public function deleteMultiGoods(array $params)
   {
      $curUser = $this->getCurUser();
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         foreach ($params as $value) {
            $cart = ShopCarModel::findFirst(array(
                       'id = ?2',
                       'bind' => array(
                          2 => $value
                       )
            ));
            if($cart){
               $cart->delete();
            }
         }
         $db->commit();
      } catch (\Exception $e) {
         $db->rollback();
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_RECEIVE_COUPON_ERROR'), $errorType->code('E_RECEIVE_COUPON_ERROR')));
      }
      $totalPrice = 0;
      $carts = ShopCarModel::find(array(
                 'uid = ?1 AND submit = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => 1
                 )
      ));
      $totalNum = ShopCarModel::count(array(
                 'uid = ?1',
                 'bind' => array(
                    1 => $curUser->getId()
                 )
      ));
      foreach ($carts as $value) {
         $totalPrice += $value->getCount() * $value->getPrice();
      }
      $submit = count($carts);
      return array(
         'totalPrice' => $totalPrice,
         'submit'     => $submit,
         'totalNum'   => $totalNum
      );
   }
   /**
    * 获取省份信息
    * 
    * @return array <b>获取省份数组信息</b>
    */
   public function getProvinces()
   {
      if (null == $this->chinaarea) {
         $this->chinaarea = new ChinaArea;
      }
      return $this->chinaarea->getProvinces();
   }

   /**
    * 获取指定的地区信息
    * 
    * @param integer $param <b>邮编</b>
    * @return string <b>该邮编的地区信息</b>
    */
   public function getArea($param)
   {
      if (empty($param)) {
         return '暂无';
      }
      if (null == $this->chinaarea) {
         $this->chinaarea = new ChinaArea;
      }
      return $this->chinaarea->getArea($param);
   }

   /**
    * 获取指定地区的下一级信息
    * 
    * @param array $param
    * @return array
    */
   public function getChildArea(array $param)
   {
      if (null == $this->chinaarea) {
         $this->chinaarea = new ChinaArea;
      }
      return $this->chinaarea->getChildArea($param[0]);
   }

   /**
    * 添加用户的收货地址
    * 
    * @param array $params
    * @return array
    */
   public function addAddress(array $params)
   {
      $curUser = $this->getCurUser();
      if ('add' == $params['type']) {
         $params['isDefault'] = 0;
         unset($params['type']);
         $address = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'addAddress', array($curUser->getId(), $params));
         return array(
            'name'      => $address->getName(),
            'province'  => $this->getArea($address->getProvince()),
            'city'      => $this->getArea($address->getCity()),
            'district'  => $this->getArea($address->getDistrict()),
            'add'       => $address->getAddress(),
            'phone'     => $address->getPhone(),
            'isDefault' => $address->getIsDefault(),
            'addressId' => $address->getId()
         );
      } else if ('modify' == $params['type']) {
         unset($params['type']);
         $address = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'updateAddress', array($params['id'], $curUser->getId(), $params));
         return array(
            'name'      => $address->getName(),
            'province'  => $this->getArea($address->getProvince()),
            'city'      => $this->getArea($address->getCity()),
            'district'  => $this->getArea($address->getDistrict()),
            'add'       => $address->getAddress(),
            'phone'     => $address->getPhone(),
            'isDefault' => $address->getIsDefault(),
            'addressId' => $address->getId()
         );
      }
   }

   /**
    * 设置用户的默认收货地址
    * 
    * @param array $params
    * @return type
    */
   public function setDefault(array $params)
   {
      $curUser = $this->getCurUser();
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $default = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'getDefaultAddressByUser', array($curUser->getId()));
         if ($default) {
            $default->setIsDefault(0);
            $default->save();
         }
         $now = $this->appCaller->call(
                 USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'getAddress', array((int) $params['id'], $curUser->getId()));
         $now->setIsDefault(1);
         $now->save();
         $db->commit();
         return $now->getId();
      } catch (\Exception $e) {
         $db->rollback();
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_SET_DEFAULT_ADDRESS_FAIL'), $errorType->code('E_SET_DEFAULT_ADDRESS_FAIL')));
      }
   }

   /**
    * 更新用户的收货地址
    * 
    * @param array $params
    * @return array
    */
   public function editorAddress(array $params)
   {
      $curUser = $this->getCurUser();
      $address = $this->appCaller->call(
              USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'getAddress', array($params['id'], $curUser->getId()));
      return array(
         'name'      => $address->getName(),
         'province'  => $address->getProvince(),
         'city'      => $address->getCity(),
         'district'  => $address->getDistrict(),
         'add'       => $address->getAddress(),
         'phone'     => $address->getPhone(),
         'isDefault' => $address->getIsDefault(),
         'addressId' => $address->getId()
      );
   }
   /**
    * 通过地址id获取地址信息
    * 
    * @param array $params <b>地址id</b>
    * @return array <b>地址信息</b>
    */
   public function getAddressById(array $params)
   {
      $curUser = $this->getCurUser();
      $address = $this->appCaller->call(
              USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_ADDRESS, 'getAddress', array($params['id'], $curUser->getId()));
      return array(
         'name'      => $address->getName(),
         'province'  => $this->getArea($address->getProvince()),
         'city'      => $this->getArea($address->getCity()),
         'district'  => $this->getArea($address->getDistrict()),
         'add'       => $address->getAddress(),
         'phone'     => $address->getPhone(),
         'isDefault' => $address->getIsDefault(),
         'addressId' => $address->getId()
      );
   }

   /**
    * 删除用户的收货地址
    * 
    * @param array $params <b>id : 地址id</b>
    * @return boolean
    */
   public function deleteAddress(array $params)
   {
      $curUser = $this->getCurUser();
      return $this->appCaller->call(
              USER_CONST::MODULE_NAME, 
              USER_CONST::APP_NAME, 
              USER_CONST::APP_API_ADDRESS, 
              'deleteAddress', 
              array($params['id'], $curUser->getId()));
   }
   /**
    * 获取当前登陆用户收货地址的数量
    * 
    * @return integer
    */
   public function getAddressNum()
   {
      $curUser = $this->getCurUser();
      $addressList = $this->appCaller->call(
              USER_CONST::MODULE_NAME, 
              USER_CONST::APP_NAME, 
              USER_CONST::APP_API_ADDRESS, 
              'getAddressListByUser', 
              array($curUser->getId()));
      return count($addressList);
   }

   /**
    * 添加收藏商品 
    * 
    * @param array $params
    * @return type
    */
   public function setFavorites(array $params)
   {
      $curUser = $this->getCurUser();
      $uid = $curUser->getId();
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         foreach ($params as $value) {
            $favo = FavoritesModel::findFirst(array(
                       'uid = ?1 AND infoId = ?2 AND type = ?3',
                       'bind' => array(
                          1 => $uid,
                          2 => $value,
                          3 => USER_CONST::FAVORITES_TYPE_GOODS
                       )
            ));
            if (!$favo) {
               $this->appCaller->call(
                       USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_FAVORITES, 'addFavorites', array($curUser->getId(), array(
                     'uid'    => $uid, 'infoId' => $value, 'type'   => USER_CONST::FAVORITES_TYPE_GOODS
               )));
            }
         }
         return $db->commit();
      } catch (\Exception $e) {
         $db->rollback();
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_RECEIVE_COUPON_ERROR'), $errorType->code('E_RECEIVE_COUPON_ERROR')));
      }
   }

   /**
    * 领取优惠券
    * 
    * @param array $params [0]优惠券id
    * @return type
    */
   public function receiveCoupon(array $params)
   {
      $curUser = $this->getCurUser();
      $couponId = $params[0];
      $bool = GrantModel::findFirst(array(
                 'uid = ?1 AND couponId = ?2',
                 'bind' => array(
                    1 => $curUser->getId(),
                    2 => $couponId
                 )
      ));
      if ($bool) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_HAVE_COUPON'), $errorType->code('E_USER_HAVE_COUPON')));
      }
      $coupon = CouponModel::findFirst($couponId);
      $needLevel = $coupon->getNeededLevel();
      $restNum = $coupon->getRestNum();
      $userLevelInfo = $this->appCaller->call(USER_CONST::MODULE_NAME, USER_CONST::APP_NAME, USER_CONST::APP_API_USER, 'getUserGrade', array($curUser->getId()));
      $userLevel = $userLevelInfo['level'];
      if ($needLevel > $userLevel) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_LEVEL_ERROR'), $errorType->code('E_USER_LEVEL_ERROR')));
      }
      if ($restNum <= 0) {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_COUPON_NUM_ERROR'), $errorType->code('E_COUPON_NUM_ERROR')));
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $grant = new GrantModel();
         $grant->setCouponId($couponId);
         $grant->setUid($curUser->getId());
         $grant->setNumber(1);
         $grant->setStatus(0);
         $grant->create();
         $coupon->setRestNum($coupon->getRestNum() - 1);
         $coupon->save();
         $db->commit();
         return $grant->getId();
      } catch (\Exception $e) {
         $db->rollback();
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_RECEIVE_COUPON_ERROR'), $errorType->code('E_RECEIVE_COUPON_ERROR')));
      }
   }
   /**
    * 获取优惠券列表
    * 
    * @return array
    */
   public function getCoupon()
   {
      $couponLists = $this->appCaller->call(
              MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_COUPON, 'getEnableUserCouponList');
      $ret = array();
      foreach ($couponLists as $coupon) {
         $ret[] = array(
            'id'           => $coupon->getId(),
            'name'         => $coupon->getName(),
            'faceValue'    => $coupon->getFaceValue(),
            'outTime'      => date('Y-m-d', $coupon->getOutTime()),
            'creationTime' => date('Y-m-d', $coupon->getCreationTime()),
            'totalGoods'   => $coupon->getTotalGoods()
         );
      }
      return $ret;
   }
   /**
    * 微信扫码支付
    * 
    * @param array $params 参数其中包括订单号与支付方式'NATIVE'
    * @return type
    */
   public function wechatNativePay(array $params)
   {
      if(!isset($params['orderId']) || !isset($params['payType'])){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_WECHATPAY_CODE_ERROR'), $errorType->code('E_WECHATPAY_CODE_ERROR')));
      }
      $orderId = $params['orderId'];
      $payType = $params['payType'];
      $orderParams = $this->getWechatPayParams($orderId, $payType);
      $meta = $this->getWechatPayConfig();
      $config = array(
         'appid' => $meta->APPID,
         'mchid' => $meta->MCH_ID,
         'notify' => sprintf($meta->NOTIFY_URL, SHOP_DEFAULT_URL)
      );
      $nativepay = new NativePay();
      $info = $nativepay->getCodeUrl($orderParams,$config);
      return $info;
   }
   
   public function wechatJsApiPay(array $params)
   {
      if(!isset($params['orderId']) || !isset($params['payType'])){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_WECHATPAY_CODE_ERROR'), $errorType->code('E_WECHATPAY_CODE_ERROR')));
      }
      $orderId = $params['orderId'];
      $payType = $params['payType'];
      $curUser = $this->getCurUser();
      $openid = $curUser->getOpenid();
      $orderParams = $this->getWechatPayParams($orderId, $payType,$openid);
      $meta = $this->getWechatPayConfig();
      $config = array(
         'appid' => $meta->APPID,
         'mchid' => $meta->MCH_ID,
         'notify' => sprintf($meta->NOTIFY_URL, SHOP_DEFAULT_URL)
      );
      $jsApiPay = new JsApiPay();
      $info = $jsApiPay->getPrepayId($orderParams, $config);
      $time = time();
      $ret = array(
         'appId' => $meta->APPID,
         'timeStamp' => "$time",
         'nonceStr' => ShareFunction::createRandStr(),
         'package' => 'prepay_id='.$info['prepay_id'],
         'signType' => 'MD5'
      );
      $sign = ShareFunction::createSign($ret);
      $ret['paySign'] = $sign;
      return $ret;
   }
   /**
    * 获取微信支付所需的订单信息
    * 
    * @param string $orderId 订单号
    * @param string $payType 支付类型
    * @return array
    */
   public function getWechatPayParams($orderId,$payType,$openid = null)
   {
      $curUser = $this->getCurUser();
      $orderInfo = $this->appCaller->call(
              CART_CONST::MODULE_NAME, 
              CART_CONST::APP_NAME, 
              CART_CONST::APP_API_MGR, 
              'getOrderByUserAndNumber', array($curUser->getId(),$orderId));
      $goods = OrderGoodsModel::findFirst(array(
         'orderId = ?1',
         'bind' => array(
            1 => $orderInfo->getId()
         )
      ));
      $body = $goods->getTitle();
      $orderParams = array(
         'body' => $body,
         'out_trade_no' => $orderInfo->getNumber(),
         'total_fee' => (int)str_replace('.', '', $orderInfo->getTotalPrice()),
         'spbill_create_ip' => Kernel\get_client_ip(),
         'trade_type' => $payType,
      );
      if($payType == 'JSAPI'){
         $orderParams['openid'] = $openid;
      }
      return $orderParams;
   }
   /**
    * 通过订单号验证该订单号是否已经支付
    * 
    * @param array $params <b>orderId</b> 订单号
    * @return integer
    */
   public function isPayFinish(array $params)
   {
      $curUser = $this->getCurUser();
      $order = $this->appCaller->call(
              CART_CONST::MODULE_NAME, 
              CART_CONST::APP_NAME, 
              CART_CONST::APP_API_MGR, 
              'getOrderByUserAndNumber', 
              array($curUser->getId(),$params['orderId']));
      return $order->getStatus();
   }
   
   /**
    * 获取微信的配置信息
    * @return object 
    */
   public function getWechatPayConfig()
   {
      $config = ConfigProxy::getFrameworkConfig('Pay');
      if(!isset($config['wechatpay']) || !isset($config['wechatpay']['APPID']) || !isset($config['wechatpay']['MCH_ID']) || !isset($config['wechatpay']['NOTIFY_URL'])){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NO_CONFIG_WECHATPAY_ERROR'), $errorType->code('E_NO_CONFIG_WECHATPAY_ERROR')
                 ), $errorType);
      }
      return $config->wechatpay;
   }

}