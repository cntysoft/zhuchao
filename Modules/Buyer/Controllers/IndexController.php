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
use App\ZhuChao\MessageMgr\Constant as MESSAGE_CONST;

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
         View::KEY_RESOLVE_DATA => 'buyer/index',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function modifyAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/modify',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function passwordAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/password',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function collectionAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/collection',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function followAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/follow',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function quotationlistAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/quotationlist',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   public function quotationAction()
   {
      $quotationId = $this->dispatcher->getParam('quotationId');
      
      $quotation = $this->getAppCaller()->call(
         MESSAGE_CONST::MODULE_NAME,
         MESSAGE_CONST::APP_NAME,
         MESSAGE_CONST::APP_API_OFFER,
         'getInquiryAndOffer',
         array((int)$quotationId)
      );

      if(!$quotation){
         Kernel\goto_route('404.html');
      }
      
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/quotation',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function messageAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/message',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   public function addressAction()
   {
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'buyer/address',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}