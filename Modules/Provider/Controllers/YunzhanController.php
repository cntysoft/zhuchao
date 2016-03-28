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
use App\Yunzhan\Content\Constant as C_CONST;
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
      }

      $user = $acl->getCurUser();
      $company = $user->getCompany();
      if (!$company || !$company->getSubAttr()) {
         Kernel\dispatch_action('Provider', 'Index', 'open');
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

   public function modifynewsAction()
   {
      $id = $this->dispatcher->getParam('id');
      $info = $this->getAppCaller()->call(C_CONST::MODULE_NAME, C_CONST::APP_NAME, C_CONST::APP_API_MANAGER, 'getGInfo', array($id));
      if (!$info) {
         Kernel\goto_route('site/news/1.html');
      }else {
         $node = $info->getNode();
         $nid = $node->getId();
         //关于我们栏目下面的文章有特定的修改地方,不能在这里修改
         if(C_CONST::NODE_COMPANY_ID != $nid && C_CONST::NODE_INDUSTRY_ID != $nid) {
            Kernel\goto_route('site/news/1.html');
         }
      }
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/modifynews',
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

   public function modifyjobAction()
   {
      $id = $this->dispatcher->getParam('id');
      $info = $this->getAppCaller()->call(C_CONST::MODULE_NAME, C_CONST::APP_NAME, C_CONST::APP_API_MANAGER, 'getGInfo', array($id));
      if (!$info) {
         Kernel\goto_route('/site/job/1.html');
      }else {
         $node = $info->getNode();
         //关于我们栏目下面的文章有特定的修改地方,不能在这里修改
         if(C_CONST::NODE_JOIN_ID != $node->getId()) {
            Kernel\goto_route('site/job/1.html');
         }
      }
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/modifyjob',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function introAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/intro',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function cultureAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/culture',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function zizhiAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/zizhi',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function contactAction()
   {
      return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'yunzhan/contact',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}