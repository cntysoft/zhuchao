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
use App\Shop\ShopFlow\Constant as SHOPFLOWMODEL;
use App\Shop\ShopFlow\Model\OrderPayLog as OrderPayLogModel;
class Order extends AbstractScript
{
   /**
    * 根据订单状态获取订单列表
    * 
    * @param array $params
    * <b>page : 页码</b>
    * <b>status : 订单状态</b>
    * <b>pageSize : 页码容量</b>
    * @return array
    * @throws Exception 参数异常
    */
   public function getOrderListsByStatus(array $params = array())
   {
      $this->checkRequireFields($params, array('page', 'status', 'pageSize'));
      $cond = array(
         'status = ?1',
         'bind' => array(
            1 => $params['status']
         )
      );
      $orderBy = 'id DESC';
      $offset = ((int)$params['page']-1)*(int)$params['pageSize'];
      $limit = (int)$params['pageSize'];
      $orders = $this->appCaller->call(
              SHOPFLOWMODEL::MODULE_NAME, 
              SHOPFLOWMODEL::APP_NAME, 
              SHOPFLOWMODEL::APP_API_MGR, 
              'getOrdersBy', array($cond,false,$orderBy,$offset,$limit));
      $data = array();
      foreach ($orders as $order) {
         $status = $order->getStatus();
         $statusText = '';
         switch ($status) {
            case 1 : $statusText = '未支付';
               break;
            case 2 : $statusText = '已支付';
               break;
            case 3 : $statusText = '配送中';
               break;
            case 4 : $statusText = '已完成';
               break;
            case 5 : $statusText = '已评价';
               break;
            case 6 : $statusText = '取消中';
               break;
            case 7 : $statusText = '已取消';
               break;
         }
         $payType = $order->getPayType();
         $payTypeText = '';
         switch ($payType) {
            case 1 : $payTypeText = '货到付款';
               break;
            case 2 : $payTypeText = '在线付款';
               break;
            case 3 : $payTypeText = '公司转账';
               break;
         }
         $orderId = $order->getId();
         $cond_in = array(
            'orderId = ?1',
            'bind' => array(
               1 => $orderId
            )
         );
         $goodsLists = $this->appCaller->call(
                 SHOPFLOWMODEL::MODULE_NAME, 
                 SHOPFLOWMODEL::APP_NAME, 
                 SHOPFLOWMODEL::APP_API_MGR, 
                 'getOrderGoodsBy', array($cond_in, false, 'id DESC', 0, null));
         $goods = array();
         foreach ($goodsLists as $goodsList) {
            $goods[] = array(
               'id'    => $goodsList->getGid(),
               'num'   => $goodsList->getCount(),
               'pic'   => $this->getImgUrlWithServer($goodsList->getImg()),
               'name'  => $goodsList->getTitle(),
               'price' => $goodsList->getPrice(),
               'std'   => $goodsList->getStdAttrs()
            );
         }
         $item = array(
            'id'          => $order->getid(),
            'orderNumber' => $order->getNumber(),
            'totalPrice'  => $order->getTotalPrice(),
            'status'      => $statusText,
            'payMode'     => $payTypeText,
            'favourPrice' => $order->getDiscount(),
            'goodsList'   => $goods
         );
         $data[] = $item;
      }
      return $data;
   }
   /**
    * 根据订单号获取订单详细信息
    * 
    * @param array $params
    * <b>id : 订单号</b>
    * @return array
    * @throws Exception 参数异常
    */
   public function getOrderDetail(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $orderInfo = $this->appCaller->call(
              SHOPFLOWMODEL::MODULE_NAME, 
              SHOPFLOWMODEL::APP_NAME, 
              SHOPFLOWMODEL::APP_API_MGR, 
              'getOrderByNumber', array($params['id']));
      $status = $orderInfo->getStatus();
      $statusText = '';
      switch ($status) {
         case 1 : $statusText = '未支付';
            break;
         case 2 : $statusText = '已支付';
            break;
         case 3 : $statusText = '配送中';
            break;
         case 4 : $statusText = '已完成';
            break;
         case 5 : $statusText = '已评价';
            break;
         case 6 : $statusText = '取消中';
            break;
         case 7 : $statusText = '已取消';
            break;
      }
      $payType = $orderInfo->getPayType();
      $payTypeText = '';
      switch ($payType) {
         case 1 : $payTypeText = '货到付款';
            break;
         case 2 : $payTypeText = '在线付款';
            break;
         case 3 : $payTypeText = '公司转账';
            break;
      }
      $contact = $orderInfo->getAddress();
      $orderId = $orderInfo->getId();
      $cond_in = array(
         'orderId = ?1',
         'bind' => array(
            1 => $orderId
         )
      );
      $goodsLists = $this->appCaller->call(
              SHOPFLOWMODEL::MODULE_NAME, 
              SHOPFLOWMODEL::APP_NAME, 
              SHOPFLOWMODEL::APP_API_MGR, 
              'getOrderGoodsBy', array($cond_in, false, 'id DESC', 0, null));
      $goods = array();
      foreach ($goodsLists as $goodsList) {
         $goods[] = array(
            'id'    => $goodsList->getGid(),
            'num'   => $goodsList->getCount(),
            'pic'   => $this->getImgUrlWithServer($goodsList->getImg()),
            'name'  => $goodsList->getTitle(),
            'price' => $goodsList->getPrice(),
            'std'   => $goodsList->getStdAttrs()
         );
      }
      $payLog = OrderPayLogModel::findFirst(array(
         'orderId = ?1',
         'bind' => array(
            1 => $orderId
         )
      ));
      $payTime = '';
      $dealId = '';
      $dealType = '';
      if($payLog){
         $payTime = date('Y-m-d H:i', $payLog->getInputTime());
         $dealId = $payLog->getPayNumber();
         $dealType = $payLog->getPayType();
      }
      $data = array(
         'id'             => $params['id'],
         'payMode'        => $payTypeText,
         'status'         => $statusText,
         'endTime'        => date("Y-m-d H:i:s", $orderInfo->getExpirePayTime()),
         'contactName'    => $contact['name'],
         'contactPhone'   => $contact['phone'],
         'contactAddress' => $contact['address'],
         'totalPrice'     => $orderInfo->getTotalPrice(),
         'favourPrice'    => $orderInfo->getDiscount(),
         'carriage'       => $orderInfo->getFreight(),
         'startTime'      => date("Y-m-d H:i:s", $orderInfo->getOrderTime()),
         'finishTime'     => $payTime,
         'payTime'        => $payTime,
         'dealId'         => $dealId,
         'dealType'       => $dealType,
         'goodsList'      => $goods
      );
      return $data;
   }

}
