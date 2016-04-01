<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Content;
use App\Site\Content\Constant as CONT_CONST;
use App\Site\CmMgr\Model\General as GeneralModel;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\Site\CmMgr\Constant as CMM_CONST;
/**
 * 信息管理类，提供基本的信息操作接口
 */
class Manager extends AbstractLib
{
   /**
    * @var array $adapterPool
    */
   protected $adapterPool = array();

   /**
    * 获取指定文档的基本信息
    *
    * @param int $id
    * @return \App\Site\CmMgr\Model\General
    */
   public function getGInfo($id)
   {
      return GeneralModel::findFirst((int)$id);
   }

   /**
    * 信息添加接口
    *
    * @param int $cmid
    * @param array $data
    * @return \App\Site\CmMgr\Model\General
    */
   public function add($cmid, array $data)
   {
      //去掉危险字段
      unset($data['id']);
      unset($data['itemId']);
      unset($data['cmodelId']);
      if (!isset($data['status'])) {
         $data['status'] = CONT_CONST::INFO_S_DRAFT;
      }
      $cmm = $this->getAppCaller()->getAppObject('Site', 'CmMgr', 'Mgr');
      $cmodel = $cmm->getModelInfo($cmid);
      if (!$cmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
               $errorType->msg('E_TARGET_MODEL_NOT_EXIST', $cmid),
               $errorType->code('E_TARGET_MODEL_NOT_EXIST')),
            $this->getErrorTypeContext());
      }
      //给默认值
      $data['cmodelId'] = $cmid;
      $data['isDeleted'] = 0;
      $data['inputTime'] = time();
      $data += array(
         'hits' => mt_rand(100, 1000),
         'priority' => 0
      );
      if(Constant::INFO_S_VERIFY == $data['status']){
         $data['passTime'] = time();
      }
      $data['isDeleted'] = 0;
      if (!isset($data['author']) && isset($data['editor'])) {
         $data['author'] = $data['editor'];
      }
      $gmodel = new GeneralModel();
      //检查基本信息表数据是否完整
      $requires = $gmodel->getRequireFields(array(
         'id', 'itemId', 'modelId'
      ));
      Kernel\ensure_array_has_fields($data, $requires);
      $nid = $data['nodeId'];
      if (!$this->getAppCaller()->call('Site', 'Category', 'Structure', 'hasNode', array($nid))) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(
            new Exception(
               $errorType->msg('E_TARGET_CATEGORY_NOT_EXIST', $nid),
               $errorType->code('E_TARGET_CATEGORY_NOT_EXIST')),
            $this->getErrorTypeContext()
         );
      }
      //判断信息是否存在
      $isExist = $this->getAppCaller()->call('Site', 'Content', 'InfoList', 'titleExist', array($cmid, $nid, $data['title']));
      if ($isExist) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_ALREADY_EXIST', $data['title'], $nid, $cmid), $errorType->code('E_INFO_ALREADY_EXIST')
         ), $this->getErrorTypeContext());
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         //基本思想是先插入基本信息， 然后处理模型相关的信息
         //@TODO 这个是不是一个bug 没有这个函数
         $gfields = $gmodel->getDataFields();
         $gdata = array();
         foreach ($gfields as $key) {
            if (array_key_exists($key, $data)) {
               $gdata[$key] = $data[$key];
               unset($data[$key]);
            }
         }
         $saver = $cmodel->getDataSaver();
         $adapter = $this->getAppCaller()->getAppObject(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            'Saver\\'.$saver
         );
         $itemId = $adapter->add($gdata, $data, $cmodel);
         if('ImageSaver' == $saver){
            $queryData = $adapter->getQueryData();
            $gmodel->setDefaultPicUrl($queryData['defaultPicUrl']);
            unset($queryData['defaultPicUrl']);
         }
         $gdata['itemId'] = $itemId;
         //更新时间
         $gdata += array(
            'updateTime' => time()
         );
         $gmodel->create($gdata);
         if('ImageSaver' == $saver){
            $queryData['gid'] = $gmodel->getId();
            $queryData['nodeId'] = $gmodel->getNodeId();
            $adapter->setEffectImageQuery($queryData);
         }         
         $db->commit();
         return $gmodel;
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 读取指定Id的信息, 信息不存在返回空数组
    *
    * @param int $id
    * @return array
    */
   public function read($id)
   {
      $gmodel = $this->getGInfo($id);
      $ret = array();
      if (!$gmodel) {
         return $ret;
      }
      $ret[] = $gmodel;
      $cmid = $gmodel->getCmodelId();
      $cmm = $this->getAppCaller()->getAppObject('Site', 'CmMgr', 'Mgr');
      $cmodel = $cmm->getModelInfo($cmid);
      if (!$cmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_TARGET_MODEL_NOT_EXIST', $gmodel->getCModelId()), $errorType->code('E_TARGET_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      $saver = $cmodel->getDataSaver();
      $adapter = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         'Saver\\'.$saver
      );
      $ret[] = $adapter->read($gmodel, $cmodel);
      if('ImageSaver' == $saver){
         $ret[] = $adapter->getQuery($id);
      }
      return $ret;
   }

   /**
    * 更新内容模型通用的信息项
    *
    * @param int $id
    * @param array $data
    * @return \App\Site\CmMgr\Model\General
    */
   public function updateGeneralInfo($id, array $data)
   {
      $gmodel = $this->getGInfo($id);
      if (!$gmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_IS_NOT_EXIST', $id),
            $errorType->code('E_INFO_IS_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      //删除危险字段
      unset($data['id']);
      unset($data['itemId']);
      unset($data['modelId']);
      unset($data['isDeleted']);
      $gmodel->assignBySetter($data);
      $gmodel->save();
   }

   /**
    * 更新指定模型指定id的数据
    *
    * @param int $id 信息的ID general模型的id
    * @param array $data 待更新的数据
    * @return \App\Site\CmMgr\Model\General
    */
   public function update($id, array $data)
   {
      $gmodel = $this->getGInfo($id);
      if (!$gmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_IS_NOT_EXIST', $id),
            $errorType->code('E_INFO_IS_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      //删除危险字段
      unset($data['id']);
      unset($data['itemId']);
      unset($data['modelId']);
      unset($data['isDeleted']); //moveToTrashcan方法负责
      //基本思想是先插入基本信息， 然后处理模型相关的信息
      //@TODO 这个是不是一个bug 没有这个函数
      $gfields = $gmodel->getDataFields();
      $gdata = array();
      if(!isset($data['updateTime'])){
         $data['updateTime'] = time();
      }
      foreach ($gfields as $key) {
         if (array_key_exists($key, $data)) {
            $gdata[$key] = $data[$key];
            unset($data[$key]);
         }
      }
      $cmid = $gmodel->getCmodelId();
      //检查标题
      if (isset($gdata['title']) && $gdata['title'] !== $gmodel->getTitle()) {
         $nodeId = isset($gdata['nodeId']) ? $gdata['nodeId'] : $gmodel->getNodeId();
         $isExist = $this->getAppCaller()->call('Site', 'Content', 'InfoList', 'titleExist', array($cmid, $nodeId, $gdata['title']));
         if ($isExist) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_INFO_ALREADY_EXIST', $gdata['title'], $nodeId, $cmid), $errorType->code('E_INFO_ALREADY_EXIST')
            ), $this->getErrorTypeContext());
         }
      }
      
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (!empty($data)) {
            $cmm = $this->getAppCaller()->getAppObject('Site', 'CmMgr', 'Mgr');
            $cmodel = $cmm->getModelInfo($cmid);
            if (!$cmodel) {
               $errorType = $this->getErrorType();
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_TARGET_MODEL_NOT_EXIST', $gmodel->getId()), $errorType->code('E_TARGET_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
            }
            $saver = $cmodel->getDataSaver();
            $adapter = $this->getAppCaller()->getAppObject(
               Constant::MODULE_NAME,
               Constant::APP_NAME,
               'Saver\\'.$saver
            );
            if (!empty($gdata)) {
               $gmodel->assignBySetter($gdata);
            }
            
            $adapter->update($gmodel, $data, $cmodel);
            if('ImageSaver' == $saver){
               $queryData = $adapter->getQueryData();
               $gmodel->setDefaultPicUrl($queryData['defaultPicUrl']);
               unset($queryData['defaultPicUrl']);
            }
            
            $gmodel->update();
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 删除指定ID的信息
    *
    * @param int $id
    */
   public function delete($id)
   {
      $gmodel = $this->getGInfo($id);
      if (!$gmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_IS_NOT_EXIST', $id), $errorType->code('E_INFO_IS_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      $cmid = $gmodel->getCmodelId();
      $cmm = $this->getAppCaller()->getAppObject('Site', 'CmMgr', 'Mgr');
      $cmodel = $cmm->getModelInfo($cmid);
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $saver = $cmodel->getDataSaver();
         $adapter = $this->getAppCaller()->getAppObject(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            'Saver\\'.$saver
         );
         $adapter->delete($gmodel, $cmodel);
         $gmodel->delete();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 调用内容模型编辑器的API接口，这个函数应对子模型很复杂的情况设计
    *
    * @param int $mid 内容模型id
    * @param string $name 调用的API的名称
    * @param array $params 调用参数
    * @return array
    */
   public function callModelSaverApi($mid, $name, array $params = array())
   {
      $mid = (int) $mid;
      $cmm = $this->getAppCaller()->getAppObject(
         CMM_CONST::MODULE_NAME,
         CMM_CONST::APP_NAME,
         CMM_CONST::APP_API_MGR
      );
      $cmodel = $cmm->getModelInfo($mid);
      if (!$cmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_TARGET_MODEL_NOT_EXIST', $mid),
            $errorType->code('E_TARGET_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      $saver = $cmodel->getDataSaver();
      $adapter = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         'Saver\\'.$saver
      );
      if (!method_exists($adapter, $name)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_MODEL_SAVER_METHOD_NOT_EXIST', get_class($adapter), $name), $errorType->code('E_MODEL_SAVER_METHOD_NOT_EXIST')), Kernel\normalize_errortype_context($errorType));
      }
      return $adapter->{$name}($params);
   }

   /**
    * 将指定信息重回收站还原
    *
    * @param int | array $ids
    */
   public function restoreFromTrashcan($ids)
   {
      $id = (array) $ids;
      if (count($ids) == 1 && $ids[0] == 0) {
         //还原所有
         $cond = array(
            'isDeleted = 1'
         );
      } else {
         $cond = array(
            'isDeleted = 1',
            GeneralModel::generateRangeCond('id', $ids)
         );
      }
      $cond = implode(' and ', $cond);
      $items = GeneralModel::find($cond);
      foreach ($items as $item) {
         $item->setIsDeleted(false);
         $item->update();
      }
   }

   /**
    * 将指定信息移入回收站
    *
    * @param int | array $ids
    */
   public function moveToTrashcan($ids)
   {
      $ids = (array) $ids;
      $items = GeneralModel::find(array(
         GeneralModel::generateRangeCond('id', $ids)
      ));
      foreach ($items as $item) {
         $item->setStatus(Constant::INFO_S_PEEDING);
         $item->setIsDeleted(true);
         $item->update();
      }
   }

   /**
    * 清空回收站，危险的方法
    */
   public function clearTrashcan()
   {
      $items = GeneralModel::find('isDeleted = 1');
      foreach ($items as $item) {
         $this->delete($item->getId());
      }
   }


   /**
    * 添加信息的点击量
    *
    * @param $id
    * @return bool
    * @throws \Exception
    */
   public function addHit($id)
   {
      $gmodel = $this->getGInfo($id);
      if (!$gmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_IS_NOT_EXIST', $id), $errorType->code('E_INFO_IS_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      $hits = $gmodel->getHits();
      $gmodel->setHits(++$hits);
      return $gmodel->save();
   }
}