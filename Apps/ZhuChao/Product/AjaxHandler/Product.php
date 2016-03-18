<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;

class Product extends AbstractHandler
{
   /**
    * 获取指定条件下的产品的信息
    * 
    * @param array $params
    * @return array
    */
   public function getProductList(array $params)
   {
      $this->checkRequireFields($params, array('start', 'limit'));
      $cond = array();
      if(isset($params['name']) && $params['name']){
         $cond[] = "(brand like '%".$params['name']."%' or title like '%".$params['name']."%' or description like '%".$params['name']."%')";
      }
      $cid = (int) $params['cid'];
      $gcategoryTree = $this->getAppCaller()->call(
         CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR,
         'getNodeTree'
         );
      if (0 != $cid) {
         $cNodes = $gcategoryTree->getChildren($cid, -1, false);
         array_unshift($cNodes, $cid);
         $cond[] = \Cntysoft\Phalcon\Mvc\Model::generateRangeCond('categoryId', $cNodes);
      }

      $query = array(implode(' and ', $cond));
      $list = $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME, 
         PRODUCT_CONST::APP_NAME, 
         PRODUCT_CONST::APP_API_PRODUCT_MGR, 
         'getProductList', 
         array($query, true, 'id DESC', $params['start'], $params['limit'])
      );

