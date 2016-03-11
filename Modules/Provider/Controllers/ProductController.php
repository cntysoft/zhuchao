<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Kernel;
use Cntysoft\Framework\Qs\View;
class ProductController extends AbstractController
{
   /**
    * 验证是否已经登录, 没有登录直接跳到登录页面
    * 
    * @return boolean
    */
   public function initialize()
   {
      $acl = $this->di->get('ProviderAcl');
      if (!$acl->isLogin()) {
         $path = $this->request->getURI();
         Kernel\goto_route('login.html?returnUrl=' . urlencode($path));
         exit;
      }
   }

   public function indexAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'provider/productlist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function groupAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'provider/productgroup',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
}