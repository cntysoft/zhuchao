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
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
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

}