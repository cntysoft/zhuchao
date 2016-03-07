<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use Cntysoft\Kernel;
use App\Shop\GoodsMgr\Constant as G_CONST;
use App\Shop\CategoryMgr\Constant as CATE_CONST;
use App\Shop\MarketMgr\Constant as Market_Constant;
use App\Shop\MarketMgr\Model\BargainGoods as BGModel;
class Bargain extends AbstractScript
{
   /**
    * 获取未添加商品列表
    * 
    * @param array $params
    * @return array
    */
   public function getGoodsList($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'id'));
      $bargainlist = $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'getBargainListBy', array(
         array('endTime>' . time())
              )
      );
      foreach ($bargainlist as $bargainItem) {
         foreach ($bargainItem->bargaingoods as $value) {
            $disableGoodsId[] = $value->getGoodsId();
         }
      }
      $cond = array();
      $cond[] = ' status=' . G_CONST::GOODS_STATUS_NORMAL;
      if (isset($disableGoodsId) && !empty($disableGoodsId)) {
         $cond[] = 'id not in (' . implode(',', $disableGoodsId) . ')';
      }
      $gcategoryTree = $this->appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNodeTree'
      );
      $cid = (int) $params['id'];
      if (0 != $cid) {
         $cNodes = $gcategoryTree->getChildren($cid, -1, false);
         array_unshift($cNodes, $cid);
         $cond[] = \Cntysoft\Phalcon\Mvc\Model::generateRangeCond('categoryId', $cNodes);
      }
      $orderBy = 'id DESC';
      $offset = ((int) $params['page'] - 1) * (int) $params['pageSize'];
      $limit = (int) $params['pageSize'];
      $goodsLists = $this->appCaller->call(
              G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getGoodsListBy', array(array(implode(' and ', $cond)), false, $orderBy, $offset, $limit));
      $ret = array();
      foreach ($goodsLists as $goodsList) {
         $goodsStats = $goodsList->getStatsInfo();
         $item = array(
            'id'         => $goodsList->getId(),
            'name'       => $goodsList->getTitle(),
            'pic'        => 'http://' . Kernel\get_server_url() . $goodsList->getImg(),
            'price'      => $goodsList->getPrice(),
            'stock'      => $goodsStats->getStocks(),
            'saleVolume' => $goodsStats->getSoldout(),
            'favor'      => $goodsStats->getCollect()
         );
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 获取已添加商品列表
    * 
    * @param array $params
    * @return array
    */
   public function getBargainGoodsList($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'id'));
      $barginid = (int) $params['id'];
      $orderBy = 'id DESC';
      $offset = ((int) $params['page'] - 1) * (int) $params['pageSize'];
      $limit = (int) $params['pageSize'];
      $barginGoodsList = BGModel::find(array(
                 'bargainId=' . $barginid,
                 'order' => $orderBy,
                 'limit' => array(
                    'number' => $limit,
                    'offset' => $offset
                 )
      ));
      $ret = array();
      foreach ($barginGoodsList as $barginGoods) {
         $goods = $barginGoods->getGoods();
         $item = array(
            'id'    => $goods->getId(),
            'name'  => $goods->getTitle(),
            'pic'   => 'http://' . Kernel\get_server_url() . $goods->getImg(),
            'price' => $goods->getPrice(),
            'std'   => $this->getGoodsAttr($goods->getId(), $barginGoods->getGoodsAttrId())
         );
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 获取商品规格
    * 
    * @param integer $gid
    * @param integer $attrId
    * 
    * @return array
    */
   protected function getGoodsAttr($gid, $attrId)
   {
      $stdAttrs = $this->appCaller->call(
              G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getStdAttrNameMap', array((int)
         $gid
              )
      );
      $keys = array_keys($stdAttrs);
      $goodsAttr = $this->appCaller->call(
              G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getGoodsStdAttr', array((int)
         $gid, $attrId
              )
      );
      $str = '';
      foreach ($goodsAttr->getCombination() as $key => $stdAttr) {
         $str.=$keys[$key] . ':' . $stdAttr . '/';
      }
      $ret = $str . '价格:' . $goodsAttr->getPrice();
      return array($ret);
   }

   /**
    * 获取特卖列表
    * 
    * @param array $params
    * @return array
    */
   public function getBargainList($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      if ($params['status'] == 1) {
         $cond = ' and startTime > ' . time();
      } else if ($params['status'] == 2) {
         $cond = ' and startTime <= ' . time() . ' and endTime > ' . time();
      } else if ($params['status'] == 3) {
         $cond = ' and endTime <= ' . time();
      } else {
         return array();
      }
      $list = $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'getBargainList', array(
         array(' deleted=0 ' . $cond), false, ( $params['page'] - 1) * $params['pageSize'], $params['pageSize']
              )
      );
      foreach ($list as $item) {
         $item = $item->toArray(true);
         $item['count'] = BGModel::count(array('bargainId=' . $item['id']));
         $item['startTime'] = date('Y-m-d H:i', $item['startTime']);
         $item['endTime'] = date('Y-m-d H:i', $item['endTime']);
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 保存特卖信息
    * 
    * @param array $params
    * @return  
    */
   public function saveBargainBase($params)
   {
      $this->checkRequireFields($params, array('id', 'name', 'startTime', 'endTime'));
      $startTime = $this->formatTime($params['startTime']);
      $endTime = $this->formatTime($params['endTime']);
      Kernel\unset_array_values($params, array('startTime', 'endTime'));
      $data['name'] = $params['name'];
      $data['startTime'] = $startTime;
      $data['endTime'] = $endTime;
      return $this->appCaller->call(
                      Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'mobileSaveBargainBase', array($params['id'], $data)
      );
   }

   /**
    * 添加特卖商品信息
    * 
    * @param array $params
    * @return  
    */
   public function saveBargainGoods($params)
   {
      $this->checkRequireFields($params, array('id', 'gId', 'stdList'));
      $stdlist = $params['stdList'];
      if (empty($stdlist)) {
         Kernel\throw_exception(new Exception('规格为空!'));
      }
      $bargainId = $params['id'];
      $goodsId = $params['gId'];
      $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'mobileSaveBargainGoods', array($bargainId, $goodsId, $stdlist)
      );
   }

   /**
    * 获取指定特卖信息
    * 
    * @param array $params
    * @return array
    */
   public function getBargainBase($params)
   {
      $this->checkRequireFields($params, array('id'));
      $bargain = $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'getBargainInfo', array($params['id'])
      );
      $ret = $bargain->toArray(true);
      $ret['startTime'] = date('Y-m-d H:i', $ret['startTime']);
      $ret['endTime'] = date('Y-m-d H:i', $ret['endTime']);
      return $ret;
   }

   /**
    * 获取指定特卖信息
    * 
    * @param array $params
    * @return array
    */
   public function getBargainGoods($params)
   {
      $this->checkRequireFields($params, array('id', 'gId'));
      $bargain = $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'getBargainInfo', array($params['id'])
      );
      $bargaingoods = BGModel::find(array(
                 'bargainId=' . $params['id'] . ' and goodsId=' . $params['gId']
      ));
      $ret = $bargain->toArray(true);
      $ret['startTime'] = date('Y-m-d H:i', $ret['startTime']);
      $ret['endTime'] = date('Y-m-d H:i', $ret['endTime']);
      if (count($bargaingoods) == 0) {
         $goods = $this->appCaller->call(
                 G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getGoodsInfo', array($params['gId'])
         );
         $bargainattrid = array();
      } else {
         $goods = $bargaingoods[0]->getGoods();
         $bargainattrid = array($bargaingoods[0]->getGoodsAttrId() => $bargaingoods[0]);
      }
      $ret['gName'] = $goods->getTitle();
      $ret['pic'] = 'http://' . Kernel\get_server_url() . $goods->getImg();
      $ret['gId'] = $goods->getId();
      $stdAttrs = $this->appCaller->call(
              G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getStdAttrNameMap', array((int)
         $ret['gId']
              )
      );
      $keys = array_keys($stdAttrs);
      $list = $this->appCaller->call(
              G_CONST::MODULE_NAME, G_CONST::APP_NAME, G_CONST::APP_API_GOODS, 'getGoodsStdAttrs', array((int)
         $ret['gId']
              )
      );
      $stdlist = array();
      foreach ($list as $value) {
         $combination = array();
         foreach ($value->getCombination() as $key => $stdAttr) {
            $combination[] = $keys[$key] . ':' . $stdAttr;
         }
         $item = array();
         $item['stdAttrId'] = $value->getId();
         $item['normalPrice'] = $value->getPrice();
         $item['stock'] = $value->getStock();
         $item['combination'] = $combination;
         if (key_exists($value->getId(), $bargainattrid)) {
            $item['checked'] = true;
            $item['specialStock'] = $bargainattrid[$value->getId()]->getTotalNum();
            $item['price'] = $bargainattrid[$value->getId()]->getPrice();
            $item['limit'] = $bargainattrid[$value->getId()]->getPerLimit();
         } else {
            $item['checked'] = false;
         }

         $stdlist[] = $item;
      }
      $ret['stdList'] = $stdlist;
      return $ret;
   }

   /**
    * 删除特卖商品
    * 
    * @param array $params
    */
   public function deleteBargainGoods(array $params = array())
   {
      $this->checkRequireFields($params, array(
         'id', 'idList'
      ));
      $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'mobileDeleteBargainGoods', array($params['id'])
      );
   }

   /**
    * 删除特卖信息
    * 
    * @param array $params
    */
   public function deleteBargain(array $params)
   {
      $this->checkRequireFields($params, array(
         'id'
      ));
      $this->appCaller->call(
              Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'deleteBargain', array($params['id'])
      );
   }

   /**
    * 转换时间
    * 
    * @param string $date
    * @return int
    */
   protected function formatTime($date)
   {
      $fdate = mb_split(' ', $date);
      $dates = mb_split('-', $fdate[0]);
      $times = mb_split(':', $fdate[1]);
      return mktime((int) $times[0], (int) $times[1], 0, (int) $dates[1], (int) $dates[2], (int) $dates[0]);
   }

   /**
    * 验证特卖是否结束
    * 
    * @param int $gid
    * @return boolean
    */
   protected function checkBargained($gid)
   {
      return $this->appCaller->call(
                      Market_Constant::MODULE_NAME, Market_Constant::APP_NAME, Market_Constant::APP_API_BARGAIN, 'checkBargained', array($gid
                      )
      );
   }

}