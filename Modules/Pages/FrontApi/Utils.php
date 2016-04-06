<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace PagesFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Site\Content\Constant as CONTENT_CONST;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY;
/**
 * 处理系统上传
 *
 * Class WebUploader
 * @package SysApiHandler
 */
class Utils extends AbstractScript
{
   /**
    * 增加商品点击量
    * 
    * @param array $params
    */
   public function addItemHits($params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'addHit', array($params['id']));
   }

   /**
    * 增加文章点击量
    * 
    * @param array $params
    */
   public function addArticleHits($params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'addHit', array($params['id']));
   }

   /**
    * 增加商品点击量
    * @param array $params
    * @return 
    */
   public function addHits($params)
   {
      $this->checkRequireFields($params, array('number'));
      $number = $params['number'];
      $this->appCaller->call(PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'addProductHits', array($number));
   }

   public function getInfoListByNodeAndStatus(array $params)
   {
      $this->checkRequireFields($params, array('limit', 'page', 'node'));
      $nodeInfo = $this->getNodeInfoByIdentifier($params['node']);
      $limit = (int) $params['limit'];
      $offset = ((int) $params['page'] - 1) * $limit;
      $nodeId = $nodeInfo->getId();
      $generalInfo = $this->appCaller->call(
              CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, 1, 3, true, 'id DESC', $offset, $limit));
      $ret = array();
      foreach ($generalInfo[0] as $value) {
         $children = array(
            'title'  => $value->getTitle(),
            'author' => $value->getAuthor(),
            'time'   => date('Y-m-d', $value->getInputTime()),
            'hits'   => $value->getHits(),
            'image'  => \Cntysoft\Kernel\get_image_cdn_url($value->getDefaultPicUrl()[0], 110, 110),
            'id'     => $value->getId()
         );
         $ret[] = $children;
      }
      return $ret;
   }

   /**
    * 获取节点信息
    * @param string $identifier
    * @return 
    */
   public function getNodeInfoByIdentifier($identifier)
   {
      $this->checkNodeIdentifier($identifier);
      $nodeInfo = $this->appCaller->call(
              CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getNodeByIdentifier', array($identifier));
      return $nodeInfo;
   }

   /**
    * 检查节点是否存在
    * @param string $identifier
    * @return boolean
    */
   public function checkNodeIdentifier($identifier)
   {
      return $this->appCaller->call(
                      CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'checkNodeIdentifier', array($identifier));
   }

   /**
    * 根据指定条件获取商品列表
    * 
    * @param array $cond 查询条件
    * @param boolean $total 是否分页
    * @param string $orderBy 排序方式
    * @param integer $offset 起始
    * @param ingeter $limit 每页多少个
    * @return object 商品对象列表
    */
   public function getGoodsListBy(array $params)
   {
      $limit = (int) $params['limit'];
      $offset = ((int) $params['page'] - 1) * $limit;
      $cid = $params['cid'];
      $tree = $this->appCaller->call(
              CATEGORY::MODULE_NAME, CATEGORY::APP_NAME, CATEGORY::APP_API_MGR, 'getNodeTree'
      );
      $ids = $tree->getChildren($cid, -1);
      array_push($ids, $cid);
      $idsText = 'categoryId IN (' . implode(",", $ids) . ') AND status = 3';
      $cond = array(
         $idsText
      );
      $goodsList = $this->appCaller->call(
              PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductList', array($cond, false, 'inputTime DESC', $offset, $limit));
      $ret = array();
      foreach ($goodsList as $list) {
         $children = array(
            'id'     => $list->getId(),
            'number' => $list->getNumber(),
            'name'   => $list->getBrand() . $list->getTitle() . $list->getDescription(),
            'price'  => $list->getPrice() > 0 ? $list->getPrice() : '面议',
            'image'  => \Cntysoft\Kernel\get_image_cdn_url($list->getDefaultImage(), 108, 108)
         );
         $ret[] = $children;
      }
      return $ret;
   }

   /**
    * 手机段获取老板内参列表
    * @param array $params
    * @return type
    */
   public function getArticleList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit', 'nodeIdentifier'));
      $infolist = $this->getArticles($params);
      $ret = array();
      foreach ($infolist as $info) {
         $imgcls = $imgurl = '';
         $defpic = $info->getDefaultPicUrl()[0];
         $infourl = '/article/' . $info->getId() . '.html';
         if ($defpic) {
            $imgurl = '<div class="new_img">
                        <div class="new_table">
                            <img src="' . $this->getImgcdn($defpic, 110, 110) . '" alt="">
                        </div>
                    </div>';
         } else {
            $imgcls = 'class="no_img"';
         }
         $ret[] = array(
            'imgcls'  => $imgcls,
            'infourl' => $infourl,
            'imgurl'  => $imgurl,
            'title'   => $info->getTitle(),
            'time'    => date('Y-m-d', $info->getInputTime()),
            'author'  => $info->getAuthor(),
            'hits'    => $info->getHits()
         );
      }
      return $ret;
   }

   /**
    * 手机段获取商学院列表
    * @param array $params
    * @return type
    */
   public function getCollegeArticleList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit', 'nodeIdentifier'));
      $infolist = $this->getArticles($params);
      $ret = array();
      foreach ($infolist as $info) {
         $imgurl = '';
         $defpic = $info->getDefaultPicUrl()[0];
         $infourl = '/article/' . $info->getId() . '.html';
         if ($defpic) {
            $imgurl = '<a href="' . $infourl . '"> <img src="' . $this->getImgcdn($defpic, 300, 300) . '" alt=""/></a>';
         }
         $ret[] = array(
            'infourl' => $infourl,
            'imgurl'  => $imgurl,
            'title'   => $info->getTitle(),
            'time'    => date('Y-m-d', $info->getInputTime()),
            'author'  => $info->getAuthor(),
            'hits'    => $info->getHits(),
            'intro'   => $info->getIntro()
         );
      }
      return $ret;
   }

   /**
    * 获取文章列表
    * 
    * @param array $params
    */
   protected function getArticles($params)
   {
      $nodeId = array();
      $node = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getNodesByIdentifiers', array(
         explode(',', $params['nodeIdentifier'])
      ));
      foreach ($node as $item) {
         array_push($nodeId, $item->getId());
      }
      return $this->appCaller->call(
                      CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(
                 $nodeId, 0, CONTENT_CONST::INFO_S_VERIFY, false, 'id desc', $params['page'] * $params['limit'], $params['limit']
      ));
   }

   /**
    * 获得图片url
    * @param type $item
    * @param type $width
    * @param type $height
    * @return string
    */
   private function getImgcdn($url, $width, $height)
   {
      if (!isset($url) || empty($url)) {
         $url = 'Statics/Skins/Pc/Images/lazyicon.png';
      }
      return \Cntysoft\Kernel\get_image_cdn_url_operate($url, array('w' => $width, 'h' => $height));
   }

}