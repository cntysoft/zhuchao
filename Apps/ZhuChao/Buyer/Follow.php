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
      
      array_merge($query, $cond);
      
      $items = FollowModel::find($query);
      
      if($total){
         return array($items, FollowModel::count($cond));
      }
      
      return $items;
   }
   
   /**
    * 删除指定id的关注信息
    * 
    * @param integer $followId
    * 
    * @return boolean
    */
   public function deleteFollow($followId)
   {
      $follow = $this->getFollowById($followId);
      
      if(!$follow){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_FOLLOW_NOT_EXIST'), $errorType->code('E_BUYER_FOLLOW_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      return $follow->delete();
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
