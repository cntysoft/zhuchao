<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Qs\View;

class ServiceController extends AbstractController
{

   public function feedbackAction()
   {
      $this->view->setRouteInfoItem('nodeIdentifier', 'help');
      return $this->setupRenderOpt(array(
         View::KEY_RESOLVE_DATA => 'service/feedback',
         View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
      ));
   }
}