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
    * 增加一名采购商
    * 
    * @param array $params
    * @return boolean
    */
   public function addBuyer(array $params)
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
      
      if(isset($params['name']) && $params['name'] && $this->checkNameExist($params['name'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_NAME_EXIST', $params['name']), $errorType->code('E_BUYER_NAME_EXIST')
         ), $this->getErrorTypeContext());
      }
      if($this->checkPhoneExist($params['phone'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_PHONE_EXIST', $params['name']), $errorType->code('E_BUYER_PHONE_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $data['phone'] = $params['phone'];
      $pwdHasher = $this->di->getShared('security');
      $data['password'] = $pwdHasher->hash($params['password']);
      $data['name']  = $params['name'];
      $data['status'] = isset($params['status']) ? (int)$params['status'] : Constant::USER_STATUS_NORMAL;
      $data += array(
         'registerTime' => (isset($params['registerTime']) && $params['registerTime']) ? $params['registerTime'] : time(),
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
      
      $profile = new ProfileModel();
      $pdata = array(
         'avatar' => '',
         'experience' => 0,
         'level'   => 1,
         'point'   => 0,
         'sex'     => 3
      );
      foreach($params as $key => $val){
         if(array_key_exists($key, $pdata)){
            $pdata[$key] = $val;
            unset($params[$key]);
         }
      }
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
    * 更新一个采购商的信息
    * 
    * @param integer $id
    * @param array $params
    * @return boolean
    */
   public function updateBuyer($id, array $params)
   {
      $buyer = $this->getBuyerById($id);
      if(!$buyer){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $id), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }

      if(isset($params['name']) && $params['name'] && $params['name'] != $buyer->getName() && $this->checkNameExist($params['name'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_NAME_EXIST'), $errorType->code('E_BUYER_NAME_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      if(isset($params['phone']) && $params['phone'] && $params['phone'] != $buyer->getPhone() && $this->checkPhoneExist($params['phone'])){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_PHONE_EXIST'), $errorType->code('E_BUYER_PHONE_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $db = Kernel\get_db_adapter();
      try{
         $db->begin();
         $profile = $buyer->getProfile();
         $pfields = $profile->getDataFields();
         $pdata = $data = array();
         foreach($params as $key => $val){
            if(in_array($key, $pfields)){
               $pdata[$key] = $val;
               unset($params[$key]);
            }
         }
         
         $fields = $buyer->getDataFields();
         foreach($params as $key => $val){
            if(in_array($key, $fields) && in_array($key, array('level'))){
               $data[$key] = $val;
            }
         }
         
         $profile->assignBySetter($pdata);
         $profile->update();
         
         if(isset($data['password'])){
            $pwdHasher = $this->di->getShared('security');
            $data['password'] = $pwdHasher->hash($data['password']);
            $data['lastModifyPwdTime'] = time();
         }
         
         $buyer->assignBySetter($data);
         $buyer->update();
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }
   
   /**
    * 获取指定条件的采购商信息
    * 
    * @param stirng $cond
    * @param boolean $total
    * @param string $orderBy
    * @param integer $offset
    * @param integer $limit
    * @return array | 
    */
   public function getBuyerList($cond, $total = false, $orderBy = null, $offset = 0, $limit = \Cntysoft\STD_PAGE_SIZE)
   {
      $items = BaseInfoModel::find(array(
         $cond,
         'order' => $orderBy,
         'limit' =>array(
            'number' => $limit,
            'offset' => $offset
         )
      ));
      if ($total) {
         return array(
            $items,
            (int)BaseInfoModel::count($cond)
         );
      }
      return $items;
   }
   
   /**
    * 修改采购商的状态
    * 
    * @param int $id
    * @param int $status
    * @return boolean
    */
   public function changeStatus($id, $status)
   {
      $buyer = $this->getBuyerById($id);
      if (!$buyer) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $id), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }

      $buyer->setStatus($status);
      return $buyer->save();
   }
   
   /**
    * 更改采购商的手机号码
    * 
    * @param integer $id
    * @param string $phone
    */
   public function changePhone($id, $phone)
   {
      $buyer = $this->getBuyerById($id);
      
      if(!$buyer){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $id), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $buyer->setPhone($phone);
      return $buyer->update();
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
   
   /**
    * 获取指定采购商的信息
    * 
    * @param integer $id
    * @return 
    */
   public function getBuyerById($id)
   {
      return BaseInfoModel::findFirst($id);
   }
}
