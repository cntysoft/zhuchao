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
use Cntysoft\Kernel;
use App\ZhuChao\Provider\Model\BaseInfo as BaseModel;
use App\ZhuChao\Provider\Model\Company as CompanyModel;
/**
 * 站点管理员角色管理
 */
class ListView extends AbstractLib
{
   /**
    * 获取供应商列表
    * 
    * @param boolean $total
    * @param string $cond
    * @param string $orderBy
    * @param int $offset
    * @param int $limit
    * @return array
    */
   public function getProviderList($total = false, $cond, $orderBy = null, $offset = 0, $limit = \Cntysoft\STD_PAGE_SIZE)
   {
      $items = BaseModel::find(array(
                 $cond,
                 'order' => $orderBy,
                 'limit' => array(
                    'number' => $limit,
                    'offset' => $offset
                 )
      ));
      if ($total) {
         return array(
            $items,
            (int) BaseModel::count($cond)
         );
      }
      return $items;
   }

   /**
    * 获取所有供应商列表
    * 
    * @param array $cond
    */
   public function getProviderListAll($cond)
   {
      $items = BaseModel::find($cond);
      return $items;
   }

   /**
    * 获取供应商企业列表
    * 
    * @param boolean $total
    * @param string $cond
    * @param string $orderBy
    * @param int $offset
    * @param int $limit
    * @return array
    */
   public function getProviderCompanyList($cond, $total = false, $orderBy = null, $offset = 0, $limit = \Cntysoft\STD_PAGE_SIZE)
   {
      $items = CompanyModel::find(array(
                 $cond,
                 'order' => $orderBy,
                 'limit' => array(
                    'number' => $limit,
                    'offset' => $offset
                 )
      ));
      if ($total) {
         return array(
            $items,
            (int) CompanyModel::count($cond)
         );
      }
      return $items;
   }

   /**
    * 获取全部的企业信息
    * 
    * @return 
    */
   public function getProviderCompanyListAll($name = '')
   {
      $cond = '';
      if ($name) {
         $cond = "name like '%" . $name . "%'";
      }

      return CompanyModel::find(array(
                 $cond
      ));
   }

   /**
    * 获取域名对应的站点ID
    * 
    * @param string $attr
    * @return int
    */
   public function getSiteIdBySubAttr($attr)
   {
      $name = strtolower($attr);
      $cacher = $this->getAppObject()->getCacheObject();
      $map = $cacher->get(Constant::SITE_CACHE_KEY);
      if (null == $map) {
         $map = array();
         $set = $this->getProviderCompanyListAll();
         foreach ($set as $site) {
            $attr = $site->getSubAttr();
            if (isset($attr)) {
               $map[$site->getSubAttr()] = $site->getId();
            }
         }
         $cacher->save(Constant::SITE_CACHE_KEY, $map);
      }
      return isset($map[$name]) ? $map[$name] : -1;
   }

}