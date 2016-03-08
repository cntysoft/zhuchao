<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Category\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\Site\Category\Constant;
use Cntysoft\Kernel;
use App\Site\CmMgr\Constant as CMMGR_CONST;
class Structure extends AbstractHandler
{
   /**
    * 获取所有的内容模型
    *
    * @return array
    */
   public function getAllContentModels()
   {
      $models = $this->getAppCaller()->call(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR,
         'getModelList'
      );
      $data = array();
      foreach($models as $model){
         $data[] = $model->toArray(true);
      }
      return $data;
   }
   /**
    * 检查节点标示符是否存在
    *
    * @param array $params
    * @return array
    */
   public function checkNodeIdentifier($params)
   {
      $this->checkRequireFields($params, array('identifier'));
      $nodeIdenitifier = $params['identifier'];
      $isExist = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'checkNodeIdentifier',
         array(
            $nodeIdenitifier
         )
      );
      return array('exist' => $isExist);
   }

   /**
    * 添加一个新的节点
    *
    * @param array $params
    */
   public function addNode(array $params)
   {
      $this->checkRequireFields($params, array('pid', 'nodeType'));
      $id = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'addNode',
         array(
            $params['nodeType'],
            $params
         )
      );
      return array(
         'id' => $id
      );
   }

   /**
    * @param array $params
    */
   public function updateNode(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'updateNode',
         array(
            $params['id'],
            $params
         )
      );
   }

   /**
    * 重新给节点排序
    *
    * @param array $params
    */
   public function reorderNodeList(array $params)
   {
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'updateNodePriority',
         array(
            $params
         )
      );
   }

   public function getNodeInfo(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $structure = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE
      );
      $treeObj = $structure->getTreeObject();
      $data = $treeObj->getValue($id);
      if(!$data){
         return array();
      }
      $type = $data->getNodeType();
      $data = $data->toArray(true);
      if ($type == Constant::N_TYPE_GENERAL) {
         //获取这个节点支持的所有内容模型
         $data['modelsTemplate'] = $structure->getNodeTplMap($id);
      }else{
         unset($data['contentModels']);
      }
      $pid = $data['pid'];
      //获取父节点的名称
      if (0 == $pid) {
         $data['parentNodeText'] = '系统栏目树';
         $data['treePath'] = '/0/' . $id;
      } else {
         $ps = array_reverse($treeObj->getParents($id));
         $parent = $treeObj->getValue($pid);
         $data['parentNodeText'] = $parent->getText();
         $data['treePath'] = '/' . implode('/', $ps);
      }
      unset($data['createDate']);
      return $data;
   }

   /**
    * 删除一个节点, 如果下面有子节点， 一并删除， 一个普通栏目下面的信息全部删除， 所以说这个函数非常危险
    * 如果有任何一个子节点权限不够，则拒绝删除
    *
    * @param array $params
    */
   public function deleteNode(array $params = array())
   {
      $this->checkRequireFields($params, array('id', 'nodeType'));
      $id = (int) $params['id'];
      $nodeType = (int) $params['nodeType'];
      $structure = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE
      );
      $tree = $structure->getTreeObject();
      $nodes = $tree->getChildren($id, -1);
      $nodes = array_reverse($nodes);
      $nodes[] = $id;
      $db = Kernel\get_db_adapter();
      try{
         $db->begin();
         foreach ($nodes as $node) {
            $structure->deleteNode($node, $nodeType, true);
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }

   }

   public function getChildren(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $allowTypes = array_key_exists('allowTypes', $params) ? $params['allowTypes'] : array();
      $extraFields = array_key_exists('extraFields', $params) ? $params['extraFields'] : array();
      $tree = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'getTreeObject'
      );
      $nodes = $tree->getChildren($id, 1, true);
      $ret = $this->formatNodes($nodes, $allowTypes, $extraFields);
      usort($ret, function($a, $b) {
         if ($a['priority'] < $b['priority']) {
            return -1;
         } else if ($a['priority'] == $b['priority']) {
            return 0;
         } else {
            return 1;
         }
      });
      return $ret;
   }

   /**
    * 处理节点数据
    *
    * @param array $nodes
    * @return array
    */
   protected function formatNodes(array $nodes, &$allowTypes, $extraFields = array())
   {
      $ret = array();
      foreach ($nodes as $node) {
         $type = $node->getNodeType();
         if (in_array($type, $allowTypes)) {
            $item = array(
               'id'       => $node->getId(),
               'text'     => $node->getText(),
               'priority' => $node->getPriority(),
               'leaf'     => $this->isLeaf($node->getId(), $type),
               'nodeType' => ($node->getId() == 1 ? Constant::N_TYPE_INDEX : $type)
            );
            if(Constant::N_TYPE_LINK == $type){
               $item['linkUrl'] = $node->getLinkUrl();
            }
            foreach ($extraFields as $field) {
               $method = 'get' . ucfirst($field);
               if (!method_exists($node, $method)) {
                  Kernel\throw_exception(new Exception(
                     Kernel\StdErrorType::msg('E_METHOD_NOT_EXIST', $method),
                     Kernel\StdErrorType::code('E_METHOD_NOT_EXIST')
                  ));
               }
               $results = $node->$method();
               if (is_object($results)) {
                  if(0 !== count($results)){
                     foreach ($results as $result) {
                        $item[$field][] = $result->toArray();
                     }
                  }
               } else {
                  $item[$field] = $results;
               }
            }
            $ret[] = $item;
         }
      }
      return $ret;

   }

   /**
    * 判断是否为ExtJs树的叶子节点
    *
    * @return boolean
    */
   protected function isLeaf($id, $nodeType)
   {
      return $nodeType == Constant::N_TYPE_LINK ||
      $nodeType == Constant::N_TYPE_SINGLE ||
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_STRUCTURE,
         'getTreeObject'
      )->isLeaf($id);
   }
}