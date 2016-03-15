<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use App\ZhuChao\MessageMgr\Constant;
return array(
   'text'        => '消息管理',
   'internalKey' => 'MessageMgr',
   'isApp'       => true,
   'hasDetail'   => true,
   'children'    => array(
      array(
         'text'        => '询价报价管理',
         'hasDetail'   => false,
         'internalKey' => Constant::PK_OFFER,
         'codes'       => array()
      )
   )
);
