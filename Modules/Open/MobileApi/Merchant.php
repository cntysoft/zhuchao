<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2015 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\Setting\Constant as SHOP_SETTING_CONST;
use App\Shop\ShopFlow\Constant as SHOP_FLOW_CONST;
/**
 * 店铺设置相关的API接口
 */
class Merchant extends AbstractScript
{
   /**
    * 获取店铺的统计信息
    * 
    * @return array
    */
   public function getBasicStats()
   {
      $orderMgr = $this->appCaller->getAppObject(
              SHOP_FLOW_CONST::MODULE_NAME, SHOP_FLOW_CONST::APP_NAME, SHOP_FLOW_CONST::APP_API_MGR
      );
      $condUnPayed = 'status = ' . SHOP_FLOW_CONST::ORDER_STATUS_UNPAY;
      $unfilledOrders = $orderMgr->getOrdersNumByCond($condUnPayed);
      $startTime = time() - 7 * 24 * 3600;
      $condWeekOrders = 'orderTime > ' . $startTime;
      $weekOrders = $orderMgr->getOrdersNumByCond($condWeekOrders);
      $logo = $this->appCaller->call(
              SHOP_SETTING_CONST::MODULE_NAME, SHOP_SETTING_CONST::APP_NAME, SHOP_SETTING_CONST::APP_API_BASEINFO, 'getItemByGroupAndKey', array(SHOP_SETTING_CONST::APP_API_BASEINFO, SHOP_SETTING_CONST::NAME_SHOP_BASEINFO_LOGO)
      );

      return array(
         'logo'           => $this->getImgUrlWithServer($logo->getData()),
         'unfilledOrders' => $unfilledOrders,
         'weekOrders'     => $weekOrders,
         'visitor'        => 0,
         'tutorial'       => ''
      );
   }

   /**
    * 查询店铺的基本信息
    * 
    * @return array
    */
   public function getBaseInfo()
   {
      $baseinfo = $this->appCaller->call(
              SHOP_SETTING_CONST::MODULE_NAME, SHOP_SETTING_CONST::APP_NAME, SHOP_SETTING_CONST::APP_API_BASEINFO, 'getItemsByGroup', array(SHOP_SETTING_CONST::APP_API_BASEINFO)
      );
      $kefu = $this->appCaller->call(
              SHOP_SETTING_CONST::MODULE_NAME, SHOP_SETTING_CONST::APP_NAME, SHOP_SETTING_CONST::APP_API_BASEINFO, 'getItemsByGroup', array(SHOP_SETTING_CONST::NAME_SHOP_KEFU)
      );
      foreach($baseinfo as $item) {
         if(SHOP_SETTING_CONST::NAME_SHOP_BASEINFO_LOGO == $item->getKey()) {
            $logo = $item->getData();
         }
         if(SHOP_SETTING_CONST::NAME_SHOP_BASEINFO_NAME == $item->getKey()) {
            $name = $item->getData();
         }
      }
      
      foreach($kefu as $item) {
         if(SHOP_SETTING_CONST::NAME_SHOP_KEFU_QQ == $item->getKey()) {
            $qq = $item->getData();
         }
         if(SHOP_SETTING_CONST::NAME_SHOP_KEFU_PHONE == $item->getKey()) {
            $phone = $item->getData();
         }
      }
      return array(
         'logo'    => $this->getImgUrlWithServer($logo),
         'name'    => $name,
         'qq'      => $qq,
         'phone'   => $phone,
         'address' => array(
            'province'     => '',
            'provinceCode' => '',
            'city'         => '',
            'cityCode'     => '',
            'district'     => '',
            'districtCode' => '',
            'detail'       => ''
         )
      );
   }

   /**
    * 保存店铺设置
    * 
    * @param array $params
    */
   public function saveBaseInfo($params)
   {
      $this->checkRequireFields($params, array('group', 'key', 'data'));
      $values = array(
         $params['key'] => $params['data']
      );
      if(isset($params['fileRefs'])) {
         $values['fileRefs'] = $params['fileRefs'];
      }
      
      $this->appCaller->call(
              SHOP_SETTING_CONST::MODULE_NAME, SHOP_SETTING_CONST::APP_NAME, SHOP_SETTING_CONST::APP_API_BASEINFO, 'saveGroupItems', array($params['group'], $values)
      );
   }

}