<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\YunzhanModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Yunzhan\Content\Constant as ContentConst;
use Cntysoft\Kernel;
class StdContentModelDs extends AbstractDsScript
{
   /**
    * @inheritdoc
    */
   public function load()
   {
      if (isset($this->invokeParams['itemId'])) {
         $itemId = $this->invokeParams['itemId'];
      } else {
         $routeInfo = $this->getRouteInfo();
         $itemId = (int) $routeInfo['id'];
      }

      $info = $this->appCaller->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'read', array($itemId));
      if (!$info) {
         return array();
      } else {
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
         $node = $main->getNode();
         $ret['nodeId'] = $node->getId();
         $defaultPicUrl = $ret['defaultPicUrl'];
         unset($ret['defaultPicUrl']);
         $ret['defaultPicUrl'] = Kernel\get_image_cdn_server_url() . '/' . $defaultPicUrl[0];
         $ret['defaultPicUrlRefId'] = $defaultPicUrl[1];
         return $ret;
      }
   }

}