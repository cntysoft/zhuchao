<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\Site\Setting\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\Site\Setting\Constant as SYS_CFG_CONST;

/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class DefaultHandler extends AbstractHandler
{
   /**
    * <code>
    * array(
    *  'group' => 'group name'
    * );
    * </code>
    *
    * @param array $params
    * @return array
    */
   public function getConfigByGroup(array $params)
   {
      $this->checkRequireFields($params, array('group'));
      $items = $this->getAppCaller()->call(
         SYS_CFG_CONST::MODULE_NAME,
         SYS_CFG_CONST::APP_NAME,
         SYS_CFG_CONST::CLS_CFG,
         'getItemsByGroup',
         array(
            $params['group']
         ));
      $ret = array();
      foreach($items as $item){
         $ret[$item->getKey()] = $item->getValue();
      }
      return $ret;
   }

   /**
    * <code>
    * array(
    *  'group' => 'group name',
    *  'data' => 'data'
    * );
    * </code>
    * @param array $params
    */
   public function saveConfigByGroup(array $params)
   {
      $this->checkRequireFields($params, array(
         'group','data'
      ));

      $group = $params['group'];
      $cfgApi = $this->getAppCaller()->getAppObject(
         SYS_CFG_CONST::MODULE_NAME,
         SYS_CFG_CONST::APP_NAME,
         SYS_CFG_CONST::CLS_CFG
      );

      foreach($params['data'] as $key => $value){
         $cfgApi->setItem($group, $key, $value);
      }

       //每次更新之后删除缓存
      $cfgApi->clearCache();
   }
}