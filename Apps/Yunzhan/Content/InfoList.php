<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Content;
use App\Yunzhan\CmMgr\Model\General as GeneralModel;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\Yunzhan\CmMgr\Constant as CM_CONST;
use App\Yunzhan\Category\Constant as CATE_CONST;
/**
 * 信息列表获取类
 */
class InfoList extends AbstractLib
{

   /**
    * 根据指定的ids一次获取基本基本数据集合
    * 
    * @param array $ids
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getInfosByIds(array $ids, $withSubmodel = true)
   {
      $ret = array();
      $mgr = new Manager();
      foreach ($ids as $id) {
         if($withSubmodel){
            $info = $mgr->read((int) $id);
         }else{
            $info = GeneralModel::findFirst($id);
         }
         
         $ret[] = $info;
      }
      return $ret;
   }

   /**
    * 检查信息的标题是否存在
    *
    * @param int $cmid
    * @param int $nid
    * @param string $title
    * @return boolean
    */
   public function titleExist($cmid, $nid, $title)
   {
      return GeneralModel::count(array(
            'cmodelId = ?0 AND nodeId = ?1 AND title = ?2',
            'bind' => array(
               0 => (int) $cmid,
               1 => (int) $nid,
               2 => $title
            )
         )) > 0 ? true : false;
   }

