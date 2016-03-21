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
use Cntysoft\Kernel;
use Cntysoft\Kernel\Exception;
use Cntysoft\Kernel\StdErrorType;
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
/**
 * 处理系统上传
 *
 * Class WebUploader
 * @package SysApiHandler
 */
class User extends AbstractScript
{
	/**
	 * 添加企业关注
	 * 
	 * @param array $params
	 */
	public function addFollow($params)
	{
		$this->checkRequireFields($params, array('id'));
		$companyId = $params['id'];
		//验证企业信息是否存在
		$this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_NAME, 'getCompanyById', array($companyId));

		$acl = $this->di->get('BuyerAcl');
		$user = $acl->getCurUser();
		$this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_FOLLOW, 'addFollow', array($user->getId(), $companyId));
	}

	/**
	 * 添加商品收藏
	 * 
	 * @param array $params
	 */
	public function addCollect($params)
	{
		$this->checkRequireFields($params, array('id'));
		$productId = $params['id'];
		//验证企业信息是否存在
		$this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductById', array($productId));

		$acl = $this->di->get('BuyerAcl');
		$user = $acl->getCurUser();
		$this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_COLLECT, 'addCollect', array($user->getId(), $productId));
	}
	/**
	 * 增加商品点击量
	 * @param array $params
	 * @return 
	 */
	public function addHits($params)
	{
		$this->checkRequireFields($params, array('id'));
		$productId = $params['id'];
		return $this->appCaller->call(
				  PRODUCT_CONST::MODULE_NAME, 
				  PRODUCT_CONST::APP_NAME, 
				  PRODUCT_CONST::APP_API_PRODUCT_MGR, 
				  'addHit', array($productId));
	}
	/**
	 * 退出
	 * @return type
	 */
	public function logout()
	{
		return $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'logout');
	}

}