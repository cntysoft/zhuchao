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
use App\Yunzhan\Category\Constant as CategoryConst;
use App\Yunzhan\Content\Constant as ContentConst;
class CompanySetting extends AbstractDsScript
{
   public function load()
   {
      $node = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodeByIdentifier', array('about')
      );
      $aboutlist = $this->appCaller->call(
              ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(array($node->getId()), 0, ContentConst::INFO_S_VERIFY, FALSE, 'id ASC', 0, 5)
      );
      $ret = array(
         serialize(array(
            'pic'     => $this->getImgcdn('', '', ''),
            'content' => ''
         )),
         serialize(
                 array(
                    'pic'     => '',
                    'content' => ''
         )),
         serialize(
                 array(
                    'pic'     => '',
                    'content' => ''
         )),
         serialize(
                 array(
                    'pic'     => '',
                    'content' => ''
         )),
         serialize(
                 array(
                    'pic'     => '',
                    'content' => ''
         ))
      );
      foreach ($aboutlist as $index => $value) {
         $info = $this->appCaller->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'read', array($value->getId()));

         if ($index) {
            $ret[$index] = serialize(array(
               'content' => $info[1]->getContent()
            ));
         } else {
            $defpic = $info[0]->getDefaultPicUrl()[0];
            $ret[$index] = serialize(array(
               'pic'     => $this->getImgcdn($defpic, 305, 190),
               'content' => $info[1]->getContent()
            ));
         }
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