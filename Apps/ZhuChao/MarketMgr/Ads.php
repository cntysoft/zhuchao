<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MarketMgr;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\ZhuChao\MarketMgr\Model\AdsModule;
use App\ZhuChao\MarketMgr\Model\Ads as AdsModel;
class Ads extends AbstractLib
{
   /**
    * 获取pid为$id的广告位置列表
    * 
    * @param integer $id 节点id
    * @return object 广告位置列表
    */
   public function getAdsModuleTree($id)
   {
      $res = AdsModule::find(array(
                 'pid = ?1',
                 'bind' => array(
                    1 => $id
                 )
      ));
      return $res;
   }
   /**
    * 根据设备端名称,模块名称,位置名称获取该位置的id
    * 
    * @param string $port 设备端
    * @param string $module 模块名称
    * @param string $location 位置名称
    * @return integer 该位置的id
    */
   public function getAdsLocationId($port,$module,$location)
   {
      $portInfo = AdsModule::findFirst(array(
         'name = ?1',
         'bind' => array(
            1 => $port
         )
      ));
      $moduleInfo = AdsModule::findFirst(array(
         'name = ?1 AND pid = ?2',
         'bind' => array(
            1 => $module,
            2 => (int)$portInfo->getId()
         )
      ));
      $locationInfo = AdsModule::findFirst(array(
         'name = ?1 AND pid = ?2',
         'bind' => array(
            1 => $location,
            2 => (int)$moduleInfo->getId()
         )
      ));
      return (int)$locationInfo->getId();
   }

   /**
    * 添加广告
    * 
    * @param array $params locationId,name,contentUrl,sort,image,fileRefs为必填项
    * @return integer 该广告的id
    */
   public function addAds(array $params)
   {
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $ads = new AdsModel();
         $params['startTime'] = strtotime($params['startTime']);
         $params['endTime'] = strtotime($params['endTime']);
         $ads->assignBySetter($params);
         if (isset($params['fileRefs'])) {
            $fileRef = $this->di->get('FileRefManager');
            $fileRef->confirmFileRef((int) $params['fileRefs']);
         }
         $ads->save();
         $db->commit();
         return $ads->getId();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 修改广告
    * @param int $id 广告ID 
    * @param array $params 要修改的字段信息
    * @return integer 该广告的id
    */
   public function modifyAds($id, array $params)
   {
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $ads = AdsModel::findFirst(array(
                    'id = ?1',
                    'bind' => array(
                       1 => $id
                    )
         ));
         $params['startTime'] = strtotime($params['startTime']);
         $params['endTime'] = strtotime($params['endTime']);
         unset($params['id']);
         if (array_key_exists('fileRefs', $params) && $ads->getFileRefs() != $params['fileRefs']) {
            $fileRefMgr = $this->di->get('FileRefManager');
            $fileRefMgr->removeFileRef($ads->getFileRefs());
            $fileRefMgr->confirmFileRef($params['fileRefs']);
         }
         $ads->assignBySetter($params);
         $ads->save();
         $db->commit();
         return $ads->getId();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 获取广告列表
    * 
    * @param integer $locationId 广告的位置id
    * @param boolean $page 是否翻页
    * @param integer $limit 
    * @param integer $offset
    * @param string $order 排序方式
    * @return array/list 翻页时返回数组'item'为广告列表,'total'为广告数量,不翻页时返回广告列表
    */
   public function getAdsList($locationId = null, $order = 'id DESC', $page = false, $limit = null, $offset = null)
   {
      if ($locationId) {
         $ads = AdsModel::find(array(
                    'locationId = ?1',
                    'bind'  => array(
                       1 => $locationId
                    ),
                    'order' => $order,
                    'limit' => array(
                       'number' => $limit,
                       'offset' => $offset
                    )
         ));
         $total = AdsModel::count(array(
                    'locationId = ?1',
                    'bind' => array(
                       1 => $locationId
                    )
         ));
      } else {
         $ads = AdsModel::find(array(
                    'limit' => array(
                       'number' => $limit,
                       'offset' => $offset
                    ),
                    'order' => $order
         ));
         $total = AdsModel::count();
      }
      if ($page) {
         return array('items' => $ads, 'total' => $total);
      } else {
         return $ads;
      }
   }

   /**
    * 删除广告
    * 
    * @param array $params 'id'该广告的id
    * @return void
    */
   public function deleteAds($id)
   {
      if ($ads = AdsModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $id
                 )
              ))) {
         $fileRefMgr = $this->di->get('FileRefManager');
         $fileRefMgr->removeFileRef($ads->getFileRefs());
         return $ads->delete();
      } else {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NO_RECORD'), $errorType->code('E_NO_RECORD')
         ));
      }
   }
   /**
    * 初始化广告位置表信息
    * 
    * @param array $params 初始化广告位置数组信息
    * @param integer $pid 设置的pid
    * @return void
    */
   public function initAdsModuleTable($params, $pid)
   {
      if (!is_array($params)) {
         return;
      }
      foreach ($params as $key => $value) {
         $adsmodule = new AdsModule();
         if (is_array($value)) {
            $adsmodule->setPid($pid);
            $adsmodule->setName($key);
            $adsmodule->save();
            $id = $adsmodule->getId();
            $this->initAdsModuleTable($value, $id);
         }else {
            $adsmodule->setPid($pid);
            $adsmodule->setName($value);
            $adsmodule->save();
         }
      }
   }

}