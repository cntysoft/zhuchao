<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel;

class Buyer extends AbstractHandler
{
   /**
    * 获取指定条件下的采购商的信息
    * 
    * @param array $params
    * @return array
    */
   public function getBuyerList(array $params)
   {
      $this->checkRequireFields($params, array('start', 'limit'));
      if (isset($params['phone']) && !empty($params['phone'])) {
         $phone = $params['phone'];
         $query = " phone = '$phone' ";
      } else {
         $query = null;
      }
      $list = $this->getAppCaller()->call(
         BUYER_CONST::MODULE_NAME, 
         BUYER_CONST::APP_NAME, 
         BUYER_CONST::APP_API_BUYER_MGR, 
         'getBuyerList', 
         array($query, true, null, $params['start'], $params['limit'])
      );
      
      $total = $list[1];
      $list = $list[0];
      $ret = array();
      if (count($list)) {
         $this->formatBuyerInfo($list, $ret);
      }
      return array(
         'total' => $total,
         'items' => $ret
      );
   }
   
   /**
    * 修改采购商的状态信息
    * 
    * @param array $params
    * @return boolean
    */
   public function changBuyerStatus(array $params)
   {
      $this->checkRequireFields($params, array('id', 'status'));

      return $this->getAppCaller()->call(
         BUYER_CONST::MODULE_NAME, 
         BUYER_CONST::APP_NAME, 
         BUYER_CONST::APP_API_BUYER_MGR, 
         'changeStatus', 
         array($params['id'], $params['status'])
      );
   }
   
   /**
    * 增加一位采购商
    * 
    * @param array $params
    * @return type
    */
   public function addBuyer(array $params)
   {
      $this->checkRequireFields($params, array('name', 'phone', 'password', 'experience', 'registerTime', 'lastLoginTime', 'sex', 'status'));
      $params['password'] = hash('sha256', $params['password']);
      
      foreach($params as $key => $val){
         if(in_array($key, array('registerTime', 'lastLoginTime'))){
            $params[$key] = strtotime($val);
         }
      }
      
      return $this->getAppCaller()->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'addBuyer',
         array($params)
      );
   }
   
   /**
    * 修改一位采购商的信息
    * 
    * @param array $params
    * @return boolean
    */
   public function updateBuyer(array $params)
   {
      $this->checkRequireFields($params, array('id', 'values'));
      $id = (int)$params['id'];
      unset($params['id']);
      $values = $params['values'];
      if(isset($values['password']) && $values['password']){
         $values['password'] = hash('sha256', $values['password']);
      }
      foreach($values as $key => $val){
         if(in_array($key, array('registerTime', 'lastLoginTime'))){
            $values[$key] = strtotime($val);
         }
      }

      return $this->getAppCaller()->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'updateBuyer',
         array($id, $values)
      );
   }
   
   /**
    * 获取采购商的信息
    * 
    * @param array $params
    * @return array
    */
   public function getBuyerInfo(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      
      $buyer = $this->getAppCaller()->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'getBuyerById',
         array((int)$params['id'])
      );
      
      if(!$buyer){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_USER_NOT_EXIST', $params['id']), $errorType->code('E_BUYER_USER_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }

      $ret = $buyer->toArray();
      unset($ret['password']);

      $profile = $buyer->getProfile();
      if ($profile) {
         $pData = $profile->toArray();
         unset($pData['id']);
         $ret += $pData;
      }

      $timeDataKeys = array('registerTime', 'lastLoginTime', 'lastLogoutTime', 'lastModifyPwdTime');
      foreach ($timeDataKeys as $key) {
         if (isset($ret[$key])) {
            $time = $ret[$key];
            unset($ret[$key]);
            $ret[$key] = date('Y-m-d', $time);
         }
      }

      return $ret;
   }
   
   /**
    * 格式化采购商的信息
    * 
    * @param type $list
    * @param array $ret
    */
   public function formatBuyerInfo($list, array &$ret)
   {
      foreach ($list as $item) {
         $profile = $item->getProfile();
         $ret[] = array(
            'id'             => $item->getId(),
            'name'           => $item->getName(),
            'sex'            => $profile->getSex(),
            'phone'          => $item->getPhone(),
            'registerTime'   => date('Y-m-d H:i:s', $item->getRegisterTime()),
            'lastLoginTime'  => date('Y-m-d H:i:s', $item->getLastLoginTime()),
            'status'         => $item->getStatus()
         );
      }
   }
}