      $total = $list[1];
      $list = $list[0];
      $ret = array();
      if (count($list)) {
         $ret = $this->formatProductInfo($list);
      }
      return array(
         'total' => $total,
         'items' => $ret
      );
   }
   
   /**
    * 添加一个产品信息
    * 
    * @param array $params
    */
   public function addProduct(array $params)
   {
      $providerId = 0;
      $companyId = (int)$params['companyId'];
      unset($params['companyId']);
      if($companyId){
         $company = $this->getAppCaller()->call(
            PROVIDER_CONST::MODULE_NAME,
            PROVIDER_CONST::APP_NAME,
            PROVIDER_CONST::APP_API_MANAGER,
            'getProviderCompany',
            array($companyId)
         );
         $providerId = $company->getProviderId();
      }
      $keyword = array();
      $keyword[] = $params['keywords1'];
      unset($params['keywords1']);
      $keyword[] = $params['keywords2'];
      unset($params['keywords2']);
      $keyword[] = $params['keywords3'];
      unset($params['keywords3']);
      $params['keywords'] = $keyword;
      
      
      return $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'addProduct',
         array($providerId, $companyId, $params)
      );
   }
   
   /**
    * 更新一个产品的信息
    * 
    * @param array $params
    * @return type
    */
   public function updateProduct(array $params)
   {
      $productId = $params['productId'];
      unset($params['productId']);
      
      return $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'updateProduct',
         array($productId, $params)
      );
   }
   
   /**
    * 获取一个产品的全部信息
    * 
    * @param array $params
    * @return array
    */
   public function getProductInfo(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      
      $product = $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductById',
         array((int)$params['id'])
      );
      $ret = array();
      $detail = $product->getDetail();
      $ret = $detail->toArray(true);
      $parray = $product->toArray(true);
      
      $ret = array_merge($ret, $parray);
      $ret['keywords1'] = $ret['keywords'][0];
      $ret['keywords2'] = $ret['keywords'][1];
      $ret['keywords3'] = $ret['keywords'][2];
      unset($ret['keywords']);
      $category = $this->getAppCaller()->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'getNode',
         array($ret['categoryId'])
      );
      
      $ret['category'] = $category->getName();
      $ret['imgRefMap'] = $ret['imgRefMap'] && is_array($ret['imgRefMap']) ? $ret['imgRefMap'] : array();
      $ret['fileRefs'] = $ret['fileRefs'] && is_array($ret['fileRefs']) ? $ret['fileRefs'] : array();
      
      if($ret['companyId']){
         $company = $this->getAppCaller()->call(
            PROVIDER_CONST::MODULE_NAME,
            PROVIDER_CONST::APP_NAME,
            PROVIDER_CONST::APP_API_MANAGER,
            'getProviderCompany',
            array($ret['companyId'])
         );
         $ret['company'] = $company->getName();
      }
      return $ret;
   }
   /**
    * 获取全部的企业信息
    * 
    * @param array $params
    * @return type
    */
   public function getCompanyList(array $params)
   {
      $name = '';
      if(isset($params['query']) && $params['query']){
         $name = $params['query'];
      }
      $list = $this->getAppCaller()->call(
         PROVIDER_CONST::MODULE_NAME,
         PROVIDER_CONST::APP_NAME,
         PROVIDER_CONST::APP_API_LIST,
         'getProviderCompanyListAll',
         array($name)
      );
      
      $ret = array();
      
      foreach($list as $company){
         $item = array(
            'text' => $company->getName(),
            'id' => $company->getId()
         );
         array_push($ret, $item);
      }
      
      return $ret;
   }
   
   /**
    * 获取产品分类下面的子分类
    * 
    * @param array $params
    * @return boolean
    */
   public function getCategoryChildren(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $mgr = $this->getAppCaller()->getAppObject(
         CATEGORY_CONST::MODULE_NAME, 
         CATEGORY_CONST::APP_NAME, 
         CATEGORY_CONST::APP_API_MGR
      );
      $tree = $mgr->getNodeTree();
      $nodes = $tree->getChildren($id, 1, true);
      $ret = array();
      foreach ($nodes as $node) {
         $item = $node->toArray(true);
         $item['text'] = $item['name'];
         unset($item['name']);
         if ($item['nodeType'] == CATEGORY_CONST::NODE_TYPE_DETAIL_CATEGORY) {
            $item['leaf'] = true;
         }
         $ret[] = $item;
      }
      return $ret;
   }
   
   /**
    * 获取产品分类的属性
    * 
    * @param array $params
    * @return type
    */
   public function getCategoryAttrs(array $params)
   {
      $this->checkRequireFields($params, array('categoryId'));
      $cid = (int) $params['categoryId'];
      $mgr = $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME, 
         PRODUCT_CONST::APP_NAME, 
         PRODUCT_CONST::APP_API_PRODUCT_MGR, 
         'getGoodsCategoryAppObject'
      );
      $data = $mgr->getNodeAttrs($cid);
      $ret = array(
         'normalAttrs' => array()
      );

      foreach ($data as $item) {
         $attr = array(
            'id'       => $item->getId(),
            'name'     => $item->getName(),
            'optValue' => $item->getOptValue(),
            'required' => $item->getRequired(),
            'group'    => $item->getGroup()
         );
         $ret['normalAttrs'][] = $attr;
      }

      return $ret;
   }
   
   /**
    * 处理要返回的产品数据
    * 
    * @param $list
    * @return array
    */
   public function formatProductInfo($list)
   {
      $ret = array();
      
      foreach($list as $val){
         $item = array(
            'id' => $val->getId(),
            'name' => $val->getBrand() . $val->getTitle() . $val->getDescription(),
            'number' => $val->getNumber(),
            'price' => $val->getPrice() ? $val->getPrice() : '面议',
            'grade' => $val->getGrade(),
            'inputTime' => date('Y-m-d', $val->getInputTime()),
            'status' => $val->getStatus()
         );

         array_push($ret, $item);
      }
      
      return $ret;
   }
   
   /**
    * 修改产品的状态
    * 
    * @param array $params
    * @return boolean
    */
   public function changeStatus(array $params)
   {
      $this->checkRequireFields($params, array('id', 'status'));
      $comment = '系统处理！';
      $status = (int)$params['status'];
      switch($status){
         case PRODUCT_CONST::PRODUCT_STATUS_REJECTION:
            $comment = '系统审核失败，请修改后重新提交！';
            break;
         case PRODUCT_CONST::PRODUCT_STATUS_VERIFY:
            $comment = '系统审核通过！';
            break;
         case PRODUCT_CONST::PRODUCT_STATUS_SHELF:
            $comment = '系统下架，请联系客服！';
            break;
      }

      return $this->getAppCaller()->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'changeStatus',
         array((int)$params['id'], $status, $comment)
      );
   }
   
   /**
    * 查找指定商品分类下面的商品数量
    * 
    * @param array $params
    * @return type
    */
   public function getProductTotalNumByCatgory(array $params)
   {
      $this->checkRequireFields($params, array('cid'));
      return array('total' => $this->getAppCaller()->call(
              PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 
              'countCategoryProduct', 
              array(
                 (int) $params['cid'], true
                 )));
   }

   /**
    * 生成指定商品分类下面商品的搜索信息
    * 
    * @param array $params
    */
   public function generateSearchAttrMapByCategory(array $params)
   {
      $this->checkRequireFields($params, array('cid', 'page', 'pageSize'));
      $this->getAppCaller()->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'generateGoodsSearchAtts', array(
         (int) $params['cid'],
         (int) $params['page'],
         (int) $params['pageSize']
      ));
   }
   
}