   /**
    * 获取节点相关状态的信息列表
    *
    * @param int $id
    * @param int $type
    * @param int $status
    * @param bool $total
    * @param string $orderBy
    * @param int $offset
    * @param int $limit
    */
   public function getInfoListByNodeAndStatus($id, $type, $status, $total = false, $orderBy = null, $offset = 0, $limit = 15)
   {
      $id = (array) $id;
      if (!in_array($status, $this->getSupportInfoStatus())) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_INFO_STATUS_NOT_SUPPORT', $status),
            $errorType->code('E_INFO_STATUS_NOT_SUPPORT')),
            $this->getErrorTypeContext());
      }
      $cond = array();
      if (!in_array(0, $id)) {
         $cond[] = GeneralModel::generateRangeCond('nodeId', $id);
      }
      $cond[] = 'isDeleted = 0';
      if ($status !== Constant::INFO_S_ALL) {
         $cond[] = "status = $status";
      }
      if (is_array($type)) {
         if (!in_array(Constant::INFO_M_ALL, $type)) {
            $cond[] = GeneralModel::generateRangeCond('cmodelId', $type);
         }
      } else {
         if ($type !== Constant::INFO_M_ALL) {
            $cond[] = "cmodelId = $type";
         }
      }
      //生成查询条件
      $cond = implode(' and ', $cond);

      $items = GeneralModel::find(array(
            $cond,
            'order' => $orderBy,
            'limit' => array(
               'number' => $limit,
               'offset' => $offset
            )
      ));
      if ($total) {
         return array($items, (int) GeneralModel::count(array(
               $cond
         )));
      }
      return $items;
   }

   /**
    * 获取某一个节点的所有信息
    * 
    * @param int $nodeId
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getInfoListByNode($nodeId)
   {
      $infoList = GeneralModel::find(array(
            'nodeId = ?0',
            'bind' => array(
               0 => $nodeId
            ))
      );

        return $infoList;
    }
    
	/**
	 * 获取上一篇下一篇
	 *
	 * @param int $nid
	 * @param int $id
	 * @return array
	 */
	public function getPrevAndNextItem($nid, $id)
	{
		$ret = array();
		$condPrev = $condNext = array('nodeId = ' . $nid, 'isDeleted = 0', 'status = ' . Constant::INFO_S_VERIFY);
		$condPrev[] = 'id > ' . $id;
		$condNext[] = 'id < ' . $id;
		$condNext = implode(' AND ', $condNext);
		$condPrev = implode(' AND ', $condPrev);
		$prev = GeneralModel::findFirst($condPrev);
		$next = GeneralModel::findFirst(array($condNext, 'order' => 'id DESC'));
		$ret['prev'] = is_object($prev) ? array($this->getItemUrl($prev->getId()), $prev->getTitle(), $prev->getDefaultPicUrl() ? $prev->getDefaultPicUrl()[0] : '') : null;
		$ret['next'] = is_object($next) ? array($this->getItemUrl($next->getId()), $next->getTitle(), $next->getDefaultPicUrl() ? $next->getDefaultPicUrl()[0] : '') : null;
		return $ret;
	}

   /**
    * 获取内容页地址，这里不进行存在性判断
    *
    * @param int $id
    * @return string
    */
   public function getItemUrl($id)
   {
      return '/article/' . $id . '.html';
   }

   /**
    * 获取回收站列表
    *
    * @param int $id 栏目id
    * @param boolean total 是否返回总数
    * @param string $orderBy 排序方式
    * @param int $offset 
    * @param int $limit 
    * @return array | \Phalcon\Mvc\Model\Resultset
    */
   public function getTrashcanListByNode($id, $total = false, $orderBy = null, $offset = 0, $limit = \Cntysoft\STD_PAGE_SIZE)
   {
      $id = (int) $id;
      //当节点未
      $cond = array(
         'isDeleted = 1'
      );
      if (0 != $id) {
         $cond[] = "nodeId = $id";
      }
      $cond = implode(' and ', $cond);

      $items = GeneralModel::find(array(
            $cond,
            'order' => $orderBy,
            'limit' => array(
               'number' => $limit,
               'offset' => $offset
            )
      ));
      if ($total) {
         return array(
            $items,
            (int) GeneralModel::count($cond)
         );
      }
      return $items;
   }

   /**
    * 获取支持的信息状态
    *
    * @return array
    */
   public function getSupportInfoStatus()
   {
      return array(
         Constant::INFO_S_DRAFT,
         Constant::INFO_S_PEEDING,
         Constant::INFO_S_REJECTION,
         Constant::INFO_S_VERIFY,
         Constant::INFO_S_ALL
      );
   }

   /**
    * 根据筛选条件获取效果图列表
    * 
    * @param array $id 栏目id
    * @param array $gids 筛选出的信息gid数组
    * @param boolean $total 是否返回总数
    * @param string $orderBy 信息排序方式
    * @param integer $offset 使用分页时，要输出的页数
    * @param integer $limit 使用分页时，每页的信息数
    */
   public function getEffectImageList(array $id = array(0), $gids = array(), $total = false, $orderBy = null, $offset = 0, $limit = 15)
   {
      $cond = 'isDeleted = 0 and status = '. Constant::INFO_S_VERIFY . ' and cmodelId = ' . CM_CONST::CONTENT_MODEL_EFFECT_IMAGE . ' and ' . GeneralModel::generateRangeCond('id', $gids);
      if (count($id) && !in_array(0, $id)) {
         $cond .=  ' and '. GeneralModel::generateRangeCond('nodeId', $id);
      }

      $items = GeneralModel::find(array(
            $cond,
            'order' => $orderBy,
            'limit' => array(
               'number' => $limit,
               'offset' => $offset
            )
      ));
      if ($total) {
         return array($items, (int) GeneralModel::count(array(
               $cond
         )));
      }
      return $items;
   }

   /**
    * 获取指定筛选条件的gid数组
    * 
    * @param array $id 栏目id
    * @param array $queryAttrs 筛选条件数组，其中area和houseType是一组，space和part是一组
    * <code>
    *    array(
    *       'color' => 1,
    *       'style' => 1,
    *       'area'  => 1,
    *       'houseType => 1,
    *       'space' => 1,
    *       'part'  => 1
    *    )
    * </code>
    * @return array
    */
   public function getGidsByQuery(array $id = array(), $imageType = 0, $queryAttrs = array())
   {
      $adapter = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME, Constant::APP_NAME, 'Saver\ImageSaver'
      );

      $ret = $adapter->getQueryListByQuery($id, false, $imageType, $queryAttrs);
      $list = array();
      foreach ($ret as $query) {
         array_push($list, $query->getGid());
      }

      return $list;
   }

   /**
    * 统计指定栏目下的信息总数
    * 
    * @param int $cid
    * @param boolean $includeChildren 是否包含子栏目
    * @param int|array $cmodel 信息模型id
    * @param int status 信息状态id
    * @returne int
    */
   public function countInfosForCategory($cid, $includeChildren = false, $cmodel = Constant::INFO_M_ALL, $status = Constant::INFO_S_VERIFY)
   {
      $cid = (int) $cid;
      $ids = array($cid);
      if ($includeChildren) {
         //在这里需要节点管理器APP相关功能
         $categoryTree = $this->getAppCaller()->call(
            CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME,
            CATE_CONST::APP_API_STRUCTURE, 'getTreeObject'
         );
         /**
          * 这里WEBOS直接请求的数据 所以节点基本可以保证存在
          * @todo 是否强制检查节点是否存在？
          */
         $ids = $categoryTree->getChildren($cid, -1, false);
         array_unshift($ids, $cid);
      }
      $cond = array();
      $cond[] = GeneralModel::generateRangeCond('nodeId', $ids);
      $cond[] = 'isDeleted = 0';
      if ($status !== Constant::INFO_S_ALL) {
         $cond[] = "status = $status";
      }
      if (is_array($cmodel)) {
         if (!in_array(Constant::INFO_M_ALL, $cmodel)) {
            $cond[] = GeneralModel::generateRangeCond('cmodelId', $cmodel);
         }
      } else {
         if ($cmodel !== Constant::INFO_M_ALL) {
            $cond[] = "cmodelId = $cmodel";
         }
      }
      $cond = implode(' and ', $cond);
      return GeneralModel::count($cond);
   }

}
