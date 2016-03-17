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
use App\ZhuChao\Buyer\Model\Collect as CollectModel;
use Cntysoft\Kernel;

class Collect extends AbstractLib
{
   /**
    * 添加一条商品收藏记录
    * 
    * @param integer $buyerId
    * @param integer $productId
    * @return boolean
    */
   public function addCollect($buyerId, $productId)
   {
      $collect = new CollectModel();
      $collect->setBuyerId((int)$buyerId);
      $collect->setProductId((int)$productId);
      $collect->setCollectTime(time());
      return $collect->create();
   }
   
   /**
    * 获取指定条件的商品收藏信息
    * 
    * @param array $cond
    * @param boolean $total
    * @param string $oderBy
    * @param integer $offset
    * @param integer $limit
    * @return array|
    */
   public function getCollectList(array $cond, $total = false, $oderBy = 'id DESC', $offset = 0, $limit = 15)
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
      
      $items = CollectModel::find($query);
      
      if($total){
         return array($items, CollectModel::count($cond));
      }
      
      return $items;
   }
   
   /**
    * 删除指定id的收藏信息
    * 
    * @param integer $collectId
    * @return boolean
    */
   public function deleteCollect($collectId)
   {
      $collect = $this->getCollectById($collectId);
      
      if(!$collect){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_BUYER_COLLECT_NOT_EXIST'), $errorType->code('E_BUYER_COLLECT_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
      
      return $collect->delete();
   }
   
   /**
    * 获取指定id的收藏信息
    * 
    * @param integer $collectId
    * @return 
    */
   public function getCollectById($collectId)
   {
      return CollectModel::findFirst($collectId);
   }
}
