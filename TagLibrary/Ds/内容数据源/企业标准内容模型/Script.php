<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\ContentModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Yunzhan\Content\Constant as ContentConst;
use App\Yunzhan\Category\Constant as CategoryConst;
class CompanyStdContentModelDs extends AbstractDsScript
{
   /**
    * @inheritdoc
    */
   public function load()
   {
      if (isset($this->invokeParams['newsid'])) {
         $itemId = $this->invokeParams['newsid'];
      } else {
         $routeInfo = $this->getRouteInfo();
         $itemId = (int) $routeInfo['newsid'];
      }
      
      $info = $this->appCaller->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'read', array($itemId));
      $main = $info[0];
      $mainValues = $main->toArray(true);
      $sub = $info[1];
      $subValues = $sub->toArray(true);
      unset($subValues['id']);
      $ret = array();
      $skip = array('itemId', 'templateFile', 'isDeleted', 'status', 'priority', 'fileRefs'); //这些键值对应的值不应在前台页面展示
      foreach ($mainValues as $key => $value) {
         $ret[$key] = $value;
      }
      foreach ($subValues as $key => $value) {
         $ret[$key] = $value;
      }
      foreach ($skip as $item) {
         unset($ret[$item]);
      }
      $node = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNode', array($ret['nodeId']));
      $ret['nodeText'] = $node->getText();
      $ret['nodeIdentifier'] = $node->getNodeIdentifier();
      $ret['url'] = $this->getItemUrl($itemId);
      $this->setupItemPrevAndNext($mainValues, $ret);
      return $ret;
   }

   /**
    * 获取上一篇下一篇URL
    * 
    * @param array $main
    * @param array $data
    */
   protected function setupItemPrevAndNext($main, array &$data)
   {
      $links = $this->appCaller->call(
              ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_INFO_LIST, 'getPrevAndNextItem', array($main['nodeId'], $main['id'])
      );

      foreach ($links as $key => $val) {
         $data[$key] = $val;
      }
   }

   /**
    * 获取当前文章链接
    * 
    */
   protected function getItemUrl($itemId)
   {
      return '/news/' . $itemId . '.html';
   }

}