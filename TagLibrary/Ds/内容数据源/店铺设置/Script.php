<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\ContentModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Yunzhan\Setting\Constant as SETTING_CONST;

class SiteSetting extends AbstractDsScript
{

   public function load()
   {
      $config = $this->appCaller->call(
         SETTING_CONST::MODULE_NAME,
         SETTING_CONST::APP_NAME,
         SETTING_CONST::APP_API_CFG,
         'getItemsByGroup',
         array('Site')
      );
      
      $ret = array('banner' => array());
      foreach ($config as $one){
         $key = $one->getKey();
         $value = $one->getValue();
         if('Banner' == $key){
            $value = unserialize($value);
            foreach($value as $val){
               $ret['banner'][] = array(
                  'image' => $val[0],
                  'id'    => $val[1],
                  'url'   => $val[2]
               );
            }
         }
      }
      
      return $ret;
   }

}