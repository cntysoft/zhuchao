<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Qs\View;
class ExceptionController extends AbstractController
{
   public function pageNotExistAction()
   {
      $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => '404.phtml',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_FINDER
      ));
   }
}