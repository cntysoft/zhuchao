<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Qs\View;
use App\Site\Category\Constant as CATE_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
class CategoryController extends AbstractController
{
   /**
    * 首页模板
    * 
    * @return boolean
    */
   public function indexAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'index',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 文章分类模板
    * 
    * @return boolean
    */
   public function categoryAction()
   {
      $nodeIdentifier = $this->dispatcher->getParam('nodeIdentifier');
      $appCaller = $this->getAppCaller();
      $node = $appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier)
      );
      //暂时不处理节点不存在的情况
      if (!$node) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      //设置nid
      $this->view->setRouteInfoItem('nid', $node->getId());
      $this->view->setRouteInfoItem('nodeIdentifier', $nodeIdentifier);
      $tpl = $node->getCoverTemplateFile();
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_FINDER,
                 View::KEY_RESOLVE_DATA => $tpl
      ));
   }

   /**
    * 
    * 
    * @return 
    */
   public function categorylistAction()
   {
      $nodeIdentifier = $this->dispatcher->getParam('nodeIdentifier');
      $appCaller = $this->getAppCaller();
      $node = $appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier)
      );
      //暂时不处理节点不存在的情况
      if (!$node) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      //设置nid
      $this->view->setRouteInfoItem('nid', $node->getId());
      $this->view->setRouteInfoItem('nodeIdentifier', $nodeIdentifier);
      $tpl = $node->getListTemplateFile();
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_FINDER,
                 View::KEY_RESOLVE_DATA => $tpl
      ));
   }

   /**
    * 文章模板
    * 
    * @return boolean
    */
   public function articleAction()
   {
      $itemId = $this->dispatcher->getParam('articleId');
      if (null === $itemId) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      $itemId = (int) $itemId;
      try {
         $appCaller = $this->getAppCaller();
         $info = $appCaller->call(
                 CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'getGInfo', array($itemId)
         );
         if (!$info) {
            $this->dispatcher->forward(array(
               'module'     => 'Pages',
               'controller' => 'Exception',
               'action'     => 'pageNotExist'
            ));
            return false;
         }
         $status = $info->getStatus();
         if ($status != CONTENT_CONST::INFO_S_VERIFY) {
            $this->dispatcher->forward(array(
               'module'     => 'Pages',
               'controller' => 'Exception',
               'action'     => 'pageNotExist'
            ));
            return false;
         }

         $this->view->setRouteInfoItem('nid', $info->getNodeId());
         $node = $appCaller->call(
                 CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNode', array($info->getNodeId()));
         if (!$node) {
            $this->dispatcher->forward(array(
               'module'     => 'Pages',
               'controller' => 'Exception',
               'action'     => 'pageNotExist'
            ));
            return false;
         }

         $tpl = $appCaller->call(
                 CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNodeModelTpl', array((int) $info->getNodeId(), $info->getCmodelId())
         );
         $this->view->setRouteInfoItem('nodeIdentifier', $node->getNodeIdentifier());
         $this->setupRenderOpt(array(
            View::KEY_RESOLVE_DATA => $tpl,
            View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_FINDER
         ));
      } catch (\Exception $ex) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
   }

   /**
    * 订单打印页模板
    */
   public function printAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'print',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 商品搜索 
    */
   public function searchAction()
   {
      $request = $this->getDI()->get('request');
      $args = $request->getQuery();
      unset($args['_url']);

      if(!array_key_exists('keyword', $args)){
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'search',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}