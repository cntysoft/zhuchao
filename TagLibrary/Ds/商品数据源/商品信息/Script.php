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
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
class Goods extends AbstractDsScript
{
   /**
    * @inheritdoc
    */
   public function load()
   {
      $number = $this->getRouteInfo()['number'];
		$goodsInfo = $this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number));
		return array(
			'name' => $goodsInfo->getBrand().$goodsInfo->getTitle().$goodsInfo->getDescription()
		);
   }


}