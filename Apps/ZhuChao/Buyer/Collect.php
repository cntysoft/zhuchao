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
		$isHave = CollectModel::findFirst(array(
			'buyerId = ?0 AND productId = ?1',
			'bind' => array(
				0 => $buyerId,
				1 => $productId
			)
		));
		if($isHave){
			return false;
		}
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
      
      $query = array_merge($query, $cond);

      $items = CollectModel::find($query);
      
      if($total){
         return array($items, CollectModel::count($cond));
      }
      
      return $items;
   }
   
   /**
    * 删除指定id的收藏信息
    * 
    * @param integer $buyerId
    * @param integer $ids
    * 
    */
   public function deleteCollects($buyerId, array $ids)
   {
      $cond[] = CollectModel::generateRangeCond('id', $ids);
      $cond[] = 'buyerId='.$buyerId;
      $cond = implode(' and ', $cond);
      $list = $this->getCollectList(array($cond), false, 'id DESC', 0, 0);
      if(count($list)){
         foreach($list as $collect){
            $collect->delete();
         }
      }
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
	/**
	 * 检查用户是否收藏该商品
	 * @param int $uid
	 * @param int $gid
	 * @return type
	 */
	public function checkCollect($uid,$gid)
	{
		return CollectModel::findFirst(array(
			'buyerId = ?0 AND productId = ?1',
			'bind' => array(
				0 => $uid,
				1 => $gid
			)
		));
	}
}
