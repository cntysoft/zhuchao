<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\Sys\AppInstaller\Constant as APP_CONST;
return array(
   'text'        => '应用安装',
   'internalKey' => APP_CONST::APP_NAME,
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text'         => '权限资源挂载',
         'hasDetail'    => false,
         'internalKey'  => APP_CONST::PK_PERM_MGR,
         'codes'        => array()
      )
   )
);