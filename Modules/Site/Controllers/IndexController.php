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
class IndexController extends AbstractController
{
   public function initialize()
   {
      $id = Kernel\get_site_id();
      if (!$id) {
         $this->dispatcher->forward(array(
            'controller' => 'Exception',
            'action' => 'notfind'
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
    * 新闻内容页路由
    * 
    * @return 
    */
   public function newsAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/newslist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}