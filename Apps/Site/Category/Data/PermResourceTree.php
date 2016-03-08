<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\Site\Category\Constant as CM_CONST;
return array(
   'text'        => '栏目管理',
   'internalKey' => 'Category',
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text'         => '结构管理',
         'hasDetail'    => true,
         'internalKey'  => CM_CONST::PK_STRUCTURE_MANAGE,
         'detailSetter' => 'Cms.CategoryManager.CategoryManage',
         'detailSaver'  => 'Category',
         'codes'        => array()
      ),
      array(
         'hasDetail'   => false,
         'text'        => '栏目排序',
         'internalKey' => CM_CONST::PK_NODE_SORT,
         'codes'       => array()
      )
   )
);