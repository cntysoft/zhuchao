<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\Site\CmMgr\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\Site\CmMgr\Constant;

/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Mgr extends AbstractHandler
{
   /**
    * 获取所有当前系统支持的内容模型列表
    */
   public function getAllModels()
   {
      $data = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'getModelList'
      );
      $ret = array();
      foreach ($data as $item) {
         $ret[] = $item->toArray(true);
      }
      return $ret;
   }

   /**
    * 获取指定ID的内容模型数据
    *
    * <code>
    *       array(
    *           'id' => 'id'
    *       );
    * </code>
    */
   public function getModelInfo(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $data = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'getModelInfo',
         array(
            $params['id']
         )
      );
      return $data->toArray(true);
   }

   /**
    * 增加一个指定的内容模型元信息
    * <code>
    *   array(
    *       'key' => 'key',
    *       'data' => 'data'
    *   );
    * </code>
    * @param array $params
    */
   public function addModelMeta(array $params)
   {
      $key = $params['key'];
      unset($params['key']);
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'addModelMeta',
         array(
            $key,
            $params
         )
      );
   }

   /**
    * 更新内容模型元信息
    */
   public function updateModelMeta(array $params)
   {
      $key = $params['key'];
      unset($params['key']);
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'updateModelMeta',
         array(
            $key,
            $params
         )
      );
   }

   /**
    * 删除一个内容模型
    *
    * <code>
    *   array(
    *      'key' => 'key'
    *   );
    * </code>
    * @param array $params
    */
   public function deleteModel(array $params)
   {
      $this->checkRequireFields($params, array(
         'key'
      ));
      $key = $params['key'];
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'deleteModel',
         array(
            $key
         )
      );
   }

   /**
    * 获取指定模型的所有字段
    *
    * @param array $params
    */
   public function getModelFields(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $fields = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'getFields',
         array(
            $params['id']
         )
      );
      $ret = array();
      foreach ($fields as $field) {
         $ret[] = $field->toArray(true);
      }
      return $ret;
   }

   /**
    * 增加一个字段
    *
    * <code>
    *  array(
    *       'modelKey' => 'model key',
    *       'data' => array(
    *           'fieldKey' => 'fieldValue'
    *       )
    *  );
    * </code>
    * @param array $params
    */
   public function addField(array $params)
   {
      $this->checkRequireFields($params, array('modelKey', 'data'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'addFields',
         array(
            $params['modelKey'], array($params['data'])
         )
      );
   }

   /**
    * 删除一个字段
    *
    * @param array $params
    */
   public function deleteField(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'deleteField',
         array(
            $params['id']
         )
      );
   }

   /**
    * 加载指定模型制定字段的信息
    *
    * <code>
    *   array(
    *       'mid' => 'model id',
    *       'fid' => 'field id'
    *   );
    * </code>
    * @param array $params
    */
   public function getField(array $params)
   {
      $this->checkRequireFields($params, array('fid'));
      $field = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'getField',
         array(
            $params['fid']
         )
      );
      if (!$field) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
               $errorType->msg('E_MODEL_FIELD_NOT_EXIST', $params['fid']), $errorType->code('MODEL_FIELD_NOT_EXIST')),
            $this->getErrorTypeContext());
      }
      return $field->toArray(true);
   }

   /**
    * <code>
    * array(
    *   'id' => 'field id',
    *   'data' => 'field data'
    * )
    * </code>
    *
    * @param array $params
    */
   public function updateField(array $params)
   {
      $this->checkRequireFields($params, array('id', 'data'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MGR,
         'updateField',
         array(
            $params['id'],
            $params['data']
         )
      );
   }

}