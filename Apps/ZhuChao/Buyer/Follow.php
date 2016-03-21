<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\Buyer\Model\Follow as FollowModel;
use Cntysoft\Kernel;

class Follow extends AbstractLib
{
   /**
    * 添加一条企业关注记录
    * 
    * @param integer $buyerId
    * @param integer $companyId
    * @return boolean
    */
   public function addFollow($buyerId, $companyId)
   {
      $follow = new FollowModel();
      $follow->setBuyerId((int)$buyerId);
      $follow->setCompanyId((int)$companyId);
      $follow->setFollowTime(time());
      return $follow->create();
   }
   
   /**
    * 获取指定条件的企业关注信息
    * 
    * @param array $cond
    * @param boolean $total
    * @param string $oderBy
    * @param integer $offset
    * @param integer $limit
    * @return array|
    */
   public function getFollowList(array $cond, $total = false, $oderBy = 'id DESC', $offset = 0, $limit = 15)
   {
      if($limit){
         $query = array(
            'order' => $oderBy,
            'limit' => array(
               'offset' => $offset,
               'number' => $limit
            )
         );
      }else{
         $query = array(
            'order' => $oderBy
         );
      }
      
      $query = array_merge($query, $cond);
      
      $items = FollowModel::find($query);
      
      if($total){
         return array($items, FollowModel::count($cond));
      }
      
      return $items;
   }
   
    /**
    * 删除指定id的关注信息
    * 
    * @param integer $buyerId
    * @param integer $ids
    * 
    */
   public function deleteFollows($buyerId, array $ids)
   {
      $cond[] = FollowModel::generateRangeCond('id', $ids);
      $cond[] = 'buyerId='.$buyerId;
      $cond = implode(' and ', $cond);
      $list = $this->getFollowList(array($cond), false, 'id DESC', 0, 0);
      if(count($list)){
         foreach($list as $follow){
            $follow->delete();
         }
      }
   }
   
   
   /**
    * 获取指定id的关注信息
    * 
    * @param integer $followId
    * @return 
    */
   public function getFollowById($followId)
   {
      return FollowModel::findFirst($followId);
   }
}
