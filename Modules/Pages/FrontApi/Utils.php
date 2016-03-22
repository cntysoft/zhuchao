<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace PagesFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Site\Content\Constant as CONTENT_CONST;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\MessageMgr\Constant as MESSAGE_CONST;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
/**
 * 处理系统上传
 *
 * Class WebUploader
 * @package SysApiHandler
 */
class Utils extends AbstractScript
{
	/**
	 * 增加商品点击量
	 * 
	 * @param array $params
	 */
	public function addItemHits($params)
	{
		$this->checkRequireFields($params, array('id'));
		$this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'addHit', array($params['id']));
	}

	/**
	 * 增加文章点击量
	 * 
	 * @param array $params
	 */
	public function addArticleHits($params)
	{
		$this->checkRequireFields($params, array('id'));
		$this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'addHit', array($params['id']));
	}

	/**
	 * 获取当前登录用户信息
	 * @return type
	 */
	public function getCurUser()
	{
		return $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'getCurUser');
	}

	/**
	 * 添加询价信息
	 * @param array $params
	 * @return type
	 */
	public function addXunjiadan(array $params)
	{
		$this->checkRequireFields($params, array('number', 'content'));
		$goodsInfo = $this->appCaller->call(
				  PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($params['number']));
		$provider = $goodsInfo->getProvider();
		$curUser = $this->getCurUser();
		$data = array(
			'gid'			 => $goodsInfo->getId(),
			'uid'			 => $curUser->getId(),
			'providerId' => $provider->getId(),
			'expireTime' => 30,
			'content'	 => $params['content']
		);
		return $this->appCaller->call(
							 MESSAGE_CONST::MODULE_NAME, MESSAGE_CONST::APP_NAME, MESSAGE_CONST::APP_API_OFFER, 'addInquiry', array($data));
	}

	/**
	 * 增加商品点击量
	 * @param array $params
	 * @return 
	 */
	public function addHits($params)
	{
		$this->checkRequireFields($params, array('number'));
		$number = $params['number'];
		$product = $this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductByNumber', array($number));
		if (!$product) {
			$errorType = new ErrorType();
			Kernel\throw_exception(new Exception($errorType->msg('E_PRODUCT_MGR_NOT_EXIST'), $errorType->code('E_PRODUCT_MGR_NOT_EXIST')));
		}
		return $this->appCaller->call(
							 PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'addHit', array($product->getId()));
	}

	public function getInfoListByNodeAndStatus(array $params)
	{
		$this->checkRequireFields($params, array('limit', 'page', 'node'));
		$nodeInfo = $this->getNodeInfoByIdentifier($params['node']);
		$limit = (int) $params['limit'];
		$offset = ((int) $params['page'] - 1) * $limit;
		$nodeId = $nodeInfo->getId();
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, true, 'id DESC', $offset, $limit));
		$ret = array();
		foreach ($generalInfo[0] as $value) {
			$children = array(
				'title'	 => $value->getTitle(),
				'author'	 => $value->getAuthor(),
				'time'	 => date('Y-m-d', $value->getInputTime()),
				'hits'	 => $value->getHits(),
				'image'	 => \Cntysoft\Kernel\get_image_cdn_url($value->getDefaultPicUrl()[0], 110, 110),
				'id'		 => $value->getId()
			);
			$ret[] = $children;
		}
		return $ret;
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
	 * 根据指定条件获取商品列表
	 * 
	 * @param array $cond 查询条件
	 * @param boolean $total 是否分页
	 * @param string $orderBy 排序方式
	 * @param integer $offset 起始
	 * @param ingeter $limit 每页多少个
	 * @return object 商品对象列表
	 */
	public function getGoodsListBy(array $params)
	{
		$limit = (int) $params['limit'];
		$offset = ((int) $params['page'] - 1) * $limit;
		$goodsList = $this->appCaller->call(
				  PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductList', array(array(), false, 'inputTime DESC', $offset, $limit));
		$ret = array();
		foreach ($goodsList as $list) {
			$children = array(
				'id'		 => $list->getId(),
				'number'	 => $list->getNumber(),
				'name'	 => $list->getBrand() . $list->getTitle() . $list->getDescription(),
				'price'	 => $list->getPrice() > 0 ? $list->getPrice() : '面议',
				'image'	 => \Cntysoft\Kernel\get_image_cdn_url($list->getDefaultImage(), 108, 108)
			);
			$ret[] = $children;
		}
		return $ret;
	}

}