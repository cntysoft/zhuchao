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
use App\Yunzhan\Product\Model\Group as GroupModel;
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
   
}