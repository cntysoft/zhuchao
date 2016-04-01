<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Qs\View;
use Cntysoft\Kernel;
use App\Yunzhan\Category\Constant as CATE_CONST;
use App\Yunzhan\Content\Constant as CONTENT_CONST;
class IndexController extends AbstractController
{
   public function initialize()
   {
      $id = Kernel\get_site_id();
      if (!$id) {
         $this->dispatcher->forward(array(
            'controller' => 'Exception',
            'action'     => 'notfind'
         ));
      }
   }

   /**
    * 网站首页路由
    * 
    * @return 
    */
   public function indexAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/index',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 栏目首页路由
    */
   public function newslistAction()
   {
      $nodeIdentifier = $this->dispatcher->getParam('nodeIdentifier');
      $appCaller = $this->getAppCaller();
      $node = $appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier)
      );
      //暂时不处理节点不存在的情况
      if (!$node) {
         $this->dispatcher->forward(array(
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
    * 新闻内容页路由
    * 
    * @return 
    */
   public function newsAction()
   {
      $itemId = $this->dispatcher->getParam('newsid');
      if (null === $itemId) {
         $this->dispatcher->forward(array(
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
               'controller' => 'Exception',
               'action'     => 'pageNotExist'
            ));
            return false;
         }
         $status = $info->getStatus();
         if ($status != CONTENT_CONST::INFO_S_VERIFY) {
            $this->dispatcher->forward(array(
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
               'controller' => 'Exception',
               'action'     => 'pageNotExist'
            ));
            return false;
         }
         if ($node->getNodeIdentifier() == 'about') {
            $this->dispatcher->forward(array(
               'controller' => 'Index',
               'action'     => 'about'
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
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
   }

   /**
    * 关于我们
    * 
    * @return 
    */
   public function aboutAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/about',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 搜索
    * 
    * @return 
    */
   public function searchAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/search',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 产品列表
    * 
    * @return 
    */
   public function productlistAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/productlist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
	
	public function caselistAction()
	{
		return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/caselist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
	}
	
	public function casedetailAction()
	{
		return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/casedetail',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
	}

}