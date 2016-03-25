<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Provider;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
class UserHead extends AbstractLabelScript
{
   /**
    * 
    * @param string $checkstr
    * @return string
    */
   public function checkUrl($checkstr)
   {
      $query = $this->di->get('request')->getQuery();
      return isset($query['_url']) && iconv_strpos($query['_url'], $checkstr) > 0 ? 'main_bg' : '';
   }

   /**
    * 
    * @param string $checkstr
    * @return string
    */
   public function checkFirst()
   {
      $query = $this->di->get('request')->getQuery();
      return isset($query['_url']) ? '' : 'main_bg';
   }

}