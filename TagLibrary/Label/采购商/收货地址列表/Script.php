<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Buyer;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Framework\Utils\ChinaArea;

class Address extends AbstractLabelScript
{
   protected static $chinaArea = null;

   /**
    * 获取指定code的下级地区信息
    * 
    */
   public function getArea($code)
   {
      $chinaArea = $this->getChinaArea();
      if($code){
         return $chinaArea->getArea($code);
      }else{
         return '';
      }      
   }

   /**
    * 获取省市区管理对象
    * 
    * @return Cntysoft\Framework\Utils\ChinaArea
    */
   protected function getChinaArea()
   {
      if (null == self::$chinaArea) {
         self::$chinaArea = new ChinaArea();
      }

      return self::$chinaArea;
   }
   
   public function getAddressList()
   {
      $curUser = $this->getCurUser();

      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'getAddressListByBuyer',
         array($curUser->getId())
      );
   }
   
   public function getCurUser()
   {
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'getCurUser'
      );
   }
}