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
         'limit' =>array(
            'number' => $limit,
            'offset' => $offset
         )
      ));
      if ($total) {
         return array(
            $items,
            (int)BaseModel::count($cond)
         );
      }
      return $items;
   }
}
