<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\Buyer\Model\Address as AddressModel;
use Cntysoft\Kernel;

class Address extends AbstractLib
{
   /**
    * 为指定的采购商添加收获地址
    * 
    * @param integer $buyerId 
    * @param array $params
    */
   public function addAddress($buyerId, array $params)
   {
      $count = $this->getAddressCount($buyerId);
      if($count == Constant::ADDRESS_MAX_NUM){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ADDRESS_MAXIMUM'), $errorType->code('E_BUYER_ADDRESS_MAXIMUM')
         ), $this->getErrorTypeContext());
      }
      $db = Kernel\get_db_adapter();
      
      try{
         $db->begin();
         $address = new AddressModel();
         $requires = $address->getRequireFields(array('id', 'buyerId', 'isDefault', 'inputTime'));
         $this->checkRequireFields($params, $requires);
         $params['inputTime'] = time();
         $params['buyerId'] = (int)$buyerId;
         $address->assignBySetter($params);
         $address->create();
         if(isset($params['isDefault']) && $params['isDefault']){
            $this->setDefaultAddress($buyerId, $address->getId());
         }
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
      
   }

   /**
    * 删除采购商的一条收货地址
    * 
    * @param integer $buyerId
    * @param integer $addressId
    * @return boolean
    */
   public function deleteAddress($buyerId, $addressId)
   {
      $address = $this->getAddressByBuyerAndId((int)$buyerId, (int)$addressId);
      
      return $address->delete();
   }
   
   /**
    * 更改采购商的收货地址
    * 
    * @param integer $buyerId
    * @param type $addressId
    * @param array $params
    * @return type
    */
   public function updateAddress($buyerId, $addressId, array $params)
   {
      $address = $this->getAddressByBuyerAndId((int)$buyerId, (int)$addressId);
      
      Kernel\unset_array_values($params, array('id', 'buyerId', 'inputTime'));
      if(isset($params['isDefault']) && $params['isDefault']){
         $this->setDefaultAddress($buyerId, $addressId);
      }
      $address->assignBySetter($params);
      return $address->update();
   }
   
   /**
    * 将指定地址设为默认地址
    * 
    * @param integer $buyerId
    * @param integer $addressId
    */
   public function setDefaultAddress($buyerId, $addressId)
   {
      $defaultAddress = $this->getDefaultAddress((int)$buyerId);
      $db = Kernel\get_db_adapter();
      
      try {
         $db->begin();
         if($defaultAddress){
            if($defaultAddress->getId() != (int)$addressId){
               $defaultAddress->setIsDefault(Constant::ADDRESS_STATUS_NOT_DEFAULT);
               $defaultAddress->update();
               $address = $this->getAddressByBuyerAndId((int)$buyerId, (int)$addressId);
               $address->setIsDefault(Constant::ADDRESS_STATUS_DEFAULT);
               $address->update();
            }
         }else{
            $address = $this->getAddressByBuyerAndId((int)$buyerId, (int)$addressId);
            $address->setIsDefault(Constant::ADDRESS_STATUS_DEFAULT);
            $address->update();
         }
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }
   
   /**
    * 获取默认的收货地址
    * 
    * @param integer $buyerId
    * @return 
    */
   public function getDefaultAddress($buyerId)
   {
      return AddressModel::findFirst(array(
         'buyerId=?0 and isDefault=?1',
         'bind' => array(
            0 => (int)$buyerId,
            1 => Constant::ADDRESS_STATUS_DEFAULT
         )
      ));
   }
   
   /**
    * 获取指定采购商的全部收货地址
    * 
    * @param integer $buyerId
    * @return 
    */
   public function getAddressListByBuyer($buyerId)
   {
      return AddressModel::find(array(
         'buyerId=?0',
         'bind' => array(
            0 => (int)$buyerId
         )
      ));
   }
   
   /**
    * 获取指定采购员指定id的地址信息
    * 
    * @param integer $buyerId
    * @param integer $addressId
    */
   public function getAddressByBuyerAndId($buyerId, $addressId)
   {
      $address = AddressModel::findFirst(array(
         'buyerId=?0 and id=?1',
         'bind' => array(
            0 => (int)$buyerId,
            1 => (int)$addressId
         )
      ));
      
      if(!address){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_ADDRESS_NOT_EXIST'), $errorType->code('E_BUYER_ADDRESS_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      return $address;
   }
   
   /**
    * 获取指定采购人员收获地址的数量
    * 
    * @param integer $buyerId
    * @return integer
    */
   public function getAddressCount($buyerId)
   {
      return AddressModel::count(array(
         'buyerId=?0',
         'bind' => array(
            0 => (int)$buyerId
         )
      ));
   }
}


