<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\SiteMgr;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\SiteMgr\Model\Repository as RepositoryModel;
/**
 * 站点管理员角色管理
 */
class Mgr extends AbstractLib
{
   /**
    * 根据站点的名称获取站点的ID
    * 
    * @param string $name
    * @return int
    */
   public function getSiteIdByName($name)
   {
        $name = strtolower($name);
        $cacher = $this->getAppObject()->getCacheObject();
        $map = $cacher->get(Constant::SITE_CACHE_KEY);
        if(null == $map){
            $map = array();
            $set = RepositoryModel::find();
            foreach($set as $site){
                $map[$site->getName()] = $site->getId();
            }
            $cacher->save(Constant::SITE_CACHE_KEY, $map);
        }
        return isset($map[$name]) ? $map[$name] : -1;
   }

}