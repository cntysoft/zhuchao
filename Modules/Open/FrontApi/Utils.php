<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use KeleShop\Framework\OpenApi\AbstractScript as BaseApiScript;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;
use App\ZhuChao\MarketMgr\Constant;
/**
 * 这个类负责处理前端的一些通用数据的AJAX调用
 * 
 * 这个类当中的所有接口都不需要验证
 * 
 * @package FrontApi
 */
class Utils extends BaseApiScript
{
   /**
    * 中国地理信息类
    *
    * @var \Cntysoft\Framework\Utils\ChinaArea
    * @return array
    */
   protected $chinaArea = null;

   public function getProvinces()
   {
      $chinaArea = $this->getChinaArea();

      return $chinaArea->getProvinces();
   }

   /**
    * 获取指定省,市的所有子地区
    * 
    * @param array $params
    * @return array
    */
   public function getChildArea($params)
   {
      $this->checkRequireFields($params, array('code'));
      $china = $this->getChinaArea();

      return $china->getChildArea($params['code']);
   }

   /**
    * 获取制定Code的地区信息
    * 
    * @param array $params
    * @return string
    */
   public function getArea($params)
   {
      $this->checkRequireFields($params, array('code'));
      $china = $this->getChinaArea();

      return $china->getArea($params['code']);
   }

   /**
    * 单一模式获取ChianArea
    * 
    * @return \Cntysoft\Framework\Utils\ChinaArea
    */
   protected function getChinaArea()
   {
      if (null == $this->chinaArea) {
         $this->chinaArea = new ChinaArea();
      }

      return $this->chinaArea;
   }

   public function shopJoin($params)
   {
      if ($params['sellerName'] && $params['name'] && $params['phoneNum'] && $params['part'] && $params['floor']) {
         $this->appCaller->call(
                 Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_SELLERSIGN, 'addSeller', array($params));
      } else {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_INPUTDATA_CODE_ERROR'), $errorType->code('E_FRONT_INPUTDATA_CODE_ERROR')));
      }
   }

   public function userAskfor($params)
   {
      if ($params['name'] && $params['phoneNum'] && $params['area']) {
         $this->appCaller->call(
                 Constant::MODULE_NAME, Constant::APP_NAME, Constant::APP_API_USERASKFOR, 'addAskforUser', array($params));
      } else {
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_FRONT_INPUTDATA_CODE_ERROR'), $errorType->code('E_FRONT_INPUTDATA_CODE_ERROR')));
      }
   }

}