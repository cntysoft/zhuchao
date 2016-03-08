<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Content\Saver;
use Cntysoft\Kernel\App\AbstractLib;
use App\Site\CmMgr\Constant as CMM_CONST;
/**
 * 实现基本的保存逻辑
 */
abstract class AbstractSaver extends AbstractLib implements SaverInterface
{
   /**
    * @param string $key
    * @return string
    */
   protected function getModelCls($key)
   {
      return $this->getAppCaller()->call(
         CMM_CONST::MODULE_NAME,
         CMM_CONST::APP_NAME,
         CMM_CONST::APP_API_MGR,
         'getModelCls',
         array(
            $key
         )
      );
   }
}