<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\ZhuChao\Provider\Constant;
return array(
   'text'        => '供应商管理',
   'internalKey' => 'Provider',
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text'        => '供应商管理',
         'hasDetail'   => false,
         'internalKey' => Constant::PK_PROVIDER_MANAGE,
         'codes'       => array()
      ),
      array(
         'hasDetail'   => false,
         'text'        => '企业管理',
         'internalKey' => Constant::PK_PROVIDER_COMPANY,
         'codes'       => array()
      )
   )
);
