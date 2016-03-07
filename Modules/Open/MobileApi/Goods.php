<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2015 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\GoodsMgr\Constant as GOODSCONST;
use App\Shop\CategoryMgr\Constant as CATEGORYCONST;
use App\Shop\CategoryMgr\Model\Category as CategoryModel;
use App\Shop\GoodsMgr\Model\GoodsBasicInfo as GoodsInfoModel;
use App\Shop\GoodsMgr\Model\GoodsDetail as GoodsDetailModel;
use App\Shop\GoodsMgr\Model\GoodsStdAttr as GoodsStdAttrModel;
use App\Shop\GoodsMgr\Model\GoodsStatsInfo as StatsModel;
class Goods extends AbstractScript
{
   /**
    * 获取出售中或已下架的商品列表
    * 
    * @param array $params
    * <b>page: 商品页码</b>
    * <b>sortKey : 排序字段</b>
    * <b>sortType : 排序方式</b>
    * <b>type : 出售中或者已出售</b>
    * <b>pageSize : 页码容量</b>
    * @return array
    * @throws Exception 参数异常
    */
   public function getSaleOrUnsaleGoodsList(array $params = array())
   {
      $this->checkRequireFields($params, array('page', 'sortKey', 'sortType', 'type', 'pageSize'));
      $orderBy = $params['sortKey'] . ' ' . $params['sortType'];
      $offset = ((int) $params['page'] - 1) * (int) $params['pageSize'];
      $limit = (int) $params['pageSize'];
      $cond = array(
         'limit' => array(
            'number' => $limit,
            'offset' => $offset
         ),
         'order' => $orderBy
      );
      $stats = StatsModel::find($cond);
      $data = array();
      foreach ($stats as $stat) {
         $gid = $stat->getGid();
         $goodsInfo = $this->appCaller->call(
                 GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'getGoodsInfo', array($gid));
         if ($goodsInfo->getStatus() != $params['type']) {
            continue;
         }
         $item = array(
            'id'         => $gid,
            'name'       => $goodsInfo->getTitle(),
            'pic'        => $this->getImgUrlWithServer($goodsInfo->getImg()),
            'url'        => 'http://' . $_SERVER['HTTP_HOST'] . '/product/' . $goodsInfo->getId() . '.html',
            'stock'      => $stat->getStocks(),
            'saleVolume' => $stat->getSoldout(),
            'favor'      => $stat->getCollect(),
            'price'      => $goodsInfo->getPrice(),
            'label'      => '',
            'discount'   => ''
         );
         $data[] = $item;
      }
      return $data;
   }

   /**
    * 获取分类下的商品列表
    * 
    * @return array
    */
   public function getCategoryGoodsList()
   {
      $childNode = $this->getChildNodesList();
      $data = array();
      foreach ($childNode as $child) {
         $cid = $child['id'];
         $cond = array(
            'categoryId = ?1',
            'bind' => array(
               1 => $cid
            )
         );
         $orderBy = 'id DESC';
         $offset = 0;
         $limit = 2;
         $goodsLists = $this->appCaller->call(
                 GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'getGoodsListBy', array($cond, false, $orderBy, $offset, $limit));
         $count = GoodsInfoModel::count(array(
                    'categoryId = ?1',
                    'bind' => array(
                       1 => $cid
                    )
         ));
         if ($count) {
            $dataGoodsList = array();
            foreach ($goodsLists as $goodsList) {
               $stat = $goodsList->getStatsInfo();
               $item = array(
                  'id'         => $goodsList->getId(),
                  'name'       => $goodsList->getTitle(),
                  'pic'        => $this->getImgUrlWithServer($goodsList->getImg()),
                  'url'        => 'http://' . $_SERVER['HTTP_HOST'] . '/product/' . $goodsList->getId() . '.html',
                  'stock'      => $stat->getStocks(),
                  'saleVolume' => $stat->getSoldout(),
                  'favor'      => $stat->getCollect(),
                  'price'      => $goodsList->getPrice(),
                  'label'      => '',
                  'discount'   => ''
               );
               $dataGoodsList[] = $item;
            }
            $data[] = array(
               'id'        => $cid,
               'name'      => $child['name'],
               'count'     => $count,
               'goodsList' => $dataGoodsList
            );
         }
      }
      return $data;
   }

