<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\ZhuChao\Provider\Exception;
use App\ZhuChao\Provider\Constant as P_CONST;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Mgr extends AbstractHandler
{
   /**
    * 添加供应商
    * 
    * @param array $params
    * @return boolean
    */
   public function addProvider($params)
   {
      $this->checkRequireFields($params, array('name', 'password', 'phone'));
      if (isset($params['id'])) {
         unset($params['id']);
      }
      $timeDataKeys = array('registerTime', 'lastLoginTime');
      foreach ($timeDataKeys as $key) {
         if (isset($params[$key])) {
            $time = $params[$key];
            unset($params[$key]);
            $params[$key] = strtotime($time);
         }
      }

      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'addProvider', array($params));
   }

   /**
    * 更新供应商信息
    * 
    * @param array $params
    * @return boolean
    */
   public function updateProvider($params)
   {
      $this->checkRequireFields($params, array('id', 'values'));
      $values = $params['values'];
      $timeDataKeys = array('registerTime', 'lastLoginTime');
      foreach ($timeDataKeys as $key) {
         if (isset($values[$key])) {
            $time = $values[$key];
            unset($values[$key]);
            $values[$key] = strtotime($time);
         }
      }
      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProvider', array($params['id'], $values));
   }

   /**
    * 
    * @param array $params
    * @return boolean
    */
   public function changProviderStatus($params)
   {
      $this->checkRequireFields($params, array('id', 'status'));

      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'changeStatus', array($params['id'], $params['status']));
   }

   /**
    * 获取供应商的详细信息
    * 
    * @param array $params
    * @return array
    */
   public function getProviderInfo($params)
   {
      $this->checkRequireFields($params, array('id'));
      $provider = $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'getProvider', array($params['id']));

      $ret = $provider->toArray();
      unset($ret['password']);

      $profile = $provider->getProfile();
      if ($profile) {
         $pData = $profile->toArray();
         unset($pData['id']);
         $ret += $pData;
      }

      $timeDataKeys = array('registerTime', 'lastLoginTime');
      foreach ($timeDataKeys as $key) {
         if (isset($ret[$key])) {
            $time = $ret[$key];
            unset($ret[$key]);
            $ret[$key] = date('Y-m-d', $time);
         }
      }

      return $ret;
   }

}
