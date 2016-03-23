<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\ContentModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Yunzhan\Content\Constant as ContentConst;
class CompanySetting extends AbstractDsScript
{
   public function load()
   {
      $ret = array(1 => '', '', '', '');
      foreach ($ret as $index => $value) {
         $info = $this->appCaller->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'read', array($index));
         $ret[$index] = $info[1]->getContent();
      }
      return $ret;
   }

   /**
    * 获得图片url
    * @param type $url
    * @param type $width
    * @param type $height
    * @return string
    */
   public function getImgcdn($url, $width, $height)
   {
      if (!isset($url) || empty($url)) {
         $url = 'Static/lazyicon.png';
      }
      return \Cntysoft\Kernel\get_image_cdn_url($url, $width, $height);
   }

}