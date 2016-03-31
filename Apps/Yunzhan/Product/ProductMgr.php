<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\Yunzhan\Product;
use Cntysoft\Kernel\App\AbstractLib;
use App\Yunzhan\Product\Model\Product as ProductModel;
use App\Yunzhan\Product\Model\ProductDetail as DetailModel;
use App\Yunzhan\Product\Model\Product2Group as PGModel;
use Cntysoft\Kernel;
use App\Yunzhan\CategoryMgr\Constant as CATEGORY_CONST;
use Cntysoft\Framework\Core\FileRef\Manager as RefManager;

class ProductMgr extends AbstractLib
{
   /**
    * 获取指定筛选条件的产品列表
    * 
    * @param array $cond
    * @param boolean $total
    * @param string $orderBy
    * @param integer $offset
    * @param integer $limit
    */
   public function getProductList(array $cond = array(), $total = false, $orderBy = 'id DESC', $offset = 0, $limit = \Cntysoft\STD_PAGE_SIZE)
   {
      if($limit){
         $query = array(
            'order' => $orderBy,
            'limit' => array(
               'offset' => $offset,
               'number' => $limit
            )
         );
      }else{
         $query = array(
            'order' => $orderBy,
         );
      }
      
      if(!empty($cond)){
         $query += $cond;
      }
      
      $items = ProductModel::find($query);
      
      if($total){
         return array($items, ProductModel::count($cond));
      }
      
      return $items;
   }
   
   /**
    * 添加一个产品
    * 
    * @param integer $providerId
    * @param integer $companyId
    * @param array $params
    * @return boolean
    */
   public function addProduct(array $params)
   {
      $product = new ProductModel();
      $detail = new DetailModel();
      $dfields = $detail->getRequireFields(array('id'));
      $this->checkRequireFields($params, $dfields);
      $pfields = $product->getRequireFields(array('id', 'number', 'hits', 'star', 'grade', 'searchAttrMap', 'indexGenerated', 'inputTime', 'updateTime', 'detailId'));
      foreach (array('price') as $val) {
         array_push($pfields, $val);
      }

      $this->checkRequireFields($params, $pfields);
      $ddata = $this->filterData($params, $dfields);
      $pdata = $this->filterData($params, $pfields);

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $detail->assignBySetter($ddata);
         $detail->create();

         $pdata['number'] = isset($params['number']) ? $params['number'] : $this->getProductNumber($params['categoryId']);
         $pdata['hits'] = 0;
         $pdata['defaultImage'] = $ddata['images'][0][0];
         $pdata['star'] = 5;
         $pdata['grade'] = 5;
         $pdata['searchAttrMap'] = '';
         $pdata['indexGenerated'] = 0;
         $pdata['inputTime'] = time();
         $pdata['updateTime'] = 0;
         $pdata['detailId'] = $detail->getId();

         $product->assignBySetter($pdata);

         if (isset($params['group']) && !empty($params['group'])) {
            $group = $params['group'];
            if (!is_array($group)) {
               $group = array($group);
            }
            $productId = $product->getId();
            foreach ($group as $one) {
               $join = new PGModel();
               $join->setProductId($productId);
               $join->setGroupId($one);
               $join->create();
               unset($join);
            }
         }
         $product->create();
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollback();

         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }
   
   /**
    * 
    * @param integer $productId
    * @param array $params
    * @return type修改一个产品的信息
    * 
    * @param integer $productId
    * @param array $params
    */
   public function updateProduct($productId, array $params)
   {
      $product = $this->getProductById($productId);
      $detail = $product->getDetail();
      $dfields = $detail->getRequireFields(array('id'));
      $pfields = $product->getRequireFields(array('id', 'number', 'hits', 'defaultImage', 'star', 'grade', 'searchAttrMap', 'indexGenerated', 'inputTime', 'updateTime', 'detailId'));
      foreach(array('price') as $val){
         array_push($pfields, $val);	
      }
      $ddata = $this->filterData($params, $dfields);
      $pdata = $this->filterData($params, $pfields);
      
      $db = Kernel\get_db_adapter();
      try{
         $db->begin();
         $detail->assignBySetter($ddata);
         $detail->update();
         
         $product->setDefaultImage($ddata['images'][0][0]);
         $product->assignBySetter($pdata);
         $product->setUpdateTime(time());
         $product->setIndexGenerated(0);
         $product->update();
         
         if (isset($params['group']) && !empty($params['group'])) {
            $group = $params['group'];
            if (!is_array($group)) {
               $group = array($group);
            }
            $oldGroup = array();
            $groups = $product->getGroups();
            if(count($groups)){
               foreach($groups as $one){
                  array_push($oldGroup, $one->getId());
               }
            }
            
            $deleteGroup = array_diff($oldGroup, $group);
            $addGroup = array_diff($group, $oldGroup);
            
            if(count($deleteGroup)){
               $modelsManager = Kernel\get_models_manager();
               $query = sprintf('DELETE FROM %s WHERE '. PGModel::generateRangeCond('productId', $deleteGroup), 'App\ZhuChao\Product\Model\Product2Group');
               $modelsManager->executeQuery($query);
            }
            
            foreach ($addGroup as $groupId) {
               $join = new PGModel();
               $join->setProductId($productId);
               $join->setGroupId($groupId);
               $join->create();
               unset($join);
            }
         }
         return $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      } 
   }
   
   /**
    * 获取指定id的产品信息
    * 
    * @param integer $productId
    */
   public function getProductById($productId)
   {
      return ProductModel::findFirst($productId);
   }
   
