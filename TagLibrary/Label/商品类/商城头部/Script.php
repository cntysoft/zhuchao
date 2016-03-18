<?php
/**
 * Cntysoft OpenEngine
 *
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Goods;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
/**
 * 商品详情页标签
 */
class ProductTop extends AbstractLabelScript
{
   public function load()
   {
      $nodes = $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_NAV_SETTING, 'getNavList', array(SETTING_CONST::NAV_LINK_TYPE_TOP));
      $out = array();
      foreach ($nodes as $node) {
         $child = array(
            'url'  => $node->getUrl(),
            'name' => $node->getName()
         );
         $out[] = $child;
      }
      return $out;
   }

}