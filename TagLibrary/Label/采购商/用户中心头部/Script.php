<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Buyer;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;

class UserHead extends AbstractLabelScript
{
   public function getSiteUrl()
   {
      $config['SiteUrl'] = 'http://'.\Cntysoft\RT_SYS_SITE_NAME;
      $config['BuyerUrl'] = 'http://'.\Cntysoft\RT_BUYER_SITE_NAME;
      $config['ProviderUrl'] = 'http://'.\Cntysoft\RT_PROVIDER_SITE_NAME;
      
      return $config;
   }
}