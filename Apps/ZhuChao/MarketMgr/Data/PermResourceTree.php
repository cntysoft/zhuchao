<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\ZhuChao\MarketMgr\Constant;
return array(
   'text'        => '营销管理',
   'internalKey' => Constant::PK_APP_KEY,
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text'        => '广告位管理',
         'hasDetail'   => false,
         'internalKey' => Constant::PK_WIDGET_ADS,
         'codes'       => array()
      )
   )
);
