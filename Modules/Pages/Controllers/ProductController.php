<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use App\ZhuChao\Product\Constant as GOODS_CONST;
use App\ZhuChao\CategoryMgr\Constant as GOODS_CATE_CONST;
use Cntysoft\Framework\Qs\View;
/**
 * 系统的回接口
 */
class ProductController extends AbstractController
{
   /**
    * 商品详情页面路由
    * 
    * @return 
    */
   public function productAction()
   {
      $number = $this->dispatcher->getParam('number');
      if (null === $number) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }

      $product = $this->getAppCaller()->call(
              GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number)
      );

      if (!$product) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }

      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'product',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 商品详情页面路由
    * 
    * @return 
    */
   public function productappAction()
   {
      $number = $this->dispatcher->getParam('number');
      if (null === $number) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      
      $product = $this->getAppCaller()->call(
              GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number)
      );

      if (!$product) {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
      
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'productapp',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
   
   /**
    * 商品分类列表页面
    * 
    * @return 
    */
   public function productclassifylistAction()
   {
      $appCaller = $this->getAppCaller();
      $categoryId = $this->dispatcher->getParam('categoryId');
      $node = $appCaller->call(
              GOODS_CATE_CONST::MODULE_NAME, GOODS_CATE_CONST::APP_NAME, GOODS_CATE_CONST::APP_API_MGR, 'getNode', array($categoryId)
      );
      if ($node) {
         return $this->setupRenderOpt(array(
                    View::KEY_RESOLVE_DATA => 'productclassifylist',
                    View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
         ));
      } else {
         $this->dispatcher->forward(array(
            'module'     => 'Pages',
            'controller' => 'Exception',
            'action'     => 'pageNotExist'
         ));
         return false;
      }
   }

   /**
    * 商品分类页面
    */
   public function classifylistAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'classifylist',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 商品搜索页
    */
   public function searchpageAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'searchpage',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}