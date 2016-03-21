<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\Sites;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\Site\Setting\Constant as SETTING_CONST;
class Sites extends AbstractDsScript
{
   /**
    * @inheritdoc
    */
   public function load()
   {
		$config = $this->appCaller->call(SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'getSiteConfig');
		return $config;
   }


}