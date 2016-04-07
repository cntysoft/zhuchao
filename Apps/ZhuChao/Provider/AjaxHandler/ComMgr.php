<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Provider\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\ZhuChao\Provider\Constant as P_CONST;
class ComMgr extends AbstractHandler
{
   /**
    * 获取供应商企业列表
    * 
    * @param array $params
    * @return arra
    */
   public function getProviderCompanyList($params)
   {
      $this->checkRequireFields($params, array('start', 'limit'));

      if (isset($params['name']) && !empty($params['name'])) {
         $phone = $params['name'];
         $query = " name like '%$phone%'";
      } else {
         $query = null;
      }
      $list = $this->getAppCaller()->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_LIST, 'getProviderCompanyList', array($query, true, null, $params['start'], $params['limit']));

      $total = $list[1];
      $list = $list[0];
      $ret = array();
      if (count($list)) {
         $this->formatUserInfo($list, $ret);
      }
      return array(
         'total' => $total,
         'items' => $ret
      );
   }

   /**
    * 格式化
    * 
    * @param \Phalcon\Mvc\Model\ResultsetInterface  $list
    * @param array $ret
    */
   protected function formatUserInfo($list, array &$ret)
   {
      foreach ($list as $item) {
         if ($item->getProvider()) {
            $pro = $item->getProvider()->getName();
         } else {
            $pro = '';
         }
         $ret[] = array(
            'id'        => $item->getId(),
            'name'      => $item->getName(),
            'provider'  => $pro,
            'inputTime' => date('Y-m-d H:i:s', $item->getInputTime()),
            'status'    => $item->getStatus()
         );
      }
   }

   /**
    * 获取供应商列表
    * 
    * @param array $params
    * @return arra
    * 
    */
   public function getProviderListAll($params)
   {
      $name = trim($params['query']);
      $cond = $name ? array('name like "%' . $name . '%"') : array();
      $list = $this->getAppCaller()->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_LIST, 'getProviderListAll', array($cond)
      );

      $items = $list;
      $ret = array(array(
            'code' => 0,
            'name' => '无'));

      foreach ($items as $item) {
         $user = array(
            'code' => $item->getId(),
            'name' => $item->getName()
         );
         array_push($ret, $user);
      }

      return array(
         'items' => $ret
      );
   }

   /**
    * 添加供应商企业
    * 
    * @param array $params
    * @return boolean
    */
   public function addProviderCompany($params)
   {
      $this->checkRequireFields($params, array('name'));
      if (isset($params['id'])) {
         unset($params['id']);
      }
      if (!$params['providerId']) {
         $params['providerId'] = 0;
      }
      if (!$params['logo']) {
         $params['logo'] = '';
      }

      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'addProviderCompany', array($params));
   }

   /**
    * 更新供应商企业信息
    * 
    * @param array $params
    * @return boolean
    */
   public function updateProviderCompany($params)
   {
      $this->checkRequireFields($params, array('id', 'values'));
      $values = $params['values'];
      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProviderCompany', array($params['id'], $values));
   }

   /**
    * 
    * @param array $params
    * @return boolean
    */
   public function changeCompanyStatus($params)
   {
      $this->checkRequireFields($params, array('id', 'status'));

      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'changeCompanyStatus', array($params['id'], $params['status']));
   }

   /**
    * 获取供应商企业的详细信息
    * 
    * @param array $params
    * @return array
    */
   public function getProviderCompany($params)
   {
      $this->checkRequireFields($params, array('id'));
      $provider = $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'getProviderCompany', array($params['id']));

      $ret = $provider->toArray();
      unset($ret['password']);

      $profile = $provider->getProfile();
      if ($profile) {
         $pData = $profile->toArray();
         unset($pData['id']);
         $ret += $pData;
      }

      return $ret;
   }

   /**
    * 获取全部的省份信息
    * 
    * @return array
    */
   public function getProvinces()
   {
      $provinces = $this->getAppCaller()->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'getProvinces'
      );

      $ret = array();
      foreach ($provinces as $code => $province) {
         $item = array(
            'code' => $code,
            'name' => $province
         );

         array_push($ret, $item);
      }

      return $ret;
   }

   /**
    * 获取指定code下的市区列表
    * 
    * @param array $params
    * @return array
    */
   public function getArea(array $params)
   {
      $this->checkRequireFields($params, array('code'));
      $code = $params['code'];
      $areas = $this->getAppCaller()->call(
              P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'getArea', array($code)
      );

      $ret = array();
      foreach ($areas as $code => $area) {
         $item = array(
            'code' => $code,
            'name' => $area
         );
         array_push($ret, $item);
      }

      return $ret;
   }

}