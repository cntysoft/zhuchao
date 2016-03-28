<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\Category;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Site\Category\Constant as SITE_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
class Category extends AbstractDsScript
{
	/**
	 * @inheritdoc
	 */
	public function load()
	{
		$router = $this->getRouteInfo();
		$identifier = $router['nodeIdentifier'];
		$nodeInfo = $this->appCaller->call(
				  SITE_CONST::MODULE_NAME, SITE_CONST::APP_NAME, SITE_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($identifier));
		return array(
			'description'	 => $nodeInfo->getMetaDescription(),
			'keyword'		 => $nodeInfo->getMetaKeywords()
		);
	}

}