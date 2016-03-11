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
class ListView extends AbstractHandler
{
   /**
    * 获取供应商列表
    * 
    * @param array $params
    * @return arra
    */
   public function getProviderList($params)
   {
      $this->checkRequireFields($params, array('start', 'limit'));
      if (isset($params['phone']) && !empty($params['phone'])) {
         $phone = $params['phone'];
         $query = " phone = '$phone' ";
      } else {
         $query = null;
      }
      $list = $this->getAppCaller()->call(
              P_CONST::MODULE_NAME, 
              P_CONST::APP_NAME, 
              P_CONST::APP_API_LIST, 
              'getProviderList', 
              array(true, $query, null, $params['start'], $params['limit']));
      
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
         $ret[] = array(
            'id'                => $item->getId(),
            'name'              => $item->getName(),
            'phone'  => $item->getPhone(),
            'registerTime'        => date('Y-m-d H:i:s', $item->getRegisterTime()),
            'lastLoginTime'     => date('Y-m-d H:i:s', $item->getLastLoginTime()),
            'status' => $item->getStatus()
         );
      }
   }

}
