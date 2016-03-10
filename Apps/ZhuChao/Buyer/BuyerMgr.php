<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\Buyer\Model\BaseInfo as BaseInfoModel;
use App\ZhuChao\Buyer\Model\Profile as ProfileModel;
use Cntysoft\Kernel;

class BuyerMgr extends AbstractLib
{
   /**
    * 增加一名采购会员
    * 
    * @param array $params
    * @return boolean
    */
   public function addUser(array $params)
   {
      if(!$params['phone']){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_PHONE_EMPTY'), $errorType->code('E_BUYER_PHONE_EMPTY')
         ), $this->getErrorTypeContext());
      }
      if(!$params['password']){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_PASSWORD_EMPTY'), $errorType->code('E_BUYER_PASSWORD_EMPTY')
         ), $this->getErrorTypeContext());
      }
      $data['phone'] = $params['phone'];
      $data['password'] = $params['password'];
      $data['name']  = $params['name'];
      $data['status'] = isset($params['status']) ? (int)$params['status'] : Constant::USER_STATUS_NORMAL;
      $data += array(
         'registerTime' => time(),
         'registerIp'   => Kernel\get_client_ip(),
         'loginTimes'   => 0,
         'lastLoginIp'  => '',
         'lastLoginTime' => 0,
         'lastLogoutTime' => 0,
         'lastModifyPwdTime' => 0,
         'loginErrorTimes' => 0
      );
      $baseInfo = new BaseInfoModel();
      $requires = $baseInfo->getRequireFields(array('id', 'profileId'));
      $this->checkRequireFields($data, $requires);

      if($data['name'] && $this->checkNameExist($data['name'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_NAME_EXIST', $data['name']), $errorType->code('E_BUYER_NAME_EXIST')
         ), $this->getErrorTypeContext());
      }
      if($this->checkPhoneExist($data['phone'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_PHONE_EXIST', $data['name']), $errorType->code('E_BUYER_PHONE_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $profile = new ProfileModel();
      $pdata = array(
         'avatar' => '',
         'experience' => 0,
         'level'   => 1,
         'point'   => 0,
         'sex'     => 3
      );
      $db = Kernel\get_db_adapter();
      
      try{
         $db->begin();
         $profile->assignBySetter($pdata);
         $profile->create();
         $data['profileId'] = $profile->getId();
         $baseInfo->assignBySetter($data);
         $baseInfo->create();
         
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollBack();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }
   
   /**
    * 获取指定用户名的用户信息
    * 
    * @param string $name
    * @return App\ZhuChao\Buyer\Model\BaseInfo | false
    */
   public function getUserByName($name)
   {
      return BaseInfoModel::findFirst(array(
         'name=?0',
         'bind' => array(
            0 => $name
         )
      ));
   }
   
   /**
    * 获取指定手机号的用户信息
    * 
    * @param string $phone
    * @return App\ZhuChao\Buyer\Model\BaseInfo | false
    */
   public function getUserByPhone($phone)
   {
      return BaseInfoModel::findFirst(array(
         'phone=?0',
         'bind' => array(
            0 => $phone
         )
      ));
   }
   /**
    * 判断用户名是否存在，存在返回true，不存在返回false
    * 
    * @param string $name
    * @return boolean
    */
   public function checkNameExist($name)
   {
      return BaseInfoModel::count(array(
         'name=?0',
         'bind' => array(
            0 => $name
         )
      )) > 0 ? true : false;
   }
   
   /**
    * 判断用户的手机号码是否存在，存在返回true，不存在返回false
    * 
    * @param string $phone
    * @return boolean
    */
   public function checkPhoneExist($phone)
   {
      return BaseInfoModel::count(array(
         'phone=?0',
         'bind' => array(
            0 => $phone
         )
      )) > 0 ? true : false;
   }
}
