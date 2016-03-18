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

	protected function getChinaArea()
	{
		if (null == $this->chinaArea) {
			$this->chinaArea = new ChinaArea();
		}
		return $this->chinaArea;
	}

}