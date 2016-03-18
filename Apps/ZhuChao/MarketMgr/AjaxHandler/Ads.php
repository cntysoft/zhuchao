<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MarketMgr\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\ZhuChao\MarketMgr\Constant;
use App\ZhuChao\MarketMgr\Model\AdsModule;
class Ads extends AbstractHandler
{
   /**
    * 获取广告位置树形图结构
    * 
    * @param array $params ['id']节点id
    * @return array
    */
   public function getAdsModuleTree(array $params)
   {
      $rets = $this->getAppCaller()->call(
              Constant::MODULE_NAME, 
              Constant::APP_NAME, 
              Constant::APP_API_ADS, 
              'getAdsModuleTree', 
              array($params['id']));
      $tree = array();
      foreach ($rets as $ret) {
         $id = $ret->getId();
         if(AdsModule::findFirst(array(
            'pid = ?1',
            'bind' => array(
               1 => $id
            )
         ))){
            $leaf = false;
         } else {
            $leaf = true;
         }
         $child = array(
            'id' => $id,
            'text' => $ret->getName(),
            'leaf' => $leaf
         );
         $tree[] = $child;
      }
      return $tree;
   }
   /**
    * 添加广告
    * 
    * @param array $params 该广告的信息
    * @return integer 该广告的id
    */
   public function addAds(array $params)
   {
      $ret = $this->getAppCaller()->call(
              Constant::MODULE_NAME, 
              Constant::APP_NAME, 
              Constant::APP_API_ADS, 
              'addAds', 
              array($params));
      return $ret;
   }
   /**
    * 修改广告
    * 
    * @param array $params 该广告的id及要修改的字段信息
    * @return integer 该广告的id
    */
   public function modifyAds(array $params)
   {
      $ret = $this->getAppCaller()->call(
              Constant::MODULE_NAME, 
              Constant::APP_NAME, 
              Constant::APP_API_ADS, 
              'modifyAds', 
              array($params['id'],$params));
      return $ret;
   }
   /**
    * 获取广告列表
    * 
    * @param array $params 指定的获取条件
    * @return array 'item'广告信息 'total'广告数量
    */
   public function getAdsList(array $params)
   {
      $rets = $this->getAppCaller()->call(
              Constant::MODULE_NAME, 
              Constant::APP_NAME, 
              Constant::APP_API_ADS, 
              'getAdsList', 
              array($params['locationId'],'id desc',true,$params['limit'],$params['start']));
      $grid = array();
      foreach ($rets['items'] as $ret) {
         $child = array(
            'id' => $ret->getId(),
            'name' => $ret->getName(),
            'locationId' => $ret->getLocationId(),
            'contentUrl' => $ret->getContentUrl(),
            'startTime' => date('Y-m-d', $ret->getStartTime()),
            'endTime' => date('Y-m-d',$ret->getEndTime()),
            'bgcolor' => $ret->getBgcolor(),
            'sort' => $ret->getSort(),
            'image' => $ret->getImage(),
            'fileRefs' => $ret->getFileRefs()
         );
         $grid[] = $child;
      }
      return array('items' => $grid,'total' => $rets['total']);
   }
   /**
    * 删除广告
    * 
    * @param array $params ['id'] 该广告的id
    * @return void
    */
   public function deleteAds(array $params)
   {
      return $this->getAppCaller()->call(
              Constant::MODULE_NAME, 
              Constant::APP_NAME, 
              Constant::APP_API_ADS, 
              'deleteAds', 
              array($params['id']));
   }
}