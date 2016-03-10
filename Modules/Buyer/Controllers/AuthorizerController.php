<?php
/**
 * Cntysoft Cloud Software Team
 */
/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Kernel;
use Cntysoft\Framework\Qs\View;
/**
 * 处理前端用户的验证
 */
class AuthorizerController extends AbstractController
{
   /**
    * 用户登录
    * 
    * @return  void
    */
   public function loginAction()
   {
      if (!$this->checkLogin()) {
         return $this->setupRenderOpt(array(
            View::KEY_RESOLVE_DATA => 'login',
            View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
         ));
      }
   }

   /**
    * 用户注册
    * 
    * @return void
    */
   public function registerAction()
   {
      if (!$this->checkLogin()) {
         return $this->setupRenderOpt(array(
            View::KEY_RESOLVE_DATA => 'register',
            View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
         ));
      }
   }

   /**
    * 找回密码
    * 
    * @return void
    */
   public function forgetAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'forget',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 检查用户是否登陆
    * 
    * @return boolean
    */
   protected function checkLogin()
   {
      $acl = $this->di->get('BuyerAcl');
      if (!$acl->isLogin()) {
         return false;
      } else {
         Kernel\dispatch_action('Buyer', 'Index', 'index');
      }
   }

}