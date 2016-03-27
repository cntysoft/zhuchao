<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Site\Content\Constant as CONTENT_CONST;
use App\ZhuChao\MarketMgr\Constant as MAR_CONST;
use Cntysoft\Kernel;

/**
 * 主要是处理采购商产品相关的的Ajax调用
 * 
 * @package ProviderMobileApi
 */
class Article extends AbstractScript
{
   protected $tree = null;
   
   /**
    * 获取首页的信息
    * 
    * @return type
    */
   public function getHomeArticleList()
   {
      $adList = array();
      $sectionList = array();
      $locationId = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsLocationId', array('手机端', '首页', 'banner'));
      if($locationId){
         $ads = $this->appCaller->call(MAR_CONST::MODULE_NAME, MAR_CONST::APP_NAME, MAR_CONST::APP_API_ADS, 'getAdsList', array($locationId, 'sort asc'));
         if (count($ads)) {
            foreach ($ads as $ad) {
               $url = $ad->getContentUrl();
               $image = $this->getImageCdnUrl($ad->getImage(), array('h' => 300));
               $adList[] = array(
                  'img' => $image,
                  'html' => $url
               );
            }
         }
      }
      
      $tree = $this->getNodeTree();
      $result = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,  
         CATEGORY_CONST::APP_API_STRUCTURE,
         'getNodesByIdentifiers',
         array(array('zhuchaoschool', 'laobanneican'))
      );
      
      foreach($result as $node){
         $children = $tree->getChildren($node->getId(), 1, true);
         foreach($children as $one){
            $list = $this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($one->getId(), 1, CONTENT_CONST::INFO_S_VERIFY, true, 'hits DESC', 0, 4));
            if(count($list[0])){
               $item = array(
                  'id'   => $one->getId(),
                  'pId'  => $one->getPid(),
                  'name' => $one->getText(),
                  'count' => $list[1],
                  'articleList' => array()
               );
               foreach($list[0] as $article){
                  $defaultPicUrl = $article->getDefaultPicUrl();
                  $item['articleList'][] = array(
                     "id"   => $article->getId(),
                     "name" => $article->getTitle(),
                     "desc" => $article->getIntro(),
                     "pic"  => is_array($defaultPicUrl) ? $this->getImageCdnUrl($defaultPicUrl[0], array('w'=> 320, 'h'=>200, 'c'=>1)) : '',
                     "url"  => $this->getArticleUrl($article->getId()),
                     "hits" => $article->getHits(),
                     "time" => date('Y-m-d', $article->getInputTime())
                  );
               }
               
               array_push($sectionList, $item);
            }
         }
      }
      
      return array(
         'adList' => $adList,
         'sectionList' => $sectionList
      );
   }
   
   /**
    * 获取指定栏目id的文章信息
    * 
    * @param array $params
    * @return array
    */
   public function getArticleList(array $params)
   {
      $this->checkRequireFields($params, array('ids', 'sortKey', 'sortType', 'page', 'pageSize', 'listSub'));
      
      $ids = (array)$params['ids'];
      $orderBy = $params['sortKey'] . ' ' . $params['sortType'];
      $limit = $params['pageSize'];
      $offset = ($params['page'] - 1) * $limit;
      if(1 == $params['listSub']){
         $tree = $this->getNodeTree();
         $nodes = array();
         foreach ($ids as $id){
            $list = $tree->getChildren($id, -1);
            $nodes = array_merge($nodes, $list);
         }
         
         $nodes = array_merge($nodes, $ids);
         $ids = array_unique($nodes);
      }

      $result = $this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($ids, 1, CONTENT_CONST::INFO_S_VERIFY, false, $orderBy, $offset, $limit));
      
      $ret = array();
      if(count($result)){
         foreach($result as $article){
            $defaultPicUrl = $article->getDefaultPicUrl();
            $item = array(
               "id"   => $article->getId(),
               "name" => $article->getTitle(),
               "desc" => $article->getIntro(),
               "pic"  => is_array($defaultPicUrl) ? $this->getImageCdnUrl($defaultPicUrl[0], array('w'=> 320, 'h'=>200, 'c'=>1)) : '',
               "url"  => $this->getArticleUrl($article->getId()),
               "hits" => $article->getHits(),
               "time" => date('Y-m-d', $article->getInputTime())
            );
            
            array_push($ret, $item);
         }
      }
      
      return $ret;
   }
   
   /**
    * 获取老板内参和筑巢商学院的栏目
    * 
    * @return array
    */
   public function getArticleNodes()
   {
      $tree = $this->getNodeTree();
      $result = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,  
         CATEGORY_CONST::APP_API_STRUCTURE,
         'getNodesByIdentifiers',
         array(array('zhuchaoschool', 'laobanneican'))
      );
      
      $nodes = array();
      foreach($result as $one){
         $item = array(
            'id'    => $one->getId(),
            'name'  => $one->getText(),
            'pId'   => $one->getPid(),
            'leaf'  => false,
            'level' => 1
         );
         
         array_push($nodes, $item);
      }

      foreach ($nodes as $node){
         $this->getChildNode($node['id'], $tree, $nodes);
      }

      return $nodes;
   }
   
   
   /**
    * 递归获取子栏目节点
    * 
    * @param type $id
    * @param type $tree
    * @param type $ret
    */
   protected function getChildNode($id, $tree, &$ret)
   {
      $list = $tree->getChildren($id, 1, true);
      if(count($list)){
         foreach($list as $one){
            $item = array(
               'id'    => $one->getId(),
               'name'  => $one->getText(),
               'pId'   => $one->getPid(),
               'leaf'  => $tree->isLeaf($one->getId()),
               'level' => $tree->getLayer($one->getId())
            );
            
            array_push($ret, $item);
            $this->getChildNode($one->getId(), $tree, $ret);
         }
      }
   }

   /**
    * 获取栏目节点树
    * 
    * @return
    */
   protected function getNodeTree()
   {
      if(null == $this->tree){
         $this->tree = $this->appCaller->call(CATEGORY_CONST::MODULE_NAME, CATEGORY_CONST::APP_NAME, CATEGORY_CONST::APP_API_STRUCTURE, 'getTreeObject');
      }
      
      return $this->tree;
   }
   
   /**
    * 获取图片的cdn地址
    * 
    * @param string $imageUrl
    * @param array $arguments
    */
   protected function getImageCdnUrl($imageUrl, array $arguments = array())
   {
      if($imageUrl){
         return Kernel\get_image_cdn_url_operate($imageUrl, $arguments);
      }else{
         return '';
      }
   }
   
   /**
    * 获取文章的地址
    * 
    * @param type $id
    * @return type
    */
   protected function getArticleUrl($id)
   {
      return 'http://'.\Cntysoft\RT_SYS_SITE_NAME . '/article/' . $id . '.html';
   }
}