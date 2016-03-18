<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ZhuChao\Phalcon\Mvc;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * 平台自己的数据库Model基类
 *
 * @package Christ\InitFlow
 */
class Model extends BaseModel
{
   public function initialize()
   {
      $this->setConnectionService('siteDb');
   }
}