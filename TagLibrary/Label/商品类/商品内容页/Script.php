<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\Product\Constant as GOODS_CONST;
use App\ZhuChao\MarketMgr\Constant as MAR_CONST;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
use Cntysoft\Framework\Utils\ChinaArea;
class Goods extends AbstractLabelScript
{
	protected $chinaArea = null;

	/**
	 * 获取商品信息
	 * @return type
	 */
	public function getProductById()
	{
		$gid = $this->getRouteInfo()['itemId'];
		return $this->appCaller->call(
							 GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductById', array($gid));
	}

	/**
	 * 获取商品列表
	 * @param array $cond
	 * @param boolean $total 是否分页
	 * @param string $orderBy
	 * @param integer $offset
	 * @param integer $limit
	 * @return list
	 */
	public function getGoodsList(array $cond, $total, $orderBy, $offset, $limit)
	{
		return $this->appCaller->call(
							 GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_PRODUCT_MGR, 'getProductList', array($cond, $total, $orderBy, $offset, $limit));
	}

	public function checkLogin()
	{
		
	}

	/**
	 * 从CDN上获取图片
	 * @param type $source
	 * @param type $width
	 * @param type $height
	 * @return type
	 */
	public function getImageFromCdn($source, $width, $height)
	{
		return \Cntysoft\Kernel\get_image_cdn_url($source, $width, $height);
	}

	public function getAreaFromCode($code)
	{
		$chinaArea = $this->getChinaArea();
		if ($code == null) {
			return "暂无";
		} else {
			return $chinaArea->getArea($code);
		}
	}

	public function getChinaArea()
	{
		if (null == $this->chinaArea) {
			$this->chinaArea = new ChinaArea();
		}
		return $this->chinaArea;
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
	 * 获取文章列表（不带分页）
	 * @param int $nodeId
	 * @return type
	 */
	public function getInfoListByNodeAndStatusNotPage($nodeId)
	{
		$limit = 5;
		$generalInfo = $this->appCaller->call(
				  CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, false, 'hits DESC', 0, $limit));
		return $generalInfo;
	}
	
	/**
    * 获取广告位的位置信息
    * 
    * @param string $port 设备端
    * @param string $module 模块端
    * @param string $location 位置信息
    * @return integer 返回该位置的位置id
    */
   public function getAdsLocationId($port, $module, $location)
   {
      return $this->appCaller->call(
                      MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsLocationId', array($port, $module, $location));
   }

   /**
    * 根据位置id获取该位置下的广告
    * 
    * @param integer $locationId 广告位置id
    * @return object 广告列表对象
    */
   public function getAds($locationId)
   {
      $ads = $this->appCaller->call(
              MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsList', array($locationId, 'sort asc'));
      return $ads;
   }

}