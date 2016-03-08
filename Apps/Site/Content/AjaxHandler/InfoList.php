<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Content\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\Site\Content\Constant;
use Cntysoft\Kernel;
use App\Site\Category\Constant as CATE_CONST;
use App\Site\CmMgr\Constant as CMMGR_CONST;
class InfoList extends AbstractHandler
{
   /**
    * 判断当前信息是否存在
    *
    * @param array $params
    * @return boolean
    */
   public function infoIsExist(array $params = array())
   {
      $this->checkRequireFields($params, array('mid', 'nid', 'title'));
      $mid = (int)$params['mid'];
      $nid = (int)$params['nid'];
      $title = $params['title'];
      $exist = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_INFO_LIST,
         'titleExist',
         array(
            $mid,
            $nid,
            $title
         )
      );
      return array(
         'exist' => $exist
      );
   }

   /**
    * 获取指定节点下的指定状态的信息列表
    *
    * @param array $params
    * @return array
    */
   public function getInfoListByNodeAndStatus(array $params)
   {
      $this->checkRequireFields($params, array('id', 'status', 'type'));
      $ret = array();
      $nodeId = $params['id'];
      //在这里需要节点管理器APP相关功能
      $categoryTree = $this->getAppCaller()->call(
         CATE_CONST::MODULE_NAME,
         CATE_CONST::APP_NAME,
         CATE_CONST::APP_API_STRUCTURE,
         'getTreeObject'
      );
      /**
       * 这里WEBOS直接请求的数据 所以节点基本可以保证存在
       * @todo 是否强制检查节点是否存在？
       */
      $cNodes = $categoryTree->getChildren($nodeId, -1, false);
      array_unshift($cNodes, $nodeId);
      /**
       * 这个flag的作用是 当获取的信息列表包含子节点的时候，在节点的前面加上节点的名称
       */
      $flag = false;
      if (count($cNodes) > 1) {
         $flag = true;
      }
      $orderBy = $limit = $offset = null;
      $this->getPageParams($orderBy, $limit, $offset, $params);
      $list = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_INFO_LIST,
         'getInfoListByNodeAndStatus',
         array(
            $cNodes,
            $params['type'],
            $params['status'],
            true, 'id desc', $offset, $limit
         )
      );
      $total = $list[1];
      $list = $list[0];
      //局部化
      $cmm = $this->getAppCaller()->getAppObject(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR
      );
      foreach ($list as $item) {
         $ret[] = $this->getItemArrayFromModel($categoryTree, $cmm, $item, $flag);
      }
      return array(
         'total' => $total,
         'items' => $ret
      );
   }

   /**
    * 获取回收站列表
    *
    * @param array $params
    * @return array
    */
   public function getTrashcanListByNode(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $ret = array();
      $id = (int) $params['id'];
      $orderBy = $limit = $offset = null;
      $this->getPageParams($orderBy, $limit, $offset, $params);
      //在这里需要节点管理器APP相关功能
      $list = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_INFO_LIST,
         'getTrashcanListByNode',
         array(
            $id, true, 'id desc', $offset, $limit
         )
      );
      $total = $list[1];
      $list = $list[0];
      //局部化
      $cmmgr = $this->getAppCaller()->getAppObject(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR
      );
      $categoryTree = $this->getAppCaller()->call(
         CATE_CONST::MODULE_NAME,
         CATE_CONST::APP_NAME,
         CATE_CONST::APP_API_STRUCTURE,
         'getTreeObject'
      );
      foreach ($list as $item) {
         $nodeId = $item->getNodeId();
         $title = $item->getTitle();
         if (0 == $id) {
            $node = $categoryTree->getValue($nodeId);
            $title = '[' . $node->getText() . '] ' . $title;
         }
         $ret[] = array(
            'id'       => $item->getId(),
            'title'    => $title,
            'nid'      => $item->getNodeId(),
            'editor'   => $item->getEditor(),
            'hits'     => $item->getHits(),
            'priority' => $item->getPriority(),
            'modelId'  => $item->getCmodelId(),
            'modelKey' => $cmmgr->getCModelKeyById($item->getCmodelId())
         );
      }
      return array(
         'total' => $total,
         'items' => $ret
      );
   }

   /**
    * @param \Cntysoft\Stdlib\Tree $categoryTree
    * @param \App\Site\CmMgr\Mgr $cmmgr
    * @param \App\Site\CmMgr\Model\General $itemObj
    * @param boolean $isRenderTitle
    */
   protected function getItemArrayFromModel($categoryTree, $cmmgr, $itemObj, $isRenderTitle)
   {
      $item = array();
      //优化返回结构加快速度
      $item['title'] = $itemObj->getTitle('title');
      if ($isRenderTitle) {
         $nid = $itemObj->getNodeId();
         $node = $categoryTree->getValue($nid);
         $item['title'] = '[' . $node->getText() . '] ' . $item['title'];
      }
      $item['nid'] = $itemObj->getNodeId();
      $item['editor'] = $itemObj->getEditor();
      $item['hits'] = $itemObj->getHits();
      $item['priority'] = $itemObj->getPriority();
      $item['modelId'] = $itemObj->getCmodelId();
      $item['modelKey'] = $cmmgr->getCModelKeyById($item['modelId']);

      $item['status'] = $itemObj->getStatus();
      $item['id'] = $itemObj->getId();
      $item['type'] = $itemObj->getCmodelId();
      return $item;
   }
}