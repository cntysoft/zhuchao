<?php

/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use App\ZhuChao\GoodsMgr\Constant as GOODS_CONST;
use App\ZhuChao\CategoryMgr\Constant as GOODS_CATE_CONST;
use Cntysoft\Framework\Qs\View;

/**
 * 系统的回接口
 */
class ProductController extends AbstractController {

	public function productAction() {
		$productId = $this->dispatcher->getParam('productid');
		if (null === $productId) {
			$this->dispatcher->forward(array(
				'module' => 'Front',
				'controller' => 'Exception',
				'action' => 'pageNotExist'
			));
			return false;
		}
		$productId = (int) $productId;
		$appCaller = $this->getAppCaller();

		$appCaller->call(
				  GOODS_CONST::MODULE_NAME, GOODS_CONST::APP_NAME, GOODS_CONST::APP_API_GOODS, 'getGoodsInfo', array($productId)
		);

		$this->setupRenderOpt(array(
			View::KEY_RESOLVE_DATA => 'product',
			View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
		));
	}

	public function productclassifylistAction() {
		$appCaller = $this->getAppCaller();
		$categoryId = $this->dispatcher->getParam('categoryId');
		$node = $appCaller->call(
				  GOODS_CATE_CONST::MODULE_NAME, GOODS_CATE_CONST::APP_NAME, GOODS_CATE_CONST::APP_API_MGR, 'getNode', array($categoryId)
		);
		if ($node) {
			$nodeId = $node->getId();
			$children = $appCaller->call(
					  GOODS_CATE_CONST::MODULE_NAME, GOODS_CATE_CONST::APP_NAME, GOODS_CATE_CONST::APP_API_MGR, 'getChildren', array($nodeId));
			if (count($children)) {
				$this->dispatcher->forward(array(
					'module' => 'Front',
					'controller' => 'Exception',
					'action' => 'pageNotExist'
				));
				return false;
			}
			return $this->setupRenderOpt(array(
						  View::KEY_RESOLVE_DATA => 'productclassifylist',
						  View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
			));
		} else {
			$this->dispatcher->forward(array(
				'module' => 'Front',
				'controller' => 'Exception',
				'action' => 'pageNotExist'
			));
			return false;
		}
	}

	public function classifylistAction() {
		$this->setupRenderOpt(array(
			View::KEY_RESOLVE_DATA => 'classifylist',
			View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
		));
	}

	public function searchpageAction() {
		$this->setupRenderOpt(array(
			View::KEY_RESOLVE_DATA => 'searchpage',
			View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
		));
	}

	public function itemAction() {
		$this->setupRenderOpt(array(
			View::KEY_RESOLVE_DATA => 'product',
			View::KEY_RESOLVE_TYPE => View::TPL_RESOLVE_MAP
		));
	}

}