   /**
    * 修改多个商品的状态
    * 
    * @param array $productNumbers
    * @param integer $status
    */
   public function changeProductsStauts(array $productNumbers, $status)
   {
      if(!in_array($status, array(
         Constant::PRODUCT_STATUS_VERIFY,
         Constant::PRODUCT_STATUS_SHELF,
         Constant::PRODUCT_STATUS_DELETE
      ))){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_PRODUCT_STATUS_ERROR'), $errorType->code('E_PRODUCT_STATUS_ERROR')
         ), $this->getErrorTypeContext());
      }
      $cond[] = ProductModel::generateRangeCond('number', $productNumbers);
      $cond = implode(' and ', $cond);
      $list = $this->getProductList(array($cond), false, 'id DESC', 0, 0);
      if(count($list)){
         foreach ($list as $product){
            $product->setStatus($status);
            $product->setUpdateTime(time());
            $product->update();
         }
      }
   }
   /**
    * 根据商品编号获取商品信息
    * 
    * @param string $number
    * @return 
    */
   public function getProductByNumber($number)
   {
      return ProductModel::findFirst(array(
         'number=?0',
         'bind' => array(
            0 => $number
         )
      ));
   }
   
   /**
    * 获得产品的编号
    * 
    * @param integer $categoryId
    * @param string $prefix
    * @return string
    */
   public function getProductNumber($categoryId = 0, $prefix = 'ZC')
   {
      $number = $prefix;
      $len = strlen($categoryId);
      for($i = $len; $i < 3; $i++){
         $number.='0';
      }
      $number .= $categoryId;
      $number.=time();
      
      for ($i = 0; $i < 3; $i++) {
         $number .= rand(0, 9);
      }
      if($this->checkNumberExist($number)){
         return $this->getProductNumber($categoryId, $prefix);
      }else{
         return $number;
      }
   }
   
   /**
    * 获取数据中需要字段的数据
    * 
    * @param array $data
    * @param array $fields
    * @return array
    */
   public function filterData(array $data, array $fields)
   {
      $ret = array();
      foreach($data as $key => $val){
         if(in_array($key, $fields)){
            $ret[$key] = $val;
         }
      }
      
      return $ret;
   }
   
   /**
    * 检查产品编号是否已经存在
    * 
    * @param type $number
    * @return type
    */
   public function checkNumberExist($number)
   {
      return ProductModel::count(array(
         'number=?0',
         'bind' => array(
            0 => $number
         )
      )) > 0 ? true : false;
   }
   
   /** 
    * @return \App\Yunzhan\CategoryMgr\Mgr
    */
   public function getGoodsCategoryAppObject()
   {
      return $this->getAppCaller()->getAppObject(
         CATEGORY_CONST::MODULE_NAME, 
         CATEGORY_CONST::APP_NAME, 
         CATEGORY_CONST::APP_API_MGR
      );
   }
   
   /**
    * 修改指定产品的状态
    * 
    * @param integer $productId
    * @param integer $status
    * @param string $comment
    * @return boolean
    */
   public function changeStatus($productId, $status, $comment = '')
   {
      $product = $this->getProductById($productId);
      
      if(!$product){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_PRODUCT_MGR_NOT_EXIST'), $errorType->code('E_PRODUCT_MGR_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $product->setStatus($status);
      $product->setComment($comment);
      return $product->update();
   }
   
   /**
    * 获取指定分类下面的商品数目
    * 
    * @param int $cid
    * @return int
    */
   public function countCategoryProduct($cid, $withChildren = false)
   {
      if (!$withChildren) {
         return ProductModel::count(array(
            'categoryId = ?0',
            'bind' => array(
               0 => (int) $cid
            )
         ));
      } else {
         $query = array();
         if ($cid != 0) {
            $gcategoryTree = $this->getAppCaller()->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getNodeTree');
            $cNodes = $gcategoryTree->getChildren($cid, -1, false);
            array_unshift($cNodes, $cid);
            $query[] = \Cntysoft\Phalcon\Mvc\Model::generateRangeCond('categoryId', $cNodes);
         }
         return ProductModel::count($query);
      }
   }
   
   /**
    * 生成商品搜索数据
    * 
    * @param int $cid
    * @param int $page
    * @param int $pageSize
    */
   public function generateGoodsSearchAtts($cid, $page = 1, $pageSize = \Cntysoft\STD_PAGE_SIZE)
   {
      $query = array();
      if ($cid != 0) {
         $gcategoryTree = $this->getAppCaller()->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_MGR, 'getNodeTree');
         $cNodes = $gcategoryTree->getChildren($cid, -1, false);
         array_unshift($cNodes, $cid);
         $query[] = \Cntysoft\Phalcon\Mvc\Model::generateRangeCond('categoryId', $cNodes);
      }
      $query['limit'] = array(
         'number' => $pageSize,
         'offset' => ($page - 1) * $pageSize,
         'order'  => 'categoryId ASC',
      );
      $goods = ProductModel::find($query);
      if (count($goods) > 0) {
         $searchKeyPool = array();
         foreach ($goods as $ginfo) {
            $attrPool = array();
            //所有的商品规格和商品属性全部生成
            $dinfo = $ginfo->getDetail();

            foreach ($dinfo->getAttribute() as $gkey => $normalAttrs) {
               foreach ($normalAttrs as $akey => $aval) {
                  //每个属性的值都生成查询条件
                  $avalAttrs = explode(' ', $aval);
                  foreach ($avalAttrs as $avalAttr) {
                     $attrPool[] = md5(strtolower(preg_replace(Constant::ATTR_FILTER_REGEX, '', $akey . $avalAttr)));
                  }
               }
            }
            $ginfo->setSearchAttrMap(implode(' ', $attrPool));
            $ginfo->setIndexGenerated(1);
            return $ginfo->save();
         }
      }
   }
   
}