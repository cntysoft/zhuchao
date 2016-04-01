<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\Product\Model\Group as GroupModel;
use App\ZhuChao\Product\Model\Product2Group as PGModel;
use Cntysoft\Kernel;

class GroupMgr extends AbstractLib
{
   protected $tree = null;
   
   /**
    * @param integer $providerId
    * @return \Cntysoft\Stdlib\Tree
    */
   public function getGroupTree($providerId = 0)
   {
      if (null == $this->tree) {
         if($providerId){
            $nodes = GroupModel::find(array(
               'providerId=?0',
               'bind' => array(
                  0 => $providerId
               )
            ));
         }else{
            $nodes = GroupModel::find();
         }
         
         $root = new GroupModel();
         $root->assign(array(
            'id'   => 0,
            'pid'  => -1,
            'text' => '商品品类库'
         ));
         $tree = new \Cntysoft\Stdlib\Tree($root);
         foreach ($nodes as $node) {
            //在这里是否获取地址
            $tree->setNode($node->getId(), $node->getPid(), $node);
         }
         $this->tree = $tree;
      }
      return $this->tree;
   }
   
   /**
    * 添加一个产品分类
    * 
    * @param integer $providerId
    * @param array $params
    */
   public function addGroup($providerId, array $params)
   {
      $this->checkRequireFields($params, array('name', 'pid'));
      
      $tree = $this->getGroupTree($providerId);
      $groups = $tree->getChildren($params['pid'], 1);
      if(count($groups) >= Constant::GROUP_MAX_NUM){
         $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_GROUP_MAX_NUM'), $errorType->code('E_GROUP_MAX_NUM')
            ), $this->getErrorTypeContext());
      }
      
      if(0 != $params['pid']){
         $group = GroupModel::findFirst(array(
            'id=?0',
            'bind' => array(
               0 => (int)$params['pid']
            )
         ));
         if(!$group){
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_GROUP_NOT_EXIST'), $errorType->code('E_GROUP_NOT_EXIST')
            ), $this->getErrorTypeContext());
         }
         
         if(0 != $group->getPid()){
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_GROUP_MAX_LEVEL_OVER'), $errorType->code('E_GROUP_MAX_LEVEL_OVER')
            ), $this->getErrorTypeContext());
         }
      }
      $group = new GroupModel();
      $group->setPid($params['pid']);
      $group->setName($params['name']);
      $group->setInputTime(time());
      $group->setProviderId($providerId);
      
      return $group->create();
   }
   
   /**
    * 删除指定用户的指定id的分组
    * 
    * @param integer $providerId
    * @param array $ids
    * @throws 
    */
   public function deleteGroups($providerId, array $ids)
   {
      $cond[] = GroupModel::generateRangeCond('id', $ids);
      $cond[] = 'providerId='.(int)$providerId;
      $cond = implode(' and ', $cond);
      
      $groups = GroupModel::find(array(
         $cond
      ));
      
      $db = Kernel\get_db_adapter();
      try{
         $db->begin();

         foreach($groups as $group){
            $p2g = $group->getP2g();
            foreach($p2g as $pg){
               $pg->delete();
            }
            $children = $this->getChildGroup($providerId, $group->getId(), false);
            if(count($children)){
               $this->deleteGroups($providerId, $children);
            }
            $group->delete();
         }
         $db->commit();
      } catch (Exception $ex) {
         $db->rollback();

         Kernel\throw_exception($ex);
      }
   }
   
   /**
    * 修改分组的信息，目前可以修改名称
    * 
    * @param integer $providerId
    * @param integer $id
    * @param array $params
    */
   public function modifyGroup($providerId, $id, array $params)
   {
      $group = GroupModel::findFirst(array(
         'providerId = ?0 and id = ?1',
         'bind' => array(
            0 => (int)$providerId,
            1 => (int)$id 
         )
      ));
      
      if(!$group){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_GROUP_NOT_EXIST'), $errorType->code('E_GROUP_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      $group->setName($params['name']);
      $group->update();
   }
   
   /**
    * 获取指定用户指定id分组的子分组
    * 
    * @param integer $providerId
    * @param integer $id
    * @param boolean $flag
    */
   public function getChildGroup($providerId, $id, $flag = true)
   {
      $tree = $this->getGroupTree($providerId);
      
      return $tree->getChildren($id, 1, $flag);
   }
   
   /**
    * 获取指定供应商的分组信息
    * 
    * @param integer $providerId
    * @return 
    */
   public function getGroupByProvider($providerId)
   {
      return GroupModel::find(array(
         'providerId=?0',
         'bind' => array(
            0 => $providerId
         )
      ));
   }
   
   public function getProductByGroup($providerId, $groupId, $total = false, $orderBy = 'productId DESC', $offset = 0, $limit = 15)
   {
      $ids = array();
      if($groupId){
         $group = GroupModel::findFirst(array(
            'providerId=?0 and id=?1',
            'bind' => array(
               0 => $providerId,
               1 => $groupId
            )
         ));
         
         if(!$group){
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_GROUP_NOT_EXIST'), $errorType->code('E_GROUP_NOT_EXIST')
            ), $this->getErrorTypeContext());
         }
         $ids = array($groupId);
         $cond = PGModel::generateRangeCond('groupId', $ids);
         $items = PGModel::find(array(
            $cond,
            'order' => $orderBy,
            'limit' => array(
               'offset' => $offset,
               'number' => $limit
            )
         ));
         $ret = array();
         foreach($items as $one){
            $ret[] = $one->getProduct();
         }
         
         if($total){
            return array($ret, PGModel::count(array($cond)));
         }
         
         return $ret;  
      }else{
         return $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_PRODUCT_MGR,
            'getProductList',
            array(array('providerId='.$providerId .' and status!='. Constant::PRODUCT_STATUS_DRAFT . ' and status!='.Constant::PRODUCT_STATUS_DELETE),  $total, 'id DESC', $offset, $limit)
         );
      }
   }
   
   /**
    * 修改商品和分组的关系
    * 
    * @param integer $providerId
    * @param integer $groupId
    * @param array $numbers
    */
   public function changeGroupProduct($providerId, $groupId, array $numbers)
   {
      $products = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_PRODUCT_MGR,
         'getProductByNumbers',
         array($numbers)
      );
      $newPid = array();
      foreach($products as $product){
         $newPid[] = $product->getId();
      }
      
      $modelsManager = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE '. PGModel::generateRangeCond('productId', $newPid), 'App\ZhuChao\Product\Model\Product2Group');
      $modelsManager->executeQuery($query);
      
      if($groupId){
         $group = GroupModel::findFirst(array(
            'providerId=?0 and id=?1',
            'bind' => array(
               0 => $providerId,
               1 => $groupId
            )
         ));

         if(!$group){
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
               $errorType->msg('E_GROUP_NOT_EXIST'), $errorType->code('E_GROUP_NOT_EXIST')
            ), $this->getErrorTypeContext());
         }

         foreach($newPid as $pid){
            $npg = new PGModel();
            $npg->setGroupId($groupId);
            $npg->setProductId($pid);
            $npg->create();
            unset($npg);
         }
      }
   }
}