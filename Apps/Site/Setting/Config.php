<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\Site\Setting;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use Cntysoft\StdModel\Config as ConfigModel;

/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Config extends AbstractLib
{
   const STD_CFG_M_CLS = 'Cntysoft\StdModel\Config';

   /**
    * @param string $group
    * @param string $key
    * @param string $value
    * @return \Cntysoft\StdModel\Config
    */
   public function setItem($group, $key, $value)
   {
      $item = ConfigModel::findFirst(array(
         '[group] = ?0 AND key = ?1',
         'bind' => array(
            0 => $group,
            1 => $key
         )
      ));
      if (!$item) {
         $item = new ConfigModel();
         $item->setGroup($group);
         $item->setKey($key);
         $item->setValue($value);
      } else {
         $item->setValue($value);
      }
      $item->save();
   }

   /**
    * 根据分组获取配置项
    *
    * @param string $group
    * @return \Phalcon\Mvc\Model\ResultSet
    */
   public function getItemsByGroup($group)
   {
      return ConfigModel::find(array(
         '[group] = ?0',
         'bind' => array(
            0 => $group
         )
      ));
   }

   /**
    * 获取系统站点配置信息
    *
    * @return array
    */
   public function getSiteConfig()
   {
      $cacher = $this->getAppObject()->getCacheObject();
      $key = $this->generateSiteConfigCacheKey();
      if(!$cacher->exists($key)){
         $items = $this->getItemsByGroup('Site');
         $map = array();
         foreach ($items as $item){
            $map[$item->getKey()] = $item->getValue();
         }
         $cacher->save($key, $map);
         return $map;
      }
      return $cacher->get($key);
   }

   /**
    * @param string $key
    *
    * @return \Cntysoft\StdModel\Config
    */
   public function getItemByKey($key)
   {
      return ConfigModel::find(array(
         'key = ?0',
         'bind' => array(
            0 => $key
         )
      ));
   }

   /**
    * 根据分组进行配置项删除
    *
    * @param string $group
    */
   public function deleteByGroup($group)
   {
      $mm = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE group = ?0', self::STD_CFG_M_CLS);
      $mm->executeQuery($query, array(
         0 => $group
      ));

      $this->clearCache();
   }

   /**
    * 根据配置识别KEY删除配置项
    *
    * @param string $key
    */
   public function deleteByKey($key)
   {
      $mm = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE  key = ?0', self::STD_CFG_M_CLS);
      $mm->executeQuery($query, array(
         0 => $key
      ));

      $this->clearCache();
   }


   /**
    * 重置教堂的站点配置，这个函数会删除原有的教堂数据
    *
    * @param int $siteId
    */
   public function generateDefaultSiteConfig()
   {
      $cfgs = include $this->getAppObject()->getDataDir().DS.'DefaultSiteConfig.php';
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         foreach ($cfgs as $item) {
            $cItem = new ConfigModel();
            $cItem->setGroup($item['group']);
            $cItem->setKey($item['key']);
            $cItem->setValue($item['value']);
            $cItem->save();
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 获取缓存的键值
    *
    * @return string
    */
   protected function generateSiteConfigCacheKey()
   {
      return md5($this->getAppObject()->getAppKey(). __FUNCTION__);
   }

   /**
    * 清除缓存
    */
   public function clearCache()
   {
      $cacher = $this->getAppObject()->getCacheObject();
      $key = $this->generateSiteConfigCacheKey();
      if($cacher->exists($key)) {
         $cacher->delete($key);
      }
   }
}