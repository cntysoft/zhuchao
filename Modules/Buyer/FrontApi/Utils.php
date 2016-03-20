<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript as BaseApiScript;
use Cntysoft\Framework\Utils\ChinaArea;
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

}