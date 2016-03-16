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
class IndexController extends AbstractController
{
   /**
    * 验证是否已经登录, 没有登录直接跳到登录页面
    * 
    * @return boolean
    */
   public function initialize()
   {
      $acl = $this->di->get('BuyerAcl');
      if (!$acl->isLogin()) {
         $path = $this->request->getURI();
         Kernel\goto_route('login.html?returnUrl=' . urlencode($path));
         exit;
      }
   }

   public function indexAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/index',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function avatarAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/avatar',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function modifyAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/modify',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function passwordAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/password',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function collectionAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/collection',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function messageAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/message',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function addressAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'user/address',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}