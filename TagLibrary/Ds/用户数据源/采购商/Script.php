<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\UserModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;

class BuyerInfo extends AbstractDsScript
{
   protected $chinaArea = null;
   
   public function load()
   {
      $user = $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'getCurUser');
      $detail = $user->getProfile();
      $fileRefs = $detail->getFileRefs();
      $ret = array(
         'avatar' => $this->getImageCdnUrl($detail->getAvatar()),
         'name'   => $user->getName(),
         'phone'  => $user->getPhone(),
         'sex'    => $detail->getSex(),
         'level'  => $detail->getLevel(),
         'address' => $this->getDefaultAddress($user->getId()),
         'rid'    => count($fileRefs) ? $fileRefs[0] : ''
      );
      return $ret;
   }

   /**
    * 获取默认地址
    * 
    * @return string
    */
   public function getDefaultAddress($id)
   {
      $address = $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'getDefaultAddress',
         array($id)
      );
      if(!$address){
         return '未设置默认收货地址';
      }else{
         $province = $this->getArea($address->getProvince());
         $city = $this->getArea($address->getCity());
         $district = $this->getArea($address->getDistrict());
         $address = $address->getAddress();
         return  $province.'-'.$city.'-'.$district.'-'.$address;
      }
   }
   
   

   /**
    * 获取指定code的下级地区信息
    * 
    */
   public function getArea($code)
   {
      if(null == $this->chinaArea){
         $this->chinaArea = new ChinaArea();
      }
      if($code){
         return $this->chinaArea->getArea($code);
      }else{
         return '';
      }      
   }
   
   public function getImageCdnUrl($imgUrl, array $arguments = array())
   {
      if($imgUrl){
         return Kernel\get_image_cdn_url_operate($imgUrl, $arguments);
      }else{
         return '';
      }
   }
}