<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\Yunzhan\CmMgr;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Stdlib\Filesystem;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Cntysoft\Kernel;
use Cntysoft\Stdlib\ArrayUtils;
use App\Yunzhan\CmMgr\Model\Content as ContentModel;
use App\Yunzhan\CmMgr\Model\Fields as FieldsModel;
use Phalcon\Db\Column;
use Cntysoft\Kernel\ConfigProxy;

/**
 * 内容模型管理类
 *
 * field字段结构
 * <code>
 *      array(
 *          array(
 *              'name' => 'fieldName',
 *              'type' => 'type',
 *              'column' => 'col',
 *              'allowHtml' => 'boolean',
 *              'needSerialize' => 'boolean',
 *              'length' => 'integer'//当为string的时候指定的长度
 *          )
 *      );
 * </code>
 */

/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Mgr extends AbstractLib
{
   protected static $buildInMKeys = array(
      'Article',
      'Job'
   );
   /**
    * @var array
    */
   protected $sysFields = array();
   /**
    * 字段到数据库之间的映射数据
    *
    * @var array $typeMap
    */
   protected $typeMap = array();
   /**
    * 是否初始化内置模型, 仅供调试使用
    *
    * @var boolean initBuildModelsPhase
    */
   private $initBuildModelsPhase = false;

   public function __construct()
   {
      parent::__construct();
      $this->typeMap = array(
         //官方支持的
         'integer'  => Column::TYPE_INTEGER,
         'boolean' => Column::TYPE_BOOLEAN,
         'date'     => Column::TYPE_DATE,
         'datetime' => Column::TYPE_DATETIME,
         'decimal'  => Column::TYPE_DECIMAL,
         'float'    => Column::TYPE_FLOAT,
         'double'   => Column::TYPE_FLOAT,
         'char'     => Column::TYPE_CHAR,
         'text'     => Column::TYPE_TEXT,
         'varchar'  => Column::TYPE_VARCHAR
      );
   }

   /**
    * 获取内置的模型名称
    *
    * @return array
    */
   public static function getBuildInMKeys()
   {
      return self::$buildInMKeys;
   }

   /**
    * 获取指定模型的字段信息
    *
    * @param int $mid
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getFields($mid)
   {
      //按照优先级获取
      return FieldsModel::find(array(
         'mid = ?0',
         'bind'  => array(
            0 => (int)$mid
         ),
         'order' => 'priority DESC'
      ));
   }

   /**
    * 获取指定内容模型的指定字段信息
    *
    * @param int $id
    * @return \App\Yunzhan\CmMgr\Model\Fields
    */
   public function getField($id)
   {
      return FieldsModel::findFirst((int)$id);
   }

   /**
    * 更新一个字段的相关信息
    *
    * @param int $id
    * @param array $field
    */
   public function updateField($id, array $field)
   {
      $fieldModel = FieldsModel::findFirst($id);
      if (!$fieldModel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_MODEL_FIELD_NOT_EXIST', $id), $errorType->code('E_MODEL_FIELD_NOT_EXIST')), $errorType);
      }
      if ($fieldModel->getSystem() && $fieldModel->getName() === 'nodeId') {
         unset($field['display']);
      }
      //注销危险字段
      unset($field['model']);
      unset($field['id']);
      unset($field['system']);
      unset($field['name']);
      unset($field['virtual']);
      unset($field['type']);
      //在这里说两句， 一个模型的字段类型是不会允许修改的，一旦修改可能导致已经发表的信息无效
      $fieldModel->assignBySetter($field);
      $fieldModel->update();
   }


   /**
    * 获取系统所有的字段名称
    *
    * @return array
    */
   protected function getSysFields()
   {
      if (null == $this->sysFields) {
         $sysfields = include $this->getAppObject()->getDataDir() . DS . 'SystemFields.php';
         foreach ($sysfields as $field) {
            $this->sysFields[] = $field['name'];
         }
      }
      return $this->sysFields;
   }

   /**
    * 给内容模型添加指定的字段
    *
    * @param string $modelKey
    * @param array $fields
    */
   public function addFields($mkey, array $fields)
   {
      set_time_limit(0);
      $model = ContentModel::findFirst(array(
         '[key] = ?0',
         'bind' => array(
            0 => $mkey
         )
      ));
      if (!$model) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_CONTENT_MODEL_NOT_EXIST', $mkey), $errorType->code('E_CONTENT_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }

      //先检查是否存在
      //基本的思想是每次添加新的字段集合的时候 先将老的字段读出来
      //因为生成Model文件的时候需要用
      $mid = $model->getId();
      $mfields = $model->getModelFields();
      $existFields = array();
      $deleteItems = array();
      $typeMap = $this->typeMap;
      $allowTypes = array_keys($typeMap);
      $fm = new FieldsModel();
      $requires = $fm->getRequireFields(array('id'));
      foreach ($mfields as $f) {
         $values = $f->toArray(true);
         $deleteItems[] = $f->getId();
         unset($values['id']);
         $existFields[$f->getName()] = $values;
      }
      foreach ($fields as $key => $field) {
         $field += array(
            'system'   => 0,
            'priority' => 0,
            'virtual'  => 0,
            'display'  => 1
         );
         $field['mid'] = $mid;
         Kernel\ensure_array_has_fields($field, $requires);
         if (array_key_exists($field['name'], $existFields)) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_MODEL_FIELD_ALREADY_EXIST', $field['name']), $errorType->code('E_MODEL_FIELD_ALREADY_EXIST')), $this->getErrorTypeContext());
         }
         if (!$field['virtual']) {
            //如果指定了就直接用
            if (!array_key_exists('type', $field)) {
               $errorType = $this->getErrorType();
               //检查类型
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_MODEL_FIELD_TYPE_RROR', 'type field not exist'), $errorType->code('E_MODEL_FIELD_TYPE_RROR')), $this->getErrorTypeContext());
            }
            $field['type'] = strtolower($field['type']);
            if (!in_array($field['type'], $allowTypes)) {
               $errorType = $this->getErrorType();
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_MODEL_FIELD_TYPE_RROR', 'type ' . $field['type'] . ' is not supported'), $errorType->code('E_MODEL_FIELD_TYPE_RROR')), $this->getErrorTypeContext());
            }

            $field['type'] = $typeMap[$field['type']];
         }
         $fields[$key] = $field;
      }
      $mergeFields = array_merge($existFields, $fields);
      //这个地方是否需要回滚保证
      /**
       * @TODO
       */
      try {
         if(!in_array($mkey, self::$buildInMKeys)){
            $this->generateModelClassFile($mkey, $mergeFields);
         }
         $this->addFieldsToDatabase($model, $fields);
         set_time_limit((int)ini_get("max_execution_time"));
      } catch (\Exception $ex) {
         throw $ex;
      }
   }

   /**
    * 删除指定的模型字段信息
    *
    * @param int $id
    */
   public function deleteField($id)
   {
      set_time_limit(0);
      $field = FieldsModel::findFirst($id);
      if (!$field) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_MODEL_FIELD_NOT_EXIST', $id), $errorType->code('E_MODEL_FIELD_NOT_EXIST')), $errorType);
      }
      if ($field->getSystem()) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_CANOT_DELETE_SYS_FIELD'), $errorType->code('E_CANOT_DELETE_SYS_FIELD')), $errorType);
      }
      $delete = $field->getName();
      $model = $field->getContentModel();
      $modelKey = $model->getKey();
      $fields = $model->getModelFields();
      //获取目标字段集合
      $target = array();
      foreach ($fields as $f) {
         if (!$f->getSystem() && $delete !== $f->getName()) {
            $target[] = $f->toArray(true);
         }
      }
      if(!in_array($modelKey, self::$buildInMKeys)){
         //内置的不生成吧
         $this->generateModelClassFile($this->getYunzhanId(), $modelKey, array()); //统一处理
      }
      $this->deleteFieldFromDatabase($modelKey, $field);
      $field->delete();
      set_time_limit((int)ini_get("max_execution_time"));
   }

   /**
    * 将相应的字段添加到数据库中
    *
    * @param \App\Yunzhan\CmMgr\Model\Content $model
    * @param array $fields
    */
   protected function addFieldsToDatabase($model, array $fields)
   {

      //主要任务是更新模型表 和 字段数据的添加
      //virtual字段需要在字段列表中出现，但是不需要在模型表里面出现
      $modelKey = $model->getKey();
      $tableName = $this->getTableName($modelKey);
      $db = Kernel\get_db_adapter();
      $mysqlDialect = $db->getDialect();
      $dbname = str_replace('.', '__', $this->getTargetDbName());
      foreach ($fields as $field) {
         $field+= array(
            'priority' => 0
         );
         
         $query = $mysqlDialect->addColumn($tableName, $dbname, new \Phalcon\Db\Column($field['name'], array(
            'type'    => $field['type'],
            'size'    => isset($field['length']) ? $field['length'] : null,
            'notNull' => $field['require']
         )));
         $fmodel = new FieldsModel();
         unset($field['length']);
         unset($field['needSerialize']);
         $fmodel->assignBySetter($field);
         $fmodel->create();
         if ($field['virtual'] || $field['system']) {
            continue;
         }
         $query = str_replace('__', '.', $query);
         $db->execute($query);
      }
   }

   /**
    * 从数据库删除一个字段
    *
    * @param string $modelKey
    * @param \App\Yunzhan\CmMgr\Model\Fields $field
    */
   protected function deleteFieldFromDatabase($modelKey, $field)
   {
      //主要任务是更新模型表 和 字段数据的添加
      $tableName = $this->getTableName($modelKey);
      $db = Kernel\get_db_adapter();
      $mysqlDialect = $db->getDialect();
      $dbname = str_replace('.', '__', $this->getTargetDbName());
      $query = $mysqlDialect->dropColumn($tableName, $dbname, $field->getName());
      $query = str_replace('__', '.', $query);
      //这里我们更新表的字段可能消耗的时间会很长，数据越多时间越长
      $db->execute($query);
   }

   /**
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getModelList()
   {
      return ContentModel::find();
   }

   /**
    * 设置系统默认的模型
    *
    * @param array $meta
    * @throws \Exception
    */
   public function setupBuildInModelFields(array $meta)
   {
      //清除环境
      $db = Kernel\get_db_adapter();
      $models = array();
      foreach ($meta as $mkey => $mdata) {
         $tables = array(
            $this->getTableName($mkey)
         );
         $models[$mkey] = $tables;
      }
      try {
         $db->begin();
         $this->emptyModels($models);
         $this->initBuildModelsPhase = true;
         foreach ($meta as $mkey => $data) {
            $this->addModelMeta($mkey, $data['meta'], false);
            if (array_key_exists('fields', $data)) {
               $this->addFields($mkey, $data['fields']);
            }
         }
         $this->initBuildModelsPhase = false;
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * @see App\Yunzhan\CmMgr::setupBuildInModelFields();
    * 这个函数内置的指定的路径
    */
   public function generateBuildInModelFields()
   {
      $metaFile = $this->getAppObject()->getDataDir().DS.'BuildInModelFields.php';
      if(!file_exists($metaFile)){
         Kernel\throw_exception(new Exception(
            Kernel\StdErrorType::msg('E_FILE_NOT_EXIST', $metaFile),
            Kernel\StdErrorType::code('E_FILE_NOT_EXIST')));
      }
      $this->setupBuildInModelFields(include $metaFile);
   }
   /**
    * 清空内容模型
    *
    * 这个函数只是调试的时候使用，不能用于其他情况，函数危险性很高
    */
   protected function emptyModels(array $models)
   {
      $db = Kernel\get_db_adapter();
      $mysqlDialect = $db->getDialect();
      $dbname = $this->getTargetDbName();
      foreach ($models as $mkey => $tables) {
         $modelFilename = $this->getModelFile($mkey);
         if (!in_array($mkey, self::$buildInMKeys) && file_exists($modelFilename)) {
            Filesystem::deleteFile($modelFilename);
         }
         foreach ($tables as $table) {
            $query = sprintf('DROP TABLE IF EXISTS `%s`.`%s`', $dbname, $table);
            $db->execute($query);
         }
      }
      
      Kernel\truncate_site_table('join_category_content_model_template');
      Kernel\truncate_site_table('app_site_cmmgr_cmodel');
      Kernel\truncate_site_table('app_site_cmmgr_cmodel_fields');

      //清空缓存
      $this->clearCache();
   }
   /**
    * 获取模型数据
    *
    * @param int $mid
    * @return \App\Yunzhan\CmMgr\Model\Content
    */
   public function getModelInfo($mid)
   {
      return ContentModel::findFirst((int) $mid);
   }

   /**
    * 获取模型对模型的ID的映射表
    *
    * @param int $siteId
    * @return array
    */
   public function getKey2IdMap()
   {
      $cacher = $this->getAppObject()->getCacheObject();
      $key = $this->generateKey2IdCacheKey();
      if(!$cacher->exists($key)){
         $ms = $this->getModelList();
         $map = array(
            0 => array(),
            1 => array()
         );
         foreach ($ms as $item) {
            $id = $item->getId();
            $key = $item->getKey();
            $map[0][$key] = $id;
            $map[1][$id] = $key;
         }
         $cacher->save($key, $map);
         return $map;
      }
      return $cacher->get($key);
   }

   /**
    * 通过模型ID 获取model key
    *
    * @param int $id
    * @return string
    */
   public function getCModelKeyById($id)
   {
      $map = $this->getKey2IdMap();
      $map = $map[1];
      if (!array_key_exists($id, $map)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_MODEL_ID_NOT_EXIST', $id),
            $errorType->code('E_MODEL_ID_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $map[$id];
   }

   /**
    * 通过模型的Key获取模型ID
    *
    * @param string $key
    * @return int
    */
   public function getModelIdByKey($key)
   {
      $map = $this->getKey2IdMap();
      $map = $map[0];
      if (!array_key_exists($key, $map)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_MODEL_ID_NOT_EXIST', $key),
            $errorType->code('E_MODEL_ID_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $map[$key];
   }

   /**
    * 添加一个新的内容模型
    *
    * @param string $modelKey 模型识别KEY
    * @param array $data 内容模型基本元信息
    * @param boolean $force 存在了是否强制添加
    */
   public function addModelMeta($modelKey, array $data, $initSysFields = true)
   {
      //判断存在的话就不让添加
      //通过检测内容模型的数据模型文件
      $modelFile = $this->getModelFile($modelKey);
      if (!in_array($modelKey, self::$buildInMKeys) && file_exists($modelFile)){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
               $errorType->msg('E_CONTENT_MODEL_ALREADY_EXIST', $modelKey),
               $errorType->code('E_CONTENT_MODEL_ALREADY_EXIST')),
            $this->getErrorTypeContext()
         );
      }
      $model = new ContentModel();
      $requires = $model->getRequireFields(array('id'));
      //保证模型的必要值
      $data += array(
         'enabled' => 1,
         'editor' => 'StdEditor',
         'dataSaver' => 'StdSaver'
      );
      //自定义添加的都强制false
      if (!$this->initBuildModelsPhase) {
         $data['buildIn'] = 0;
      } else {
         $data['buildIn'] = 1;
      }
      $data['key'] = $modelKey;
      Kernel\ensure_array_has_fields($data, $requires);
      //检查两个重要的类名称是否存在
      $this->editorFileExist($modelKey, $data['editor']);
      $this->saverFileExist($modelKey, $data['dataSaver']);
      $db = Kernel\get_db_adapter();
      $tableName =  $this->getTableName($modelKey);
      $mysqlDialect = $db->getDialect();
      $dbname = str_replace('.', '__', $this->getTargetDbName());
      $step = 0;
      try {
         $db->begin();
         //这里创建一个模型表
         $definition = array(
            'columns' => array(
               new Column('id', array(
                     'type'          => 'int',
                     'primary'       => true,
                     'unsigned'      => true,
                     'size'          => 11,
                     'autoIncrement' => true)
               )
            )
         );
         //创建模型表
         //修复phalcon的bug
         
         $query = $mysqlDialect->createTable($tableName,$dbname, $definition);
         $query = str_replace('__', '.', $query);
         $db->execute($query);
         $step++;
         //生成模型文件和类文件
         if(!in_array($modelKey, self::$buildInMKeys)){
            //内置的不生成吧
            $this->generateModelClassFile($modelKey, array()); //统一处理
         }
         $step++;
         $model->assignBySetter($data);
         $model->create();
         if ($initSysFields) {
            $this->initSysFields($model);
         }
         $db->commit();
      } catch (\Exception $ex) {
         $modelFilename = $this->getModelFile($modelKey);
         if ($step == 2 && file_exists($modelFilename)) {
            Filesystem::deleteFile($modelFilename);
         }
         if ($step == 1 && $this->tableExist($tableName, $db)) {
            $query = $mysqlDialect->dropTable($tableName, $dbname);
            $query = str_replace('__', '.', $query);
            $db->execute($query);
         }
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }

      //清除缓存
      $this->clearCache();

   }

   public function updateModelMeta($modelKey, array $data)
   {
      //删除危险字段
      unset($data['id']);
      unset($data['key']);
      unset($data['buildIn']);
      $model = ContentModel::findFirst(array(
         '[key] = ?0',
         'bind' => array(
            0 => $modelKey
         )
      ));
      if (!$model) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_CONTENT_MODEL_NOT_EXIST', $modelKey), $errorType->code('E_CONTENT_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      //检查两个重要的类名称是否存在
      if (array_key_exists('editor', $data)) {
         $this->editorFileExist($modelKey, $data['editor']);
      }
      if (array_key_exists('dataSaver', $data)) {
         $this->saverFileExist($modelKey, $data['dataSaver']);
      }

      $model->assignBySetter($data);
      $model->update();

      //清除缓存
      $this->clearCache();
   }

   /**
    * 删除一个指定的内容模型
    *
    * @param string $modelKey
    * @throws \Exception
    */
   public function deleteModel($modelKey)
   {
      //首先读出来
      $model = ContentModel::findFirst(array(
         '[key] = ?0',
         'bind' => array(
            0 => $modelKey
         )
      ));
      if (!$model) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_CONTENT_MODEL_NOT_EXIST', $modelKey), $errorType->code('E_CONTENT_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      if ($model->getBuildIn()) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_CANOT_DELETE_BUILDIN_MODEL', $modelKey), $errorType->code('E_CANOT_DELETE_BUILDIN_MODEL')), $this->getErrorTypeContext());
      }
      $db = Kernel\get_db_adapter();

      try {
         $db->begin();
         $this->getAppCaller()->call(
            \App\Yunzhan\Category\Constant::MODULE_NAME,
            \App\Yunzhan\Category\Constant::APP_NAME,
            \App\Yunzhan\Category\Constant::APP_API_STRUCTURE,
            'deleteC2MapByModel',
            array(
               $model->getId()
            )
         );
         $mysqlDialect = $db->getDialect();
         //删除相关文件
         $modelFilename = $this->getModelFile($modelKey);
         if(!in_array($modelKey, self::$buildInMKeys)){
            if('StdEditor' !== $model->getEditor()){
               $editorFilename = $this->getEditorJsFile($modelKey, $model->getEditor());
               if(file_exists($editorFilename)){
                  Filesystem::deleteFile($editorFilename);
               }
            }
            if('StdSaver' !== $model->getDataSaver()){
               $dataSaverFilename = $this->getSaverPhpFile($modelKey, $model->getDataSaver());
               if(file_exists($dataSaverFilename)){
                  Filesystem::deleteFile($dataSaverFilename);
               }
            }
         }
         $dbname = str_replace('.', '__', $this->getTargetDbName());
         if (file_exists($modelFilename)) {
            Filesystem::deleteFile($modelFilename);
         }
         $tableName = $this->getTableName($modelKey);
         //删除节点内容模型模板映射
         //判断书否具有该内容模型的数据
         //需要删除的地方，节点<->内容模型链接表里面本模型的连接
         //内容模型相关的信息
         $model->delete();
         //删除模型表
         $query = $mysqlDialect->dropTable($tableName, $dbname);
         $query = str_replace('__', '.', $query);
         $db->execute($query);
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }

      //清除缓存
      $this->clearCache();
   }

   /**
    * 初始化模型的系统类型的字段
    *
    * @param \App\Yunzhan\CmMgr\Model\Content $model
    */
   public function initSysFields($model)
   {
      $sysfields = include $this->getAppObject()->getDataDir() . DS . 'SystemFields.php';
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $typeMap = $this->typeMap;
         foreach ($sysfields as $field) {
            $field = ArrayUtils::apply($field, array(
               'mid'      => $model->getId(),
               'system'   => 1,
               'display'  => 1,
               'priority' => 0,
               'virtual'  => 0
            ));
            unset($field['length']);
            $m = new FieldsModel();
            $m->assignBySetter($field);
            $m->create();
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * @param string $modelKey
    * @return string
    */
   protected function getTableName($modelKey)
   {
      return 'app_site_cmmgr_u_'.strtolower($modelKey);
   }
   /**
    * @param string $modelKey
    * @param array $fields
    */
   protected function generateModelClassFile($modelKey, $fields)
   {
      $modelCls = $this->getModelCls($modelKey);
      $modelFilename = $this->getModelFile($modelKey);
      $fileGen = new FileGenerator();
      $fileGen->setNamespace('App\Yunzhan\CmMgr\Model');
      $fileGen->setUses(array(
         array(
            'use' => 'Cntysoft\Phalcon\Mvc\Model',
            'as' => 'BaseModel'
         )
      ));
      array_unshift($fields, array(
         'name'    => 'id',
         'type'    => Column::TYPE_INTEGER,
         'column'  => 'id',
         'system'  => false,
         'virtual' => false
      ));
      $clsGen = new ClassGenerator($modelKey);
      $getSourceBody = sprintf('return "%s";', $this->getTableName($modelKey));
      //添加获取表名称的方法
      $clsGen->addMethod('getSource', array(), null, $getSourceBody);
      $clsGen->setExtendedClass('BaseModel');
      $fileGen->setClass($clsGen);
      foreach ($fields as $field) {
         if ($field['system'] || $field['virtual']) {
            continue;
         }
         $convertExpr = '';
         if(Column::TYPE_BOOLEAN === $field['type'] || Column::TYPE_INTEGER === $field['type']){
            $convertExpr = '(int)';
         }
         $fieldName = $field['name'];
         $clsGen->addProperty($fieldName, null, PropertyGenerator::FLAG_PRIVATE);
         if (isset($field['needSerialize'])) {
            $needSerialize = (boolean) $field['needSerialize'];
         } else {
            $needSerialize = false;
         }
         $getter = 'get' . ucfirst($fieldName);
         $setter = 'set' . ucfirst($fieldName);
         $setterBody = '';
         if ($needSerialize) {
            $getterBody = <<<TPL
if(null == \$this->{$fieldName}){
    return null;
}
return unserialize(\$this->{$fieldName});
TPL;
         } else {
            $getterBody = sprintf("return %s\$this->{$fieldName};", $convertExpr);
         }

         if ($needSerialize) {
            $setterBody .= "\n\${$fieldName} = serialize(\${$fieldName});";
         }
         $setterBody .= sprintf("\n\$this->{$fieldName} = %s\${$fieldName};\nreturn \$this;", $convertExpr);

         $clsGen->addMethod($getter, array(), null, $getterBody);
         $clsGen->addMethod($setter, array($fieldName), null, $setterBody, '@return \\' . $modelCls);
      }
      Filesystem::filePutContents($modelFilename, $fileGen->generate());
   }

   /**
    * 判断编辑器文件是否存在
    *
    * @param string $mkey
    * @param strig $editor
    * @throws \Exception
    */
   protected function editorFileExist($mkey, $editor)
   {
      $editorFilename = $this->getEditorJsFile($mkey, $editor);
      if (!file_exists($editorFilename)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_EDITOR_CLS_NOT_EXIST', $editor), $errorType->code('E_EDITOR_CLS_NOT_EXIST')), $this->getErrorTypeContext());
      }
   }

   /**
    * 判断内容模型保存器文件是否存在
    *
    * @param string $mkey
    * @param string $saver
    * @throws \Exception
    */
   protected function saverFileExist($mkey, $saver)
   {
      $saverFilename = $this->getSaverPhpFile($mkey, $saver);
      if (!file_exists($saverFilename)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_DATA_SAVER_NOT_EXIST', $saver), $errorType->code('E_DATA_SAVER_NOT_EXIST')), $this->getErrorTypeContext());
      }
   }

   /**
    * 根据内容模型识别KEY获取内容模型
    *
    * @param string $mkey
    * @return string
    */
   public function getModelCls($mkey)
   {
      return 'App\Yunzhan\CmMgr\Model'.'\\'.$mkey;
   }

   /**
    * @param string $mkey
    * @return string
    */
   protected function getModelFile($mkey)
   {
      return $this->getAppObject()->getModelDir().DS. $mkey . '.php';
   }

   /**
    * 获取编辑器文件名称
    *
    * @param string $mkey
    * @param string $editor
    * @return string
    */
   protected function getEditorJsFile($mkey, $editor)
   {
      return \Cntysoft\Kernel\StdDir::getAppRootDir('Yunzhan', 'CmMgr') .DS .'Js'. DS  . 'Editor' .DS. $editor . '.js';
   }

   /**
    * 获取保存器的文件名称
    *
    * @param string $mkey
    * @param string $saver
    * @return string
    */
   protected function getSaverPhpFile($mkey, $saver)
   {
      return \Cntysoft\Kernel\StdDir::getAppRootDir('Yunzhan', 'Content').DS.'Saver'.DS.$saver.'.php';
   }

   /**
    * @param string $name
    * @param \Phalcon\Db\Adapter\Pdo\Mysql $db
    * @return boolean
    */
   protected function tableExist($name, $db)
   {
      $dialect = $db->getDialect();
      $query = $dialect->tableExists($name);
      $r = $db->fetchOne($query, \Phalcon\Db::FETCH_NUM);
      return $r[0] == '1' ? true : false;
   }

   protected function getTargetDbName()
   {
      return \Cntysoft\Kernel\get_site_db_name();
   }

   /**
    * 生成缓存键值
    *
    * @return string
    */
   protected function generateKey2IdCacheKey()
   {
      return md5($this->getAppObject()->getAppKey().__METHOD__);
   }

   /**
    * 清空缓存
    */
   public function clearCache()
   {
      $cacher = $this->getAppObject()->getCacheObject();
      $key = $this->generateKey2IdCacheKey();
      if($cacher->exists($key)) {
         $cacher->delete($key);
      }
   }
}