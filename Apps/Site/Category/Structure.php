<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Category;
use Cntysoft\Kernel\App\AbstractLib;
use App\Site\Category\Model\Node as NodeModel;
use App\Site\Category\Model\C2Map;
use Cntysoft\Kernel;
use Cntysoft\Stdlib\ArrayUtils;
use App\Site\Content\Constant as ContentConst;
use Cntysoft\Framework\Core\FileRef\Manager as RefManager;

class Structure extends AbstractLib
{
   const C2MAP_M_CLS = 'App\Site\Category\Model\C2Map';
   const NODE_M_CLS = 'App\Site\Category\Model\Node';

   /**
    * 节点树对象
    *
    * @var \Cntysoft\Stdlib\Tree[] $treeRepo
    */
   protected $treeRepo = null;

   /**
    * 不同节点类型的必要字段映射
    *
    * @var array $nType2ClsMap
    */
   protected $nTypeRequiresMap = array();

   /**
    * 获取所有的节点数据，这里通常是整个数据表的数据
    *
    * @return \Phalcon\Mvc\Model\Resultset
    */
   public function getAllNodes()
   {
      return NodeModel::find();
   }

   /**
    * 删除节点内容模型映射表里面的指定的条目
    *
    * @param int $mid
    */
   public function deleteC2MapByModel($mid)
   {
      $modelsManager = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s  WHERE modelId = ?0', self::C2MAP_M_CLS);
      $modelsManager->executeQuery($query, array(
         0 => (int) $mid
      ));

      //删除缓存
      $this->clearCache();
   }

   /**
    * 获取节点管理城程序支持的节点类型
    *
    * @return array
    */
   public function getSupportNodeTypes()
   {
      return array(
         Constant::N_TYPE_GENERAL,
         Constant::N_TYPE_LINK,
         Constant::N_TYPE_SINGLE
      );
   }

   /**
    * 根据节点ID，获取节点路径
    *
    * @param string $id
    * @return array
    */
   public function getNodePath($id)
   {
      $tree = $this->getTreeObject();
      $ret = array();
      if (!$tree->isNodeExist($id)) {
         return $ret;
      }

      $node = $tree->getValue($id);
      $ret[] = $node;
      while ($id = $tree->getParent($id)) {
         $ret[] = $tree->getValue($id);
      }
      return $ret;
   }

   /**
    * 获取节点动态地址
    *
    * @param string $nodeIdentifier
    * @return string
    */
   public function getNodeUrl($nodeIdentifier)
   {
      return str_replace('{CategoryId}', $nodeIdentifier, \Cntysoft\CATEGORY_ROUTE_N_PAGE);
   }

   /**
    * 获取带分页列表页的分页地址
    *
    * @param string $nodeIdentifier
    * @param integer $pageId
    * @return string
    */
   public function getPageUrl($nodeIdentifier, $pageId)
   {
      return str_replace(array('{CategoryId}', '{PageId}'), array(
         $nodeIdentifier,
         $pageId
      ), \Cntysoft\CATEGORY_ROUTE_W_PAGE);
   }

