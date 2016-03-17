<?php

namespace TagLibrary\Label\HelpCenter;

use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;

class HelpCenter extends AbstractLabelScript {

	/**
	 * 检查节点是否存在
	 * @param string $identifier
	 * @return boolean
	 */
	public function checkNodeIdentifier($identifier) {
		return $this->appCaller->call(
							 CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'checkNodeIdentifier', array($identifier));
	}

	/**
	 * 获取节点信息
	 * @param string $identifier
	 * @return 
	 */
	public function getNodeInfoByIdentifier($identifier) {
		$this->checkNodeIdentifier($identifier);
		$nodeInfo = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($identifier));
		return $nodeInfo;
	}

	/**
	 * 获取节点的字节点信息
	 * @param string $identilier
	 * @return 
	 */
	public function getSubNodesByIdentifier($identifier) {
		$nodeInfo = $this->getNodeInfoByIdentifier($identifier);
		$nodeId = $nodeInfo->getId();
		$childNodes = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getSubNodes', array($nodeId));
		return $childNodes;
	}

	public function getInfoListByNodeAndStatus($nodeId) {
		$limit = $this->invokeParams['outputNum'];
		$page = $this->getRouteInfo()['pageid'];
		$offset = ((int) $page - 1) * $limit;
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, true, 'id DESC', $offset, $limit));
		return $generalInfo;
	}

	public function getInfoListByNodeAndStatusNotPage($nodeId) {
		$limit = $this->invokeParams['outputNum'];
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, false, 'hits DESC', 0, $limit));
		return $generalInfo;
	}

	public function getGInfo($id) {
		return $this->appCaller->call(
							 CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'getGInfo', array($id));
	}

	public function getPrevAndNextItem($nid, $id) {
		return $this->appCaller->call(
							 CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getPrevAndNextItem', array($nid, $id));
	}

}
