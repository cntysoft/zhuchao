<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\HelpCenter;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
use Cntysoft\Kernel\ConfigProxy;
class HelpCenter extends AbstractLabelScript
{
	/**
	 * 路由信息
	 *
	 * @var null
	 */
	protected $routes = null;

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
	 * 获取指定信息的详细内容
	 *
	 * @param integer $itemId
	 * @return array
	 */
	public function getDetail($itemId)
	{
		return $this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'read', array($itemId));
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

	public function getRouteNodeIdentifier()
	{
		return $this->getRoute()['nodeIdentifier'];
	}

	/**
	 * 获取路由信息
	 */
	public function getRoute()
	{
		if (null == $this->routes) {
			$this->routes = $this->getRouteInfo();
		}
		return $this->routes;
	}

	public function openCommentPage()
	{
		$mate = $this->getChangyanConfig();
		$router = $this->getRoute();
		return '<div id="SOHUCS" sid="' . $router['articleId'] . '"></div>
<script charset="utf-8" type="text/javascript" src="http://changyan.sohu.com/upload/changyan.js" ></script>
<script type="text/javascript">
    window.changyan.api.config({
        appid: "' . $mate['appid'] . '",
        conf: "' . $mate['appkey'] . '"
    });
</script>        
';
	}

	public function openCommnetPageWap()
	{
		$mate = $this->getChangyanConfig();
		$router = $this->getRoute();
		return '<div id="SOHUCS" sid="'.$router['articleId'].'"></div>
<script id="changyan_mobile_js" charset="utf-8" type="text/javascript" 
	src="http://changyan.sohu.com/upload/mobile/wap-js/changyan_mobile.js?client_id=' . $mate['appid'] . '&conf=' . $mate['appkey'] . '">
</script>';
	}

	/**
	 * 获取changyanID和secret
	 * @return array
	 */
	private function getChangyanConfig()
	{
		$netCfg = ConfigProxy::getFrameworkConfig('Net');
		if (!isset($netCfg['changYan']) || !isset($netCfg['changYan']['appid']) || !isset($netCfg['changYan']['appkey'])) {
			$errorType = ErrorType::getInstance();
			Kernel\throw_exception(new Exception(
					  $errorType->msg('E_SDK_CONFIG_NOT_EXIST'), $errorType->code('E_SDK_CONFIG_NOT_EXIST')
			));
		}
		return $netCfg->changYan;
	}

}