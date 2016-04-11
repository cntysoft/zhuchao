<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
use App\ZhuChao\Service\Constant;
return array(
   'text'        => '客户服务',
   'internalKey' => Constant::PK_APP_KEY,
   'isApp'       => true,
   'hasDetail'   => false,
   'children'    => array(
      array(
         'text'        => '用户反馈',
         'internalKey' => Constant::PK_WIDGET_FEEDBACK,
         'hasDetail'   => false,
         'codes'       => array()
      )
   )
);
