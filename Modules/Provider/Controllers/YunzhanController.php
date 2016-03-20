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
class YunzhanController extends AbstractController
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

   public function newslistAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/newslist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function addnewsAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/addnews',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function joblistAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/joblist',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function addjobAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/addjob',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function settingAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/setting',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      )); 
   }

}