   /**
    * 添加一个指定类型的节点， 注意系统会按照节点的类型进行一些检查
    *
    * modelTplMap
    *
    * <code>
    * array(
    *   'modelId' => 'tpl'
    * );
    * </code>
    *
    * @param $nodeType
    * @param array $data
    * @param bool $filterId
    * @return int
    * @throws Kernel\Exception
    * @throws \Exception
    */
   public function addNode($nodeType, array $data, $filterId = false)
   {
      if (!in_array($nodeType, $this->getSupportNodeTypes())) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_TYPE_NOT_SUPPORT', $nodeType), $errorType->code('E_NODE_TYPE_NOT_SUPPORT')), $this->getErrorTypeContext());
      }
      //去掉危险字段
      unset($data['nodeType']);
      if ($filterId) {
         unset($data['id']);
      }
      /**
       * 在添加节点的时候需要通过按照节点类型进行字段检查，不同的类型只检查自己的
       */
      $requires = $this->getTypeRequiresByType($nodeType);
      $data['nodeType'] = $nodeType;
      $this->assignDefaultValues($nodeType, $data);
      Kernel\ensure_array_has_fields($data, $requires);
      //检查节点是否存在
      if (NodeModel::findFirst(array(
         'nodeIdentifier = ?0',
         'bind' => array(
            0 => $data['nodeIdentifier']
         )
      ))) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_IDENTIFIER_EXIST', $data['nodeIdentifier']), $errorType->code('E_NODE_IDENTIFIER_EXIST')), $this->getErrorTypeContext());
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $modesTemplate = array();
         if ($nodeType == Constant::N_TYPE_GENERAL) {
            //检查文件夹是否存在
            if ($this->nodeDirExist($data['pid'], $data['dirname'])) {
               $errorType = $this->getErrorType();
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_NODE_DIR_EXIST', $data['dirname']), $errorType->code('E_NODE_DIR_EXIST')), $this->getErrorTypeContext());
            }
            if (isset($data['modelsTemplate'])) {
               $modesTemplate = $data['modelsTemplate'];
               unset($data['modelsTemplate']);
            }
         }
         $node = new NodeModel();
         //处理文件引用
         if (method_exists($node, 'getFileRefs')) {
            if ($data['fileRefs']) {
               $refManager = new RefManager();
               $refManager->confirmFileRef($data['fileRefs']);
            }
         }else {
            unset($data['fileRefs']);
         }
         $node->assignBySetter($data);
         $node->save();
         $nid = $node->getId();
         if ($nodeType == Constant::N_TYPE_GENERAL) {
            foreach ($modesTemplate as $mid => $tpl) {
               $c2model = new C2Map();
               $c2model->setDefaultTemplateFile($tpl);
               $c2model->setCategoryId($nid);
               $c2model->setModelId($mid);
               $c2model->create();
            }
         }
         $db->commit();
         return $nid;
      } catch (\Exception $ex) {
         $db->rollback();
         throw $ex;
      }

      //清除缓存
      $this->clearCache();
   }

   /**
    * 更新指定ID的节点
    *
    * @param int $nid 节点ID
    * @param array $data
    */
   public function updateNode($nid, array $data)
   {
      //删除危险字段
      unset($data['id']);
      unset($data['nodeType']);
      //判断节点是否存在
      $node = NodeModel::findFirst((int) $nid);
      if (null === $node) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_NOT_EXIST', $nid), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }
      //如果存在标识符，并且跟以前的不一样那么检查是否唯一
      if (array_key_exists('nodeIdentifier', $data) && $node->getNodeIdentifier() !== $data['nodeIdentifier']) {
         if (NodeModel::findFirstByNodeIdentifier($data['nodeIdentifier'])) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_NODE_IDENTIFIER_EXIST', $data['nodeIdentifier']), $errorType->code('E_NODE_IDENTIFIER_EXIST')), $this->getErrorTypeContext());
         }
      }
      $nodeType = $node->getNodeType();
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (Constant::N_TYPE_GENERAL == $nodeType) {
            unset($data['dirname']);
            /**
             * 在添加节点的时候需要通过按照节点类型进行字段检查，不同的类型只检查自己的
             */
            if (array_key_exists('modelsTemplate', $data)) {
               $modelsTemplate = $data['modelsTemplate'];
               //删除旧的模板映射数据，然后插入新的
               $this->updateNodeC2MapData($nid, $modelsTemplate);
               unset($data['modelsTemplate']);
            }
         }
         //处理文件引用
         if (method_exists($node, 'getFileRefs')) {
            $refManager = new RefManager();
            $oldFileRefs = $node->getFileRefs();
            if($oldFileRefs != $data['fileRefs']){
               if($oldFileRefs){
                  $refManager->removeFileRef($oldFileRefs);
               }
               if ($data['fileRefs']) {
                  $refManager->confirmFileRef($data['fileRefs']);
               }
            }
         }else {
            unset($data['fileRefs']);
         }

         $node->assignBySetter($data);
         $node->update();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 更新指定ID的节点
    *
    * @param int $nid 节点ID
    * @param $nodeType 需要删除的栏目类型
    * @param boolean $isForce 是否强制删除
    */
   public function deleteNode($id, $nodeType, $isForce = false)
   {
      $db = Kernel\get_db_adapter();
      $orginTime = ini_get('max_execution_time');
      try {
         $db->begin();
         $tree = $this->getTreeObject();
         if ($nodeType == Constant::N_TYPE_GENERAL) {
            //删普通节点
            //基本逻辑是判断是否有子节点，有子节点就拒绝删除，该节点的文章放入系统回收站
            //多节点删除 暂时还有问题
            $children = $tree->getChildren($id, -1);
            if (/*                 * !$isForce && * */!empty($children)) {
               $errorType = $this->getErrorType();
               Kernel\throw_exception(new Exception(
                  $errorType->msg('E_NODE_NOT_EMPTY', $id), $errorType->code('E_NODE_NOT_EMPTY')), $this->getErrorTypeContext());
            }
            /**
             * 删除节点本身加上内容模型模板表
             */
            $modelsManager = Kernel\get_models_manager();
            $query = sprintf('DELETE FROM %s WHERE categoryId = ?0', self::C2MAP_M_CLS);
            $modelsManager->executeQuery($query, array(
               0 => $id
            ));
            set_time_limit(0);
            $items = $this->getAppCaller()->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_INFO_LIST, 'getInfoListByNode', array($id));
            foreach ($items as $item){
                $this->getAppCaller()->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'delete', array($item->getId()));
            }
            set_time_limit($orginTime);
         }
         //处理文件引用
         $node = $tree->getChild($id);
         if (method_exists($node, 'getFileRefs')) {
            $fileRefs = $node->getFileRefs();
            if (!empty($fileRefs)) {
               $fileRefs = explode('|', $fileRefs);
               $manager = new RefManager();
               foreach ($fileRefs as $ref) {
                  $manager->removeFileRef($ref);
               }
            }
         }

         $query = sprintf("DELETE FROM %s WHERE  id = ?0", self::NODE_M_CLS);
         $modelsManager = Kernel\get_models_manager();
         $modelsManager->executeQuery($query, array(
            0 => $id
         ));
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }

      //删除缓存
      $this->clearCache();
   }

   /**
    * 更新节点列表
    *
    * @param array $nodeList
    */
   public function updateNodePriority(array $nodeList)
   {
      $ids = array_keys($nodeList);
      $nodes = NodeModel::find(array(
         NodeModel::generateRangeCond('id', $ids),
      ));
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (count($nodes) > 0) {
            foreach ($nodes as $node) {
               $node->setPriority($nodeList[$node->getId()]);
               $node->update();
            }
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         throw $ex;
      }
   }

   /**
    * 更新节点与内容模型模板映射数据
    *
    * @param int $nid 节点ID
    * @param array $c2ModelMap
    */
   protected function updateNodeC2MapData($nid, array $c2ModelMap)
   {
      $mm = Kernel\get_models_manager();
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $mm->executeQuery(sprintf('DELETE FROM %s WHERE categoryId = ?0', self::C2MAP_M_CLS), array(
            0 => $nid
         ));
         foreach ($c2ModelMap as $mid => $tpl) {
            $m = new C2Map();
            $m->assignBySetter(array(
               'categoryId'          => $nid,
               'modelId'             => $mid,
               'defaultTemplateFile' => $tpl
            ));
            $m->create();
         }
         $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
      //删除缓存
      $this->clearCache();
   }

   /**
    * 获取指定ID的节点数据
    *
    * @param int $id
    * @return \App\Site\Category\Model\Node
    */
   public function getNode($id)
   {
      $node = NodeModel::findFirst((int)$id);
      if (!$node) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_NOT_EXIST', $id), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $node;
   }

   /**
    * 根据节点的identifier获取节点信息
    *
    * @param string $identifier
    * @return \App\Site\Category\Model\Node
    */
   public function getNodeByIdentifier($identifier)
   {
      $node = NodeModel::findFirst(array(
         'nodeIdentifier = ?0',
         'bind' => array(
            0 => $identifier,
         )
      ));
      if (!$node) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_NOT_EXIST', $identifier), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $node;
   }

   /**
    * 根据栏目标识符查找栏目ID
    *
    * @param array $nodeIdentifiers
    * @return mixed
    * @throws \Exception
    */
   public function getNodesByIdentifiers(array $nodeIdentifiers)
   {
      $ret = NodeModel::find(NodeModel::generateRangeCond('nodeIdentifier', $nodeIdentifiers));

      if (!$ret) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_NODE_NOT_EXIST', $nodeIdentifiers), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $ret;
   }

   /**
    * 判断节点是否存在
    *
    * @param int $id
    * @return boolean
    */
   public function hasNode($id)
   {
      return $this->getNode($id) ? true : false;
   }

   /**
    * 获取指定的栏目下的模板映射
    *
    * <code>
    *  array(
    *      'modelId' => 'tpl'
    * );
    * </code>
    *
    * @param int $nid
    */
   public function getNodeTplMap($nid)
   {
      $key = $this->generateNodeModelCacheKey();
      $cacher = $this->getAppObject()->getCacheObject();
      $map = array();
      if(!$cacher->exists($key)) {
         $items = C2Map::find();
         foreach ($items as $item) {
            $nodeId = $item->getCategoryId();
            if (!isset($map[$nodeId])) {
               $map[$nodeId] = array();
            }
            $map[$nodeId][$item->getModelId()] = $item->getDefaultTemplateFile();
         }
         $cacher->save($key, $map);
      }else {
         $map = $cacher->get($key);
      }

      return $map[$nid];
   }

   /**
    * 获取当前栏目的内容模型模板文件
    *
    * @param int $nid
    * @param int $mid
    * @return string
    */
   public function getNodeModelTpl($nid, $mid)
   {
      $key = $this->generateNodeModelCacheKey();
      $cacher = $this->getAppObject()->getCacheObject();
      if (!$cacher->exists($key)) {
         $map = array();
         $items = C2Map::find();
         foreach ($items as $item) {
            $nodeId = $item->getCategoryId();
            if (!isset($map[$nodeId])) {
               $map[$nodeId] = array();
            }
            $map[$nodeId][$item->getModelId()] = $item->getDefaultTemplateFile();
         }
         $cacher->save($key, $map);
      } else {
         $map = $cacher->get($key);
      }
      return ArrayUtils::get($map, $nid . '.' . $mid);
   }

   /**
    * 获取节点树对象，有的时候需要直接引用
    *
    * @return \Cntysoft\Stdlib\Tree
    */
   public function getTreeObject()
   {
      if (null == $this->treeRepo) {
         $root = new NodeModel();
         $root->assign(array(
            'id'             => 0,
            'pid'            => -1,
            'text'           => 'Category Root',
            'nodeIdentifier' => 'Root'
         ));
         $tree = new \Cntysoft\Stdlib\Tree($root);
         foreach ($this->getAllNodes() as $node) {
            //在这里是否获取地址
            $tree->setNode($node->getId(), $node->getPid(), $node);
         }
         $this->treeRepo = $tree;
      }
      return $this->treeRepo;
   }

   /**
    * 检查栏目节点的标识符
    *
    * @param string $nodeIdentifier
    * @return boolean
    */
   public function checkNodeIdentifier($nodeIdentifier)
   {
      return (int) NodeModel::count(array(
         'nodeIdentifier = ?0 ',
         'bind' => array(
            0 => $nodeIdentifier
         )
      )) > 0 ? true : false;
   }

   /**
    * 检查指定栏目文件夹是否存在
    *
    * @param int $pid
    * @param string $dirname
    * @return boolean
    */
   public function nodeDirExist($pid, $dirname)
   {
      return NodeModel::findFirst(array(
         'pid = ?0 AND dirname = ?1',
         'bind' => array(
            0 => $pid,
            1 => $dirname
         )
      )) != false ? true : false;
   }

   /**
    * @param array $nodes
    */
   public function createNodeStructure(array $nodes)
   {
      Kernel\truncate_table('app_site_category_tree');
      Kernel\truncate_table('join_category_content_model_template');
      $num = NodeModel::count();
      if ($num > 0) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_SITE_TREE_ALREADY_EXIST'), $errorType->code('E_SITE_TREE_ALREADY_EXIST')), $this->getErrorTypeContext());
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();

         $idSeed = Constant::ID_SEED_START;
         $this->doInsertNodeRecursive($idSeed, 0, $nodes, 0);
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   protected function doInsertNodeRecursive(&$id, $pid, array $nodes, $priority = 0)
   {

      foreach ($nodes as $value) {
         $cur = array(
            'id'             => $id,
            'text'           => $value['text'],
            'nodeIdentifier' => $value['identifier'],
            'pid'            => $pid,
            'priority' => $priority++,
            'coverTemplateFile' => isset($value['coverTemplateFile']) ? $value['coverTemplateFile']: null,
            'showOnMenu' => $value['showOnMenu'],
            'showOnListParent' => $value['showOnListParent']
         );
         if (Constant::N_TYPE_GENERAL == $value['nodeType']) {
            if(isset($value['listTemplateFile'])){
               $cur['listTemplateFile'] = $value['listTemplateFile'];
            }
            if (isset($value['modelsTemplate'])) {
               $cur['modelsTemplate'] = $value['modelsTemplate'];
            } else {
               $cur['modelsTemplate'] = array();
            }
         }
         $newAdded = $this->addNode($value['nodeType'], $cur);
         if (isset($value['children']) && is_array($value['children'])) {
            $this->doInsertNodeRecursive(++$id, $newAdded, $value['children'], 0);
         }
         ++$id;
      }
   }

   /**
    * 给相应的节点类型赋默认值
    *
    * @param int $type 节点的类型
    * @param array $data
    */
   protected function assignDefaultValues($type, array &$data)
   {
      if ($type == Constant::N_TYPE_GENERAL) {
         $data += array(
            'itemOpenType' => 1
         );
         if (!isset($data['dirname'])) {
            $data['dirname'] = $data['nodeIdentifier'];
         }
      }
      $data['createDate'] = time();
      $data += array(
         'openType'         => Constant::OPEN_TYPE_NEW,
         'priority'         => 0,
         'showOnListParent' => 1,
         'showOnMenu' => 0
      );
   }

   /**
    * 根据节点类型获取节点模型类
    *
    * @param int $type 模型的类型ID
    * @return array
    */
   protected function getTypeRequiresByType($type)
   {
      if (empty($this->nTypeRequiresMap)) {
         $this->nTypeRequiresMap = array(
            Constant::N_TYPE_GENERAL => array('pid', 'nodeIdentifier', 'dirname', 'text'),
            Constant::N_TYPE_LINK    => array('pid', 'nodeIdentifier', 'text'),
            Constant::N_TYPE_SINGLE  => array('pid', 'nodeIdentifier', 'text')
         );
      }
      return $this->nTypeRequiresMap[$type];
   }

   /**
    * 节点排序
    *
    * @param array $nodeList
    * @throws \Exception
    */
   public function resortNodeList( array $nodeList)
   {
      $ids = array_keys($nodeList);
      $nodes = NodeModel::find(array(
         NodeModel::generateRangeCond('id', $ids)
      ));
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (count($nodes) > 0) {
            foreach ($nodes as $node) {
               $node->setPriority($nodeList[$node->getId()]);
               $node->update();
            }
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         throw $ex;
      }
   }

   /**
    * 生成系统栏目树的json数据
    *
    * @return string
    */
   public function toJson()
   {
      return \Zend\Json\Encoder::encode($this->toArray());
   }

   /**
    * 生成系统栏目树的数组形式
    *
    * @return array
    */
   public function toArray()
   {
      $tree = $this->getTreeObject();
      return $tree->toArray('children', function($tree, $nid, $pid) {
         $node = $tree->getValue($nid);
         return array(
            'id'             => $nid,
            'nodeIdentifier' => $node->getNodeIdentifier(),
            'text'           => $node->getText(),
            'url'            => $this->getNodeUrl($nid),
            'openType'       => $node->getOpenType(),
            'nodeType'       => $node->getNodeType()
         );
      });
   }

   /**
    * 生成节点模板缓存键值
    *
    * @return string
    */
   protected function generateNodeModelCacheKey()
   {
      return md5($this->getAppObject()->getAppKey() . '.' . __FUNCTION__);
   }

   /**
    * 清除相关缓存
    */
   public function clearCache()
   {
      $cacher = $this->getAppObject()->getCacheObject();
      $key = $this->generateNodeModelCacheKey();
      if($cacher->exists($key)) {
         $cacher->delete($key);
      }
   }
    /**
     * 查询所有字节点信息
     *  清除相关缓存
     * @param integer $pid 
    */
   public function getSubNodes($pid)
   {
       return NodeModel::find(array(
         'pid = ?0',
         'bind' => array(
            0 => $pid
         )
      ));
   }
}