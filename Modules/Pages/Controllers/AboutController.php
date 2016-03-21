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
class AboutController extends AbstractController
{
   /**
    * 关于我们公司简介
    */
   public function companyAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/company',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 关于我么们企业文化
    */
   public function cultureAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/culture',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 关于我们我们的业务
    */
   public function businessAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/business',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 关于资质
    */
   public function qualificationAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/qualification',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 广告服务
    */
   public function adAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/ad',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 人才招聘
    */
   public function joinAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/join',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 法律声明
    */
   public function legalAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/legal',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

   /**
    * 联系我们
    */
   public function contactAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'about/contact',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }

}