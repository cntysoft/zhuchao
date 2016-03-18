<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
use App\Yunzhan\Product\Constant;
return array(
   'text'        => '产品管理',
   'internalKey' => Constant::PK_APP_KEY,
   'isApp'       => true,
   'hasDetail'   => false,
   'children'    => array(
      array(
         'text'        => '产品管理',
         'internalKey' => Constant::PK_WIDGET_PRODUCT,
         'hasDetail'   => false,
         'codes'       => array()
      )
   )
);
