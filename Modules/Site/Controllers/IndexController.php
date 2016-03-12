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

   public function indexAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/index',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function newslistAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'site/newslist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}