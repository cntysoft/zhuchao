<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider;
 /**
 * @author Changwang <chenyongwaNG@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Kernel\App\AbstractLib;
class Acl extends AbstractLib
{
   public function isLogin()
   {
      return false;
   }
}
