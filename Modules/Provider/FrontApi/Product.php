<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Product\Constant as P_CONST;
use App\ZhuChao\CategoryMgr\Constant as CATEGORY_CONST;
/**
 * 主要是处理采购商产品相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Product extends AbstractScript
{
   /**
    * 获取指定分类的子分类列表
    * 
    * @param array $params
    * @return array
    */
   public function getChildCategory(array $params)
   {
      $this->checkRequireFields($params, array('categoryId'));
      
      $list = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'getChildren',
         array((int)$params['categoryId'])
      );
      
      $ret =  array();
      foreach($list as $category){
         $item = array(
            'id' => $category->getId(),
            'text' => $category->getName()
         );
         
         array_push($ret, $item);
      }
      
      return $ret;
   }
}