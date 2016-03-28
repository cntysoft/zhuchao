<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\ZhuChao\CategoryMgr\Constant as CATE_CONST;
class GoodsCate extends AbstractDsScript
{
   /**
    * @inheritdoc
    */
   public function load()
   {
      $number = $this->getRouteInfo()['categoryId'];
      $nodeInfo = $this->appCaller->call(CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNode', array($number));
      return array(
         'name' => $nodeInfo->getName()
      );
   }

}