<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\Article;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Site\Content\Constant as CONTENT_CONST;

class Article extends AbstractDsScript
{
	/**
	 * @inheritdoc
	 */
	public function load()
	{
		$router = $this->getRouteInfo();
		$articleId = $router['articleId'];
		$articleInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, 
				  CONTENT_CONST::APP_NAME, 
				  CONTENT_CONST::APP_API_MANAGER, 
				  'getGInfo', array($articleId));
		$articleDetail = $articleInfo->getDetail();
		return array(
			'title' => $articleInfo->getTitle(),
			'intro' => $articleInfo->getIntro(),
			'keyword' => $articleDetail->getKeywords()
		);
	}

}