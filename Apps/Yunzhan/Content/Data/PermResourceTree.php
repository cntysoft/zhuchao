<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\Yunzhan\Content\Constant;
return array(
   'text'        => '内容管理',
   'internalKey' => 'Content',
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text' => '信息列表',
         'internalKey' => Constant::PK_LIST_VIEW,
         'hasDetail' => false,
         'codes' => array()
      ),
      array(
         'text' => '回收站',
         'internalKey' => Constant::PK_TRASHCAN,
         'hasDetail' => false,
         'codes' => array()
      )
   )
);