   /**
    * 根据分类id获取该分类下的商品列表
    * 
    * @param array $params
    * <b>id : 分类id</b>
    * <b>page : 商品页码</b>
    * <b>pageSize : 页码容量</b>
    * @return array
    * @throws Exception 参数异常
    */
   public function getGoodsListsByCategoryId(array $params = array())
   {
      $this->checkRequireFields($params, array('id', 'page', 'pageSize'));
      $gcategoryTree = $this->appCaller->call(
              CATEGORYCONST::MODULE_NAME, CATEGORYCONST::APP_NAME, CATEGORYCONST::APP_API_MGR, 'getNodeTree'
      );
      $cid = $params['id'];
      $cond = array();
      if (0 != $cid) {
         $cNodes = $gcategoryTree->getChildren($cid, -1, false);
         array_unshift($cNodes, $cid);
         $cond[] = \Cntysoft\Phalcon\Mvc\Model::generateRangeCond('status = 1 AND categoryId', $cNodes);
      } else {
         $cond[] = 'status = 1';
      }
      $orderBy = 'id DESC';
      $offset = ((int) $params['page'] - 1) * (int) $params['pageSize'];
      $limit = (int) $params['pageSize'];
      $goodsLists = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'getGoodsListBy', array($cond, false, $orderBy, $offset, $limit));
      $data = array();
      foreach ($goodsLists as $goodsList) {
         $stat = $goodsList->getStatsInfo();
         $item = array(
            'id'         => $goodsList->getId(),
            'name'       => $goodsList->getTitle(),
            'pic'        => $this->getImgUrlWithServer($goodsList->getImg()),
            'url'        => 'http://' . $_SERVER['HTTP_HOST'] . '/product/' . $goodsList->getId() . '.html',
            'stock'      => $stat->getStocks(),
            'saleVolume' => $stat->getSoldout(),
            'favor'      => $stat->getCollect(),
            'price'      => $goodsList->getPrice(),
            'label'      => '',
            'discount'   => ''
         );
         $data[] = $item;
      }
      return $data;
   }

   /**
    * 获取叶子分类的列表
    * 
    * @return string json格式的数组
    * <b>id : 叶子分类的id</b>
    * <b>name : 叶子分类的名称</b>
    * @return array 
    */
   public function getChildNodesList()
   {
      $nodes = CategoryModel::find(array(
                 'nodeType = ?1',
                 'bind' => array(
                    1 => 2
                 )
      ));
      $data = array();
      foreach ($nodes as $node) {
         $data[] = array(
            'id'   => $node->getId(),
            'name' => $node->getName()
         );
      }
      return $data;
   }

   /**
    * 根据商品分类id获取商品属性
    * @param array $params
    * <b>id : 分类id</b>
    * @return array
    * @throws Exception 参数异常
    */
   public function getAttrsByCategoryId(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $trademarks = $this->appCaller->call(GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_TRADEMARK, 'getTrademarkList', array(false, 'id DESC', 0, null));
      $brand = array();
      foreach ($trademarks as $trademark) {
         $id = $trademark->getId();
         $item = array(
            'id'   => $id,
            'name' => $trademark->getName()
         );
         $brand[] = $item;
      }
      $attrs = $this->appCaller->call(
              CATEGORYCONST::MODULE_NAME, CATEGORYCONST::APP_NAME, CATEGORYCONST::APP_API_MGR, 'getNodeAttrs', array($params['id']));
      $data = array();
      $group = array();
      $groupKey = array();
      foreach ($attrs as $attr) {
         $groupName = $attr->getGroup();
         if (!array_key_exists($groupName, $group)) {
            $group[$groupName] = array(
               'group' => $groupName,
               'attrs' => array(
                  array(
                     'name'  => $attr->getName(),
                     'value' => explode(',', $attr->getOptValue())
                  )
               )
            );
            continue;
         }
         $group[$groupName]['attrs'][] = array(
            'name'  => $attr->getName(),
            'value' => explode(',', $attr->getOptValue())
         );
      }
      foreach ($group as $value) {
         $groupKey[] = $value;
      }
      $data['groupAttrs'] = $groupKey;
      $data['brand'] = $brand;
      return $data;
   }

   /**
    * 添加商品(仅带属性)
    * @param array $params
    * @return array
    */
   public function addGoodsWithAttrs(array $params = array())
   {
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'addGoodsWithAttrs', array($params));
      return $ret;
   }

   /**
    * 根据分类id获取该分类下的规格信息
    * 
    * @param array $params
    * <b>id : 分类id</b>
    * @return array
    * <b>name : 规格名称</b>
    * <b>value : 规格的值,数组形式</b>
    * @throws Exception 参数异常
    */
   public function getStdAttrByCategoryId(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $stdAttrs = $this->appCaller->call(
              CATEGORYCONST::MODULE_NAME, CATEGORYCONST::APP_NAME, CATEGORYCONST::APP_API_MGR, 'getNodeStdAttrs', array($params['id']));
      $data = array();
      foreach ($stdAttrs as $stdAttr) {
         $item = array(
            'name' => $stdAttr->getName()
         );
         $values = explode(',', $stdAttr->getOptValue());
         foreach ($values as $value) {
            $item['value'][] = explode('|', $value)[0];
         }
         $data[] = $item;
      }
      return $data;
   }

   /**
    * 添加商品规格信息
    * 
    * @param array $params
    * @return array
    */
   public function addGoodsStdAttrs(array $params = array())
   {
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'addGoodsStdAttrs', array($params));
      return $ret;
   }

   /**
    * 添加商品的详细图片展示
    * @param array $params
    * @return array
    * @throws Exception 参数错误
    */
   public function addGoodsContentPic(array $params = array())
   {
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'addGoodsContentPic', array($params));
      return $ret;
   }

   /**
    * 获取商品的图文详情
    * 
    * @param array $params
    * <b>id : 商品id</b>
    * @return array
    * @throws Exception
    */
   public function getGoodsPicContent(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $detail = GoodsDetailModel::findFirst(array(
                 'gid = ?1',
                 'bind' => array(
                    1 => $params['id']
                 )
      ));
      $images = $detail->getDetailInfoImages();
      $picContent = array();
      foreach ($images as $image) {
         $picContent[] = array(
            'id'   => $image[1],
            'url'  => $image[0],
            'base' => 'http://' . $_SERVER['HTTP_HOST']
         );
      }
      return $picContent;
   }

   /**
    * 改变商品的状态
    * @param array $params
    * <b>id : 商品id</b>
    * <b>status : 商品状态</b>
    * @return array
    * @throws Exception
    */
   public function changeGoodsStatus(array $params = array())
   {
      $this->checkRequireFields($params, array('id', 'status'));
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'changeGoodsStatus', array($params['id'], $params['status']));
      return $ret;
   }

   /**
    * 根据商品id获取该商品的基本信息
    * 
    * @param array $params
    * @return array
    */
   public function getGoodsBaseInfo(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $goodsInfo = GoodsInfoModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $params['id']
                 )
      ));
      if(!$goodsInfo) {
         
      }
      $category = $goodsInfo->getCategory();
      $trademarkId = $goodsInfo->getTrademarkId();
      $trademarks = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_TRADEMARK, 'getTrademarkList', array(false, 'id DESC', 0, null));
      $brand = array();
      foreach ($trademarks as $value) {
         $id = $value->getId();
         $item = array(
            'id'   => $id,
            'name' => $value->getName()
         );
         $brand[] = $item;
      }
      $detail = GoodsDetailModel::findFirst(array(
                 'gid = ?1',
                 'bind' => array(
                    1 => $params['id']
                 )
      ));
      $normalAttrs = $detail->getNormalAttrs();
      $attrs = $this->appCaller->call(
              CATEGORYCONST::MODULE_NAME, CATEGORYCONST::APP_NAME, CATEGORYCONST::APP_API_MGR, 'getNodeAttrs', array($goodsInfo->getCategoryId()));
      $opv = array();
      foreach ($attrs as $attr) {
         $optvalue = explode(',', $attr->getOptvalue());
         $opv[$attr->getName()] = $optvalue;
      }
      $group = array();
      foreach ($normalAttrs as $key => $value) {
         $attrs = array();
         foreach ($value as $k => $v) {
            $attrs[] = array(
               'attr'  => $v,
               'name'  => $k,
               'value' => $opv[$k]
            );
         }
         $group[] = array(
            'attrs' => $attrs,
            'group' => $key
         );
      }
      $content = $goodsInfo->getDetailInfo()->getDetailInfoImages();
      $std = $goodsInfo->getDetailInfo()->getStdAttrMap();
      $data = array(
         'id'         => $goodsInfo->getId(),
         'name'       => $goodsInfo->getTitle(),
         'cId'        => $goodsInfo->getCategoryId(),
         'cName'      => $category->getName(),
         'bId'        => $trademarkId,
         'brand'      => $brand,
         'status'     => $goodsInfo->getStatus(),
         'content'    => !empty($content) ? true : false,
         'goodsStd'   => !empty($std) ? true : false,
         'groupAttrs' => $group
      );
      return $data;
   }

   /**
    * 根据商品id修改该商品的基本信息与属性信息
    * 
    * @param array $params
    * @return array
    */
   public function updateGoodsBaseInfo(array $params = array())
   {
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'updateGoodsBaseInfo', array($params['id'], $params['status']));
      return $ret;
   }

   /**
    * 根据商品id获取该商品的规格信息
    * 
    * @param array $params
    * @return array
    */
   public function getGoodsStdAttrInfo(array $params = array())
   {
      $this->checkRequireFields($params, array('id'));
      $attrs = GoodsStdAttrModel::find(array(
                 'gid = ?1',
                 'bind' => array(
                    1 => $params['id']
                 )
      ));
      $data = array();
      foreach ($attrs as $attr) {
         $images = $attr->getImages();
         $image = array();
         foreach ($images as $value) {
            $image[] = array(
               'url'  => $value[0],
               'id'   => $value[1],
               'base' => 'http://' . $_SERVER['HTTP_HOST']
            );
         }
         $item = array(
            'normalPrice' => $attr->getNormalPrice(),
            'price'       => $attr->getPrice(),
            'stock'       => $attr->getStock(),
            'stdAttrId'   => $attr->getId(),
            'combination' => $attr->getStdMap(),
            'images'      => $image
         );
         $data[] = $item;
      }
      return $data;
   }

   /**
    * 根据商品id与商品规格id更新商品的规格信息
    * 
    * @param array $params
    * @return array
    */
   public function updateGoodsStdAttrInfo(array $params = array())
   {
      $ret = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, GOODSCONST::APP_NAME, GOODSCONST::APP_API_GOODS, 'updateGoodsStdAttrInfo', array($params));
      return $ret;
   }

}