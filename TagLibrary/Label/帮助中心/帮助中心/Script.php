<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\HelpCenter;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
class HelpCenter extends AbstractLabelScript
{
	/**
	 * 检查节点是否存在
	 * @param string $identifier
	 * @return boolean
	 */
	public function checkNodeIdentifier($identifier)
	{
		return $this->appCaller->call(
							 CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'checkNodeIdentifier', array($identifier));
	}

	/**
	 * 获取节点信息
	 * @param string $identifier
	 * @return 
	 */
	public function getNodeInfoByIdentifier($identifier)
	{
		$this->checkNodeIdentifier($identifier);
		$nodeInfo = $this->appCaller->call(
				  CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($identifier));
		return $nodeInfo;
	}

	/**
	 * 获取节点的字节点信息
	 * @param string $identilier
	 * @return 
	 */
	public function getSubNodesByIdentifier($identifier)
	{
		$nodeInfo = $this->getNodeInfoByIdentifier($identifier);
		$nodeId = $nodeInfo->getId();
		$childNodes = $this->appCaller->call(
				  CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getSubNodes', array($nodeId));
		return $childNodes;
	}

	/**
	 * 获取文章列表（带分页）
	 * 
	 * @param int $nodeId
	 * @return type
	 */
	public function getInfoListByNodeAndStatus($nodeId)
	{
		$limit = $this->invokeParams['outputNum'];
		$page = $this->getRouteInfo()['pageid'];
		$offset = ((int) $page - 1) * $limit;
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, true, 'id DESC', $offset, $limit));
		return $generalInfo;
	}

	/**
	 * 获取文章列表（不带分页）
	 * @param int $nodeId
	 * @return type
	 */
	public function getInfoListByNodeAndStatusNotPage($nodeId)
	{
		$limit = $this->invokeParams['outputNum'];
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, false, 'hits DESC', 0, $limit));
		return $generalInfo;
	}

	/**
	 * 获取文章内容
	 * @param int $id
	 * @return type
	 */
	public function getGInfo($id)
	{
		return $this->appCaller->call(
							 CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'getGInfo', array($id));
	}

	/**
	 * 获取上一篇下一篇文章
	 * @param int $nid
	 * @param int $id
	 * @return type
	 */
	public function getPrevAndNextItem($nid, $id)
	{
		return $this->appCaller->call(
							 CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getPrevAndNextItem', array($nid, $id));
	}

}