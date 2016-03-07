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
use App\Shop\GoodsMgr\Model\GoodsBasicInfo as GoodsInfoModel;

class Trademark extends AbstractScript
{
   /**
    * 获取品牌列表信息
    * 
    * @return string json格式数组
    * <b>id : 品牌id</b>
    * <b>name : 品牌名称</b>
    * <b>logo : 品牌logo</b>
    * <b>count : 品牌下商品数量</b>
    */
   public function getTrademarksList()
   {
      $trademarks = $this->appCaller->call(
              GOODSCONST::MODULE_NAME, 
              GOODSCONST::APP_NAME, 
              GOODSCONST::APP_API_TRADEMARK, 
              'getTrademarkList', 
              array(false,'id DESC',0,null));
      $data = array();
      foreach ($trademarks as $trademark) {
         $id = $trademark->getId();
         $count = GoodsInfoModel::count(array(
            'trademarkId = ?1',
            'bind' => array(
               1 => $id
            )
         ));
         $item = array(
            'id' => $id,
            'name' => $trademark->getName(),
            'logo' => $this->getImgUrlWithServer($trademark->getLogo()),
            'fileRefs' => $trademark->getFileRefs(),
            'count' => $count
         );
         $data[] = $item;
      }
      return $data;
   }
   /**
    * 添加品牌
    * 
    * @param array $params
    * <b>name : 品牌名称</b>
    * <b>logo : 品牌logo</b>
    * <b>fileRefs : 图片id</b>
    * @return string json格式数组
    * <b>id : 图片id</b>
    * <b>pic : 图片地址</b>
    * @throws Exception 参数传递错误
    */
   public function addTrademark(array $params = array())
   {
      $this->checkRequireFields($params, array('name', 'logo', 'fileRefs'));
      $this->appCaller->call(
              GOODSCONST::MODULE_NAME, 
              GOODSCONST::APP_NAME, 
              GOODSCONST::APP_API_TRADEMARK, 
              'addTrademark',array($params));
      return ;
   }
   /**
    * 修改品牌信息
    * 
    * @param array $params
    * <b>id : 品牌id</b>
    * <b>name : 品牌名称</b>
    * <b>logo : 品牌logo</b>
    * <b>fileRefs : 图片id</b>
    * @return string json格式数组
    * <b>id : 图片id</b>
    * <b>pic : 图片地址</b>
    * @throws Exception 参数错误
    */
   public function updateTrademark(array $params = array())
   {
      $this->checkRequireFields($params, array('id', 'name', 'fileRefs', 'logo'));
      $params['trademarkId'] = $params['id'];
      unset($params['id']);
      $this->appCaller->call(
              GOODSCONST::MODULE_NAME, 
              GOODSCONST::APP_NAME, 
              GOODSCONST::APP_API_TRADEMARK, 
              'updateTrademarkInfo',array($params['trademarkId'],$params));

      return;
   }
}