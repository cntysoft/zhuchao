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
      $group = new GroupModel();
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
         ), $this->getErrorTypeContext())
      }
      
      $group->setName($params['name']);
      $group->update();
   }
   
   /**
    * 获取指定用户指定id分组的子分组
    * 
    * @param integer $provider
    * @param integer $id
    * @param boolean $flag
    */
   public function getChildGroup($providerId, $id, $flag = true)
   {
      $tree = $this->getGroupTree($providerId);
      
      return $tree->getChildren($id, 1, $flag);
   }
}