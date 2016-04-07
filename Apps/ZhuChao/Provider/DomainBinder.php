<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Provider;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\Provider\Model\Map as MapModel;
/**
 * 站点管理员角色管理
 */
class DomainBinder extends AbstractLib
{
   /**
    * 获取域名对应的站点ID
    * 
    * @param string $domain
    * @return int
    */
   public function getSiteIdByDomain($domain)
   {
      $name = strtolower($domain);
      $cacher = $this->getAppObject()->getCacheObject();
      $map = $cacher->get(Constant::SITE_DOMAIN_CACHE_KEY);
      if (null == $map) {
         $map = array();
         $set = MapModel::find();
         foreach ($set as $site) {
            $attr = $site->getDomain();
            if (isset($attr)) {
               $map[$attr] = $site->getCompanyId();
            }
         }
         $cacher->save(Constant::SITE_DOMAIN_CACHE_KEY, $map);
      }
      
      return isset($map[$name]) ? $map[$name] : -1;
   }
   
   /**
    * 为商家站点绑定自定义的域名
    * 
    * @param int $companyId
    * @param string $domain
    * @return boolean
    */
   public function bindDomainToCompany($companyId, $domain)
   {
      $map = new DomainBinder();
      $map->setCompanyId($companyId);
      $map->setDomain($domain);
      
      return $map->create();
   }
   
   /**
    * 删除域名绑定记录
    * 
    * @param int $id
    * @return boolean
    */
   public function unbindDomain($id)
   {
      $map = DomainBinder::findFirst($id);
      
      if(!$map) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_DOMAIN_MAP_NOT_EXIST'), $errorType->code('E_DOMAIN_MAP_NOT_EXIST'), $this->getErrorTypeContext()));
      }
      
      return $map->delete();
   }
}