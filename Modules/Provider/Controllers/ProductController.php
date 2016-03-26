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
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;

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
      
      $user = $acl->getCurUser();
      $company = $user->getCompany();
      if (!$company || !$company->getSubAttr()) {
         Kernel\dispatch_action('Provider', 'Index', 'open');
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
   
   public function addproductAction()
   {
      $stage = $this->dispatcher->getParam('stage');
      if(1 == $stage){
         return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'provider/categoryselect',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
         ));
      }else if(2 == $stage){
         $query = $this->request->getQuery();
         if(isset($query['category']) && $query['category']){
            $category = $this->getAppCaller()->call(
               CATEGORY_CONST::MODULE_NAME,
               CATEGORY_CONST::APP_NAME,
               CATEGORY_CONST::APP_API_MGR,
               'getNode',
               array((int)$query['category'])
            );
            if($category && CATEGORY_CONST::NODE_TYPE_DETAIL_CATEGORY == $category->getNodeType()){
               return $this->setupRenderOpt(array(
                 View::KEY_RESOLVE_DATA => 'provider/addproduct',
                 View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
               ));
            }else{
               Kernel\goto_route('product/addproduct/1.html');
            }
         }else{
            Kernel\goto_route('product/addproduct/1.html');
         }
      }
   }
   
   public function changeproductAction()
   {
      $number = $this->dispatcher->getParam('number');
      
      $product = $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductByNumber',
         array($number)
      );
      
      if(!$product){
         Kernel\goto_route('product/1.html');
      }
      
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'provider/changeproduct',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
       ));
   }
}