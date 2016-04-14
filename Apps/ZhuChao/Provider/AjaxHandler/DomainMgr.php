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
use App\ZhuChao\Provider\Model\Map as DomainMapModel;
class DomainMgr extends AbstractHandler
{
   public function getProviderShopList($params)
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
         $id = $item->getId();
         $map = DomainMapModel::findFirstByCompanyId($id);
         $domain = $map ? $map->getDomain() : '';
         $subAttr = $item->getSubAttr();
         $siteName = isset($subAttr) ? $subAttr . '.' . \Cntysoft\RT_ZHUCHAO_SITE_DOMAIN : '';
         
         $ret[] = array(
            'id'        => $id,
            'name'      => $item->getName(),
            'siteName'  => $siteName,
            'domain' => $domain,
            'status'    => $item->getStatus()
         );
      }
   }

   public function setCompanyDomain($params)
   {
      $this->checkRequireFields($params, array('id', 'domain'));
      $id = $params['id'];
      $domain = $params['domain'];
      
      return $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_DOMAIN_BINDER, 'bindDomainToCompany', array($id, $domain));
   }
   
   public function deleteCompanyDomain()
   {
      
   }
   
   public function getCompanyInfo($params)
   {
      $this->checkRequireFields($params, array('id'));
      $provider = $this->getAppCaller()->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'getProviderCompany', array($params['id']));

      $map = DomainMapModel::findFirstByCompanyId($params['id']);
      $subAttr = $provider->getSubAttr();
      $ret = array(
         'id' => $provider->getId(),
         'name' => $provider->getName(),
         'domain' => $map ? $map->getDomain() : '',
         'siteName' => isset($subAttr) ? $subAttr . '.' . \Cntysoft\RT_ZHUCHAO_SITE_DOMAIN : ''
      );
      return $ret;
   }
   
}