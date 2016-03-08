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
use App\Site\CmMgr\Constant as CMMGR_CONST;
use App\Site\Category\Constant as CATE_CONST;
use App\Sys\User\Constant as SYS_USER_CONST;
use Cntysoft\Kernel;
use App\Site\Content\Exception;

class Manager extends AbstractHandler
{
   /**
    * 获取模型编辑器名称映射, 代理内容模型管理的接口
    */
   public function getModelEditorMap()
   {
      $data = $this->getAppCaller()->call(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR,
         'getModelList'
      );
      $ret = array();
      foreach ($data as $item) {
         $ret[$item->getId()] = $item->getEditor();
      }
      return $ret;
   }

   public function getModelFields(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $fields = $this->getAppCaller()->call(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR,
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
    *  调用内容模型编辑器的API接口，这个函数应对子模型很复杂的情况设计
    *
    * <code>
    * array(
    *      'mid' => 'mid',
    *      'name' => 'name',
    *      'params' => 'params'
    * );
    * </code>
    * @param array $params
    */
   public function callModelSaverApi(array $params)
   {
      $this->checkRequireFields($params, array('mid', 'name'));
      $mid = $params['mid'];
      $mname = $params['name'];
      unset($params['name']);
      $args = array();
      if (isset($params['params'])) {
         $args = $params['params'];
      }
      //有些地方可能会用到内容模型ID
      $args = array_merge($args, array('mid' => $mid));
      return $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER,
         'callModelSaverApi',
         array(
            $mid,
            $mname,
            $args
         )
      );
   }

   /**
    * 保存信息
    *
    * @param array $params
    */
   public function saveInfo(array $params = array())
   {
      $this->checkRequireFields($params, array('modelId', 'nodeId'));
      $modelId = (int)$params['modelId'];
      unset($params['modelId']);
      $nodeId = $params['nodeId'];
      //在这里是否要判断status的值是否正确
      if(isset($params['id'])){
         //修改信息
         $id = (int)$params['id'];
         unset($params['id']);
         $ginfo = $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_MANAGER,
            'getGInfo',
            array(
               $id
            )
         );
         if (null == $ginfo) {
            //要更新的信息不存在
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_INFO_IS_NOT_EXIST', $id), $errorType->code('E_INFO_IS_NOT_EXIST')
            ), $this->getErrorTypeContext());
         }
         $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_MANAGER,
            'update',
            array(
               $id,
               $params
            )
         );
      }else{
         //全新添加
         $curUser = $this->getAppCaller()->call(
            SYS_USER_CONST::MODULE_NAME,
            SYS_USER_CONST::APP_NAME,
            SYS_USER_CONST::APP_API_ACL,
            'getCurUser'
         );
         //文章模型信息没有填写简介的时候默认截取一段内容作为简介
         $params['editor'] = $curUser->getName();
         $intro = isset($params['intro']) ? trim($params['intro']) : '';
         if(empty($intro) && array_key_exists('content', $params)){
            $content = strip_tags($params['content']);
            $intro = iconv_substr($content, 0, Constant::INTRO_DEFAULT_LEN, 'UTF-8');
         }
         $params['intro'] = $intro;
         $added = $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_MANAGER,
            'add',
            array(
               $modelId,
               $params
            )
         );
         return array(
            'id' => $added->getId()
         );

      }
   }

   /**
    * 获取信息的详细信息
    *
    * @param array $params
    * @return array
    */
   public function readInfo(array $params = array())
   {
      $timeArray = array('inputTime', 'updateTime', 'passTime');
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $info = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER,
         'read',
         array(
            $id
         )
      );
      $g = $info[0];
      $sub = $info[1];
      $subValues = $sub->toArray(true);
      if(isset($subValues['fileRefs'])){
         $subValues['fileRefs'] = explode(',', $subValues['fileRefs']);
      }
      unset($subValues['id']);
      $id = $g->getNodeId();
      if(CMMGR_CONST::CONTENT_MODEL_EFFECT_IMAGE === $g->getCmodelId()){
         $queryValues = $info[2]->toArray(true);
         $queryValues['qid'] = $queryValues['id'];
         unset($queryValues['id']);
         if(Constant::QUERY_ATTR_SINGLE == $queryValues['imageType']){
            $subValues['singlePic'][0] = $subValues['images'][0]['filename'];
            $subValues['singlePic'][1] = $subValues['images'][0]['fileRefIds'];
            unset($subValues['images']);
         }
         $ret = array_merge($g->toArray(true), $subValues, $queryValues);
      }else{
         $ret = array_merge($g->toArray(true), $subValues);
      }
      $ret['nodePath'] = $this->getNodePath($id);
      $node = $this->getAppCaller()->call(
         CATE_CONST::MODULE_NAME,
         CATE_CONST::APP_NAME,
         CATE_CONST::APP_API_STRUCTURE,
         'getNode',
         array(
            $id
         )
      );
      if (null == $node) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_TARGET_CATEGORY_NOT_EXIST', $id), $errorType->code('E_TARGET_CATEGORY_NOT_EXIST')), $this->getErrorTypeContext());
      }
      $ret['nodeText'] = $node->getText();
      //格式化DateTime对象
      foreach ($ret as $key => $value) {
         if (in_array($key, $timeArray) && !is_null($value)) {
            $ret[$key] = Kernel\format_timestamp($value);
         }
      }
      return $ret;
   }

   /**
    * 获取指定节点的路径
    *
    * @param int $id
    */
   protected function getNodePath($id)
   {
      $nodePath = $this->getAppCaller()->call(
         CATE_CONST::MODULE_NAME,
         CATE_CONST::APP_NAME,
         CATE_CONST::APP_API_STRUCTURE,
         'getNodePath',
         array(
            $id
         )
      );
      $ret = array();
      if (is_array($nodePath)) {
         foreach ($nodePath as $item) {
            array_unshift($ret, $item->getId());
         }
      }
      array_unshift($ret, 0);
      return '/' . implode('/', $ret);
   }

   /**
    * 将指定信息移除到回收站, 这个是直接在界面上面操作的调用API
    * 参数格式如下
    * <code>
    *      array(
    *            'nodeId' => array(
    *                  'id','id'
    *             ),
    *             'nodeId2' => array(
    *                  'id','id'
    *              )
    *          )
    *      )
    * </code>
    * @param array $params
    */
   public function moveToTrashcan(array $params = array())
   {
      //nodeid主要用来判断权限的
      $ids = array();
      foreach ($params as $nodeId => $infos) {
         $ids = array_merge($ids, $infos);
      }
      return $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER,
         'moveToTrashcan',
         array(
            $ids
         )
      );
   }

   /**
    * 审核指定的信息的看状态
    *
    * 参数的格式
    * <code>
    *      array(
    *          'items' => array(
    *              array(
    *                  'nodeId' => 'nodeId',
    *                  'id' => 'id',
    *                  'status' => 'status'
    *              )
    *          )
    *      )
    * </code>
    */
   public function verifyInfo(array $params)
   {
      $this->checkRequireFields($params, array(
         'items'
      ));
      $items = $params['items'];
      if (empty($items)) {
         return array();
      }
      $supportTypes = array(
         Constant::INFO_S_DRAFT,
         Constant::INFO_S_REJECTION,
         Constant::INFO_S_VERIFY
      );
      foreach ($items as $item) {
         $this->checkRequireFields($item, array(
            'nodeId', 'id', 'status'
         ));
         $status = $item['status'];
         //检查状态代码是否支持
         if (!in_array($status, $supportTypes)) {
            $errorType = $this->getErrorType();
            throw new Exception(
               $errorType->msg('INFO_STATUS_NOT_SUPPORT', $status), $errorType->code('INFO_STATUS_NOT_SUPPORT'));
         }
         $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_MANAGER,
            'updateGeneralInfo',
            array(
               $item['id'],
               array(
                  'status' => $status
               )
            )
         );
      }
   }

   /**
    * 将指定信息移除出回收站
    * /**
    * 将指定信息移除到回收站, 这个是直接在界面上面操作的调用API
    * 参数格式如下
    * <code>
    *      array(
    *            'nodeId' => array(
    *                  'id','id'
    *             ),
    *             'nodeId2' => array(
    *                  'id','id'
    *              )
    *          )
    *      )
    * </code>
    * @param array $params
    */
   public function moveOutTrashcan(array $params = array())
   {
      //暂时没用到， 为以后限定做准备
      $ids = array();
      foreach ($params as $nodeId => $infos) {
         $ids = array_merge($ids, $infos);
      }
      return $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER,
         'restoreFromTrashcan',
         array(
            $ids
         )
      );
   }

   /**
    * 删除指定ID信息，可以是批量删除
    * <code>
    *      array(
    *            'nodeId' => array(
    *                  'id','id'
    *             ),
    *             'nodeId2' => array(
    *                  'id','id'
    *              )
    *          )
    *      )
    * </code>
    *
    * @param array $params
    */
   public function deleteInfo(array $params = array())
   {
      $ids = array();
      foreach ($params as $nodeId => $infos) { //这个地方更没有用到节点ID，没有必要放在循环里面
         $ids = array_merge($ids, $infos);
      }
      $mgr = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER
      );
      foreach($ids as $id){
         $mgr->delete($id);
      }
   }

   /**
    * 清空回收站
    */
   public function clearTrashcan()
   {
      $mgr = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_MANAGER,
         'clearTrashcan'
      );
   }

